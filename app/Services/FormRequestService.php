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

        $data = $request->signature;
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        // Uploader
        $uploadHelper = new UploadHelper(
            $data,
            'signatures'
        );
        $filePath = $uploadHelper->insertAttachment();
        $pivot->attachment = $filePath;
        $pivot->save();
    }
}
