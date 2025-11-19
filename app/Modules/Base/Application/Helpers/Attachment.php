<?php

use App\Modules\Attachment\Application\DTOS\Attachment\AttachmentDTO;
use App\Modules\Attachment\Application\UseCases\Attachment\AttachmentUseCase;
use App\Modules\Attachment\Infrastructure\Persistence\Models\Attachment\Attachment;

function uploadAttachment($model, $file, string $folder, $special_type = null)
{
    $filename = $file->getClientOriginalName();
    $file_size = $file->getSize();
    $file_type = $file->getMimeType();
    // dd($file_type);
    if (isImage($file)) {
        $file = uploadImage($file, $folder);
    } else {
        $file = uploadFile($file, $folder);
    }

    $attachmentDTOData = [
        'file_size' => $file_size,
        'file_name' => $filename,
        'file' => $file,
        'alt' => $filename,
        'type' => $file_type,
        'special_type' => $special_type,
        'attachable_type' => get_class($model),
        'attachable_id' => $model->id,
    ];
    $attachmentDTO = new AttachmentDTO(...$attachmentDTOData);
    $attachmentUseCase = new  AttachmentUseCase();
    return $attachmentUseCase->create($attachmentDTO);
}
