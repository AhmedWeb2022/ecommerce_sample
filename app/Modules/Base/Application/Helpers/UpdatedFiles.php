<?php


use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

function deleteFileIfExists(?string $filePath, string $disk = 'public'): void
{
    // Define a list of default files to skip
    $defaultFiles = [
        'uploads/default.jpg',
        'uploads/default.mp4', // Add more defaults as needed
    ];

    // If filePath is null, empty, or a default file, return early
    if (!$filePath || in_array($filePath, $defaultFiles, true)) {
        return;
    }
    // Determine if the file is stored in public path or Laravel storage disk
    if (str_starts_with($filePath, 'uploads/')) {
        Log::info(['filePath' => $filePath]);
        $fullPath = public_path($filePath);
        if (File::exists($fullPath)) {
            Log::info(['fullPath' => $fullPath]);
            File::delete($fullPath);
        } elseif (Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
            Log::info(['storagepath' => $filePath]);
        }
    }
}

// Example Usage:
// Deleting a file from DTO object
function deleteFilesFromDto($dto, string $disk = 'public'): void
{
    if (!is_object($dto)) {
        return;
    }
    // dd($dto);
    foreach (get_object_vars($dto) as $value) {
        if(is_string($value)){

            deleteFileIfExists($value, $disk);
        }
    }
}

// Deleting old files before updating a record
function deleteOldFiles($dto, $record, string $disk = 'public'): void
{
    foreach ($dto as $key => $value) {
        if (!is_null($value) && is_string($value)) {
            Log::info(['recodrd_key' => $record->$key, 'key' => $key, 'value' => $value]);

            deleteFileIfExists($record->$key ?? '', $disk);
        }
    }
    // dd('out');
}

function deleteRecordFiles($record, string $disk = 'public'): void
{
    if (!$record) {
        return;
    }

    // Convert the record to an array and iterate through all fields
    foreach ((array) $record as $field => $value) {
        // If the field is an array (e.g., ff or other nested structure), process it recursively
        if (is_array($value)) {
            foreach ($value as $subField => $subValue) {
                deleteFileIfExists($subValue, $disk);
            }
        } else {
            deleteFileIfExists($value, $disk);
        }
    }
}
