<?php

namespace App\Services;

use App\AdditionalHelper\UploadHelper;
use App\FormPettyCash;
use App\FormPettyCashUsers;

class FormPettyCashService
{
    protected $formPettyCash;

    public function __construct(FormPettyCash $formPettyCash)
    {
        $this->formPettyCash = $formPettyCash;
    }

    // Form Petty Cash users pivot
    public function createFormPettyCashUsers($request, string $roleName, $user)
    {
        $pivot = new FormPettyCashUsers();
        $pivot->user_id = $user->id;
        $pivot->role_name = $roleName;
        $pivot->form_petty_cash_id = $this->formPettyCash->id;

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
