<?php

namespace App\Http\Utils\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * This trait will be used to upload files to the server
 */

trait UploadFileTrait
{

    // Attachment types for consistency
    protected const ATTACHMENT_TYPES = [
        'passport_photo' => 'passport_photo',
        'tin_document' => 'tin',
        'national_id_document' => 'national_id',
        'cv_document' => 'cv',
        'certificate' => 'certificate',
    ];


    /**
     * Summary of uploadFile
     * @param mixed $file
     * @param mixed $request
     * @return array{message: Illuminate\Support\MessageBag, status: string, type: string|array{payload: array{file_path: mixed}, status: string}}
     */
    public static function uploadFile($file, $request)
    {
        // validate the upload file
        $rules = [
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return [
                'status' => 'fail',
                'type' => 'error',
                'message' => $validate->errors()
            ];
        }
        $filePath = $request->file('file')->store('uploads');
        return [
            'status' => 'success',
            'payload' => [
                'file_path' => $filePath
            ]
        ];
    }


    /**
     * Summary of deleteOldAttachment
     * trait to delete any old attachment matching a type.
     * @param mixed $attachable
     * @param string $type
     * @return void
     */
    private function deleteOldAttachment($attachable, string $type)
    {
        $olds = $attachable->attachments()
            ->where('type', $type)
            ->get();
        foreach ($olds as $key => $old) {
            $this->deleteFile($old->path);
            $old->delete();
        }
    }

    public function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }


    /**
     * Summary of handleDocumentUpload
     * @param mixed $file
     * @param string $type
     * @param array $attachments
     * @param string $location
     * @return array{message: string, status: string}
     */
    private function handleDocumentUpload(
        $file,
        string $type,
        array &$attachments,
        string $location = 'attachments/employees',
    ): array|null {
        try {
            $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs(
                $location . '/',
                $filename,
                'public'
            );
            $attachments[] = [
                'filename' => $filename,
                'path' => $path,
                'type' => $type

            ];
            return [
                'status' => 'success',
                'message' => 'File uploaded successfully'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'fail',
                'message' => $th->getMessage()
            ];
        }
    }


     /**
     * Helper to handle passport photo upload.
     */
    private function handlePassportToProfilePhotoUpload(Request $request)
    {
        if (
            $request->hasFile('passport_photo') && $request->file('passport_photo')
                ->isValid()
        ) {
            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();

            $profilePic = $photo->storeAs('profile_photos', $filename, 'public');
            // Merge file name into request (if later used in creating employee record)
            $request->merge(['profile_picture' => $profilePic]);
        }
    }
}
