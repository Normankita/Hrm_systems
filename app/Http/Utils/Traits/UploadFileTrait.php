<?php

use Illuminate\Support\Facades\Validator;

/**
 * This trait will be used to upload files to the server
 */

 trait UploadFileTrait
 {
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
 }
