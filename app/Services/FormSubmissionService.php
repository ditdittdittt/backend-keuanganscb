<?php

namespace App\Services;

use App\AdditionalHelper\UploadHelper;
use App\FormSubmission;
use App\FormSubmissionUsers;

class FormSubmissionService
{
    protected $formSubmission;

    public function __construct(FormSubmission $formSubmission)
    {
        $this->formSubmission = $formSubmission;
    }

    // Form Submission users pivot
    public function createFormSubmissionUsers($request, string $roleName, $user)
    {
        $pivot = new FormSubmissionUsers();
        $pivot->user_id = $user->id;
        $pivot->role_name = $roleName;
        $pivot->form_submission_id = $this->formSubmission->id;

        // Uploader
        $uploadHelper = new UploadHelper(
            $request->signature,
            'signatures'
        );
        $filePath = $uploadHelper->insertAttachment();
        $pivot->attachment = $filePath;
        $pivot->save();
    }
}
