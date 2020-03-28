<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\FormSubmission;
use App\Http\Requests\ValidateFormSubmission;
use Exception;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    private $modelName = 'Form_Submission';
    //
    public function index()
    {
        try {
            $form_submissions = FormSubmission::all();
            $form_submissions->load('user');
            $form_submissions->load('formRequest');
            return ReturnGoodWay::successReturn(
                $form_submissions,
                $this->modelName,
                "List of all " . $this->modelName,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function store(ValidateFormSubmission $request)
    {
        try {
            $form_submission = new FormSubmission();
            $form_submission->user_id = auth()->user()->id;
            $form_submission->form_request_id = $request->input('form_request_id');
            $form_submission->date = $request->input('date');
            $form_submission->used = $request->input('used');
            $form_submission->balance = $request->input('balance');
            $form_submission->allocation = $request->input('allocation');
            $form_submission->notes = $request->input('notes');
            $form_submission->is_confirmed_pic = false;
            $form_submission->is_confirmed_verificator = false;
            $form_submission->is_confirmed_head_dept = false;
            $form_submission->is_confirmed_head_office = false;
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

    public function show($id, Request $request)
    {
        try {
            $form_submission = FormSubmission::findOrFail($id);
            $form_submission->load('user');
            $form_submission->load('formRequest');
            return ReturnGoodWay::successReturn(
                $form_submission,
                $this->modelName,
                null,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $form_submission = FormSubmission::findOrFail($id);
            $form_submission->user_id = auth()->user()->id;
            if ($request->input('date')) $form_submission->date = $request->input('date');
            if ($request->input('used')) $form_submission->used = $request->input('used');
            if ($request->input('balance')) $form_submission->balance = $request->input('balance');
            if ($request->input('allocation')) $form_submission->allocation = $request->input('allocation');
            if ($request->input('notes')) $form_submission->notes = $request->input('notes');
            if ($request->input('is_confirmed_pic')) {
                $form_submission->is_confirmed_pic = $request->input('is_confirmed_pic');
            }
            if ($request->input('is_confirmed_verificator')) {
                $form_submission->is_confirmed_verificator = $request->input('is_confirmed_verificator');
            }
            if ($request->input('is_confirmed_head_dept')) {
                $form_submission->is_confirmed_head_dept = $request->input('is_confirmed_head_dept');
            }
            if ($request->input('is_confirmed_head_office')) {
                $form_submission->is_confirmed_head_office = $request->input('is_confirmed_head_office');
            }
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

    public function delete($id)
    {
        try {
            $hidden = array(
                'user_id',
                'form_request_id',
                'date',
                'used',
                'balance',
                'allocation',
                'notes',
                'is_confirmed_pic',
                'is_confirmed_verificator',
                'is_confirmed_head_dept',
                'is_confirmed_head_office'
            );
            $form_submission = FormSubmission::findOrFail($id);
            $form_submission->delete();
            return ReturnGoodWay::successReturn(
                $form_submission->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $form_submission->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    //Return count of submission form
    public function countSubmissionForm(){
        $count = FormSubmission::all()->count();
        return response()->json(['jumlah_submission_form' => $count]);
    }
}
