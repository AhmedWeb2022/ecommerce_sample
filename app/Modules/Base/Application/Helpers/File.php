<?php

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


function uploadImage($file, $folder, $disk = 'public')
{
    if (is_string($file) && isBase64($file)) {
        // Convert base64 to UploadedFile
        $file = convertBase64ToUploadedFile($file);
    }
    if (!$file) return null;
    $path = $file->store("uploads/{$folder}", [
        'disk' => $disk
    ]);

    return $path;
}

function updateImage($file, string $folder, Model $model, string $fileName, $disk = 'public')
{
    if ($model && $model->$fileName) deleteFile($model->$fileName);
    return uploadImage($file, $folder, $disk);
}

function updateOrUploadImage($file, string $folder, Model $model, string $fileName, $disk = 'public')
{
    // dd($model->$fileName);
    if ($model && $model->$fileName) {
        // dd($model->$fileName);
        deleteFile($model->$fileName);
    }
    return uploadImage($file, $folder, $disk);
}

// i want function to store multiple images for attachment table

//function uploadImages($names, $title)
//{
//    $paths = [];
//
//    // Ensure $files is always an array
//    if (!is_array($names)) {
//        $files = [$names];
//    } else {
//        $files = $names;
//    }
//
//    foreach ($files as $file) {
//        if ($file) { // Ensure the file is valid
////            if (is_base64_string($file)) {
////                // Convert base64 to UploadedFile
////                $file = convertBase64ToUploadedFile($file);
////            }
//            $path = $file->store($title, [
//                'disk' => 'uploads'
//            ]);
//            $paths[] = $path;
//        }
//    }
//
//    return $paths;
//}

function uploadImages($names, $title)
{
    $paths = [];

    // Ensure $files is always an array
    $files = is_array($names) ? $names : [$names];

    foreach ($files as $file) {
        if ($file instanceof \Illuminate\Http\UploadedFile) { // Check if it's an UploadedFile
            $path = $file->store($title, [
                'disk' => 'uploads'
            ]);
            $paths[] = $path;
        } elseif (is_string($file)) {
            // If it's a string, assume it's already a file path, you might want to handle this case
            $paths[] = $file; // or implement logic to copy/move the file if necessary
        } else {
            // Handle unexpected types if needed
            throw new InvalidArgumentException('Invalid file type provided.');
        }
    }

    return $paths;
}

function delete_image($path)
{
    if ($path != "uploads/default.jpg") {
        File::delete(getImageLink($path));
    }
}
function deleteFile($filePath, $disk = 'public')
{
    Storage::disk($disk)->delete($filePath);
}
function deleteMediaFile(?string $filePath, string $disk = 'public'): void
{
    if ($filePath && Storage::disk($disk)->exists($filePath)) {
        Storage::disk($disk)->delete($filePath);
    }
}
/**
 * Checks if the given string is likely a file path.
 */
function isFilePath($value): bool
{
    return is_string($value) && Str::contains($value, ['/', '\\']); // Basic check for file paths
}

function convertStringToUploadedFile($filePath): UploadedFile
{
    // Ensure the file exists
    if (!File::exists($filePath)) {
        throw new Exception("File not found: " . $filePath);
    }

    // Get file info (use File facade to handle file data)
    $file = new UploadedFile(
        $filePath,
        File::name($filePath),        // Filename without extension
        File::mimeType($filePath),    // MIME type
        null,
        true                          // Mark the file as test file (optional)
    );

    return $file;
}

function getImageLink($path)
{
    return asset("uploads/$path");
}

function is_base64_string($string)
{
    // Remove whitespaces (if any) and check if it's valid base64
    $string = trim($string);

    // Check for a valid base64 string, with or without the `data:image/...` prefix
    if (preg_match('/^data:image\/(\w+);base64,/', $string)) {
        return true;  // This matches the format with `data:image/...`
    }

    // Check if it's a valid base64 string without the `data:image/...` prefix
    return base64_decode($string, true) !== false && preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $string);
}
function isBase64($string): bool
{
    return preg_match('/^data:(image\/(jpeg|png|gif|webp|svg\+xml)|video\/(mp4|webm|ogg)|audio\/(mpeg|wav|ogg)|application\/(pdf|msword|vnd\.openxmlformats-officedocument\.wordprocessingml\.document));base64,/', $string);
}
function convertBase64ToUploadedFile($base64Str): UploadedFile
{
    // Define common MIME types and their extensions
    $mimeTypes = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        'video/mp4' => 'mp4',
        'video/webm' => 'webm',
        'video/ogg' => 'ogv',
        'application/pdf' => 'pdf',
        'text/plain' => 'txt',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
    ];

    // Check if the base64 string has a data URI prefix
    if (preg_match('/^data:([\w\/+.-]+);base64,/', $base64Str, $match)) {
        // Get the MIME type from the prefix
        $mimeType = $match[1];
        // Remove the base64 header
        $base64Str = preg_replace('/^data:[\w\/+.-]+;base64,/', '', $base64Str);
    } else {
        // Default to binary file if no MIME type is specified
        $mimeType = 'application/octet-stream';
    }

    // Decode the base64 string
    $decodedData = base64_decode($base64Str);

    // Handle potential decoding issues
    if ($decodedData === false) {
        throw new \Exception("Invalid base64 string");
    }

    // Determine the file extension
    $extension = $mimeTypes[$mimeType] ?? 'bin'; // Default to 'bin' for unknown types

    // Create a temporary file in the system
    $tempFilePath = sys_get_temp_dir() . '/' . Str::random(10) . '.' . $extension;

    // Save the decoded data to the temporary file
    file_put_contents($tempFilePath, $decodedData);

    // Convert the temporary file to an UploadedFile instance
    return new UploadedFile(
        $tempFilePath,
        Str::random(10) . '.' . $extension,  // Random file name with extension
        $mimeType,                          // MIME type
        null,                              // Error (null as we're creating it)
        true                               // Mark as test file
    );
}

