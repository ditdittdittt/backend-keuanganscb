<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\FormSubmission;
use Exception;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    private $modelName = 'Form Submission';
    //
    public function index()
    {
        try {
            $form_submission = FormSubmission::all();
            return ReturnGoodWay::successReturn(
                $form_submission,
                $this->modelName,
                "List of all " . $this->modelName,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function store(Request $request)
    {
        try {
            $form_submission = new FormSubmission();
            $form_submission['user_id'] = $request->input('user_id');
            $form_submission['date'] = $request->input('date');
            $form_submission['used'] = $request->input('used');
            $form_submission['balanced'] = $request->input('balanced');
            $form_submission['allocation'] = $request->input('allocation');
            $form_submission['notes'] = $request->input('notes');
            $form_submission['is_confirmed_pic'] = false;
            $form_submission['is_confirmed_verificator'] = false;
            $form_submission['is_confirmed_head_dept'] = false;
            $form_submission['is_confirmed_head_office'] = false;
            $form_submission->save();
            return ReturnGoodWay::successReturn(
                $form_submission,
                $this->modelName,
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function update(Request $request)
    {
        try {
            $form_submission = FormSubmission::find($request['form_submission_id']);
            $form_submission->user_id = $request['user_id'];
            $form_submission->date = $request['date'];
            $form_submission->used = $request['used'];
            $form_submission->balanced = $request['balanced'];
            $form_submission->allocation = $request['allocation'];
            $form_submission->notes = $request['notes'];
            $form_submission->save();
            return ReturnGoodWay::successReturn(
                $form_submission,
                $this->modelName,
                $this->modelName . " with id " . $form_submission->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function delete(Request $request)
    {
        try {
            $form_submission = FormSubmission::find($request['form_submission_id']);
            $form_submission->delete();
            return ReturnGoodWay::successReturn(
                $form_submission,
                $this->modelName,
                $this->modelName . " with id " . $form_submission->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
