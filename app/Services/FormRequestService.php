<?php

namespace App\Services;

use App\AdditionalHelper\UploadHelper;
use App\FormRequest;
use App\FormRequestUsers;

class FormRequestService
{
    protected $formRequest;

    public function __construct(FormRequest $formRequest)
    {
        $this->formRequest = $formRequest;
    }

    // Form Request users pivot
    public function createFormRequestUsers($request, string $roleName, $user)
    {
        $pivot = new FormRequestUsers();
        $pivot->user_id = $user->id;
        $pivot->role_name = $roleName;
        $pivot->form_request_id = $this->formRequest->id;
        $base64_signature = substr($request->signature, strpos($request->signature, ",")+1);
        $signature = base64_decode($base64_signature);
        // Uploader
        $uploadHelper = new UploadHelper(
            $signature,
            'signatures'
        );
        $filePath = $uploadHelper->insertAttachment();
        $pivot->attachment = $filePath;
        $pivot->save();
    }
}