function deleteImageFromDisk($disk, $old_image)
{

    if (is_array($old_image)) {

        foreach ($old_image as $image) {
            Storage::disk($disk)->delete($image);
        }
    } else {

        Storage::disk($disk)->delete($old_image);
    }
}

function upload_file_base64($image, $folder) // image-pdf-docx-excel
{

    $file = str_replace(' ', '+', $image);

    $file_extension = 'bin';
    $base64_string = $file;

    if (preg_match('/^data:image\/(\w+);base64,/', $file, $type)) {
        $file_extension = strtolower($type[1]);
        $base64_string = substr($file, strpos($file, ',') + 1);
    } elseif (preg_match('/^data:application\/pdf;base64,/', $file, $type)) {
        $file_extension = 'pdf';
        $base64_string = substr($file, strpos($file, ',') + 1);
    } elseif (preg_match('/^data:application\/vnd\.openxmlformats-officedocument\.wordprocessingml\.document;base64,/', $file, $type)) {
        $file_extension = 'docx';
        $base64_string = substr($file, strpos($file, ',') + 1);
    } elseif (preg_match('/^data:application\/vnd\.ms-excel;base64,/', $file, $type)) {
        $file_extension = 'xls';
        $base64_string = substr($file, strpos($file, ',') + 1);
    } elseif (preg_match('/^data:application\/(\w+);base64,/', $file, $type)) {
        $file_extension = strtolower($type[1]);
        $base64_string = substr($file, strpos($file, ',') + 1);
    }

    $fileName = Str::random(10) . '_' . time() . '.' . $file_extension;



    $upload_path = $folder;
    $file_url = $upload_path . '/' . $fileName;

    if (!file_exists($upload_path)) {
        mkdir(public_path($upload_path), 0775, true);
    }

    file_put_contents(public_path($file_url), base64_decode($base64_string));


    return $file_url;
}
function imageHandle($request, string $name, ?Model $model = null, string $folder = null): ?string
{
    if (array_key_exists($name, $request)) {
        if ($request[$name] !== null) {
            if ($model && $model[$name]) {
                deleteImage($model[$name]);
            }
            return uploadImage($request, $name);
        }
    }

    return optional($model)->$name;
}
function uploadImageV2($request, string $name, string $folder)
{
    if (array_key_exists($name, $request)) {
        if (!$request[$name]) return;
        $file = $request[$name];
        $path = 'uploads/' . $file->store($folder, [
            'disk' => 'uploads'
        ]);
    }

    return $path ?? null;
}
function fileHandle($request, string $name, ?Model $model = null, string $folder)
{
    if (array_key_exists($name, $request)) {
        if ($request[$name] !== null) {
            if ($model && $model[$name]) {
                deleteFile($model[$name]);
            }
            return uploadFile($request, $name, $folder);
        }
    }

    return optional($model)->$name;
}
function uploadFile($name, string $folder)
{

    $pdf = $name;
    $pdfName = time() . rand(1, 9999) . '.' . $pdf->getClientOriginalExtension();
    $path = $pdf->storeAs($folder, $pdfName, 'files');


    return 'files/' . $path ?? null;
}
function deleteImage($path)
{
    if ($path != "uploads/default.jpg") {
        // dd(public_path($path));
        File::delete(public_path($path));
    }
    // dd($path);
}



function isPDF($file): bool
{
    return $file->getMimeType() === 'application/pdf';
}

function isImage(UploadedFile $file): bool
{
    return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']);
}
function hasImage($row, string $image): ?string
{
    return isset($row[$image]) ? $row[$image] : null;
}
function hasFile($row, string $file): ?string
{
    return isset($row[$file]) ? $row[$file] : null;
}
