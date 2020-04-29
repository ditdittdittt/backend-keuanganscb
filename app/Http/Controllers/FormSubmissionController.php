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
            $formSubmissions = FormSubmission::all();
            $formSubmissions->load('user');
            $formSubmissions->load('formRequest');
            return ReturnGoodWay::successReturn(
                $formSubmissions,
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
            $formSubmission = new FormSubmission();
            $formSubmission->form_request_id = $request->input('form_request_id');
            $formSubmission->date = $request->input('date');
            $formSubmission->used = $request->input('used');
            $formSubmission->balance = $request->input('balance');
            $formSubmission->allocation = $request->input('allocation');
            $formSubmission->notes = $request->input('notes');
            $formSubmission->save();
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function show(FormSubmission $formSubmission, Request $request)
    {
        try {
            $formSubmission->load('user');
            $formSubmission->load('formRequest');
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                null,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function update(FormSubmission $formSubmission, Request $request)
    {
        try {
            $formSubmission->update($request->all());
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                $this->modelName . " with id " . $formSubmission->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function delete(FormSubmission $formSubmission)
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
            $formSubmission->delete();
            return ReturnGoodWay::successReturn(
                $formSubmission->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formSubmission->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    //Return count of submission form
    public function countSubmissionForm()
    {
        $count = FormSubmission::all()->count();
        return response()->json(['form_submission_count' => $count]);
    }
}
