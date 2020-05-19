<?php

namespace App\Observers;

use App\FormRequest;
use App\FormSubmission;
use App\FormSubmissionDetail;
use App\FormSubmissionUsers;
use App\Services\BudgetCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormSubmissionObserver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the form submission "creating" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function creating(FormSubmission $formSubmission)
    {
        // Form Number
        $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $year = Carbon::now()->year;
        $count = FormSubmission::whereDate('created_at', Carbon::now()->toDate())->count() + 1;
        $count = str_pad($count, 2, '0', STR_PAD_LEFT);
        $code = "INV.FS";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formSubmission->invoice_number = $number;
    }

    /**
     * Handle the form submission "created" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function created(FormSubmission $formSubmission)
    {
        // Store Details
        foreach ($this->request->details as $detail) {
            $formSubmissionDetail = new FormSubmissionDetail();
            $formSubmissionDetail->form_submission_id = $formSubmission->id;
            $formSubmissionDetail->budget_code_id = $detail['budget_code_id'];
            $formSubmissionDetail->used = $detail['used'];
            $formSubmissionDetail->balance = $detail['balance'];
            $formSubmissionDetail->save();
        }

        // Update status form request
        $formRequest = FormRequest::find($formSubmission->form_request_id);
        $formRequest->status_id = 5;
        $formRequest->save();

        // Users pivot
        $pivot = new FormSubmissionUsers();
        $pivot->user_id = auth()->user()->id;
        $pivot->role_name = 'pic';
        $pivot->form_submission_id = $formSubmission->id;
        if ($this->request->hasFile('signature')) {
            $uploadHelper = new UploadHelper(
                $this->request->file('signature'),
                "signatures"
            );
            $filePath = $uploadHelper->insertAttachment();
            $pivot->attachment = $filePath;
        }
        $pivot->save();
    }

    /**
     * Handle the form submission "updated" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function updated(FormSubmission $formSubmission)
    {
        if (
            $formSubmission->is_confirmed_verificator && $formSubmission->is_confirmed_head_dept &&
            $formSubmission->is_confirmed_pic &&
            $formSubmission->is_confirmed_head_office &&
            ($formSubmission->status_id == 1)
        ) {
            $formSubmission->status_id = 2;
            $formSubmission->saveWithoutEvents();
        }

        if ($formSubmission->isDirty('is_confirmed_cashier')) {

            // Update number
            $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
            $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
            $year = Carbon::now()->year;
            $count = FormSubmission::where(function ($query) {
                $query->where('is_confirmed_cashier', 1);
                $query->whereDate('date', Carbon::now()->toDate());
            })->count() + 1;
            $count = str_pad($count, 2, '0', STR_PAD_LEFT);
            $code = "FS";
            $number = $code . "." . $count . "." . $day . $month . $year;
            $formSubmission->number = $number;

            // Increase or decrease budget code balance
            foreach ($formSubmission->details as $detail) {
                $budgetCode = $detail->budgetCode;
                $budgetCode->balance = $budgetCode->balance + $detail->balance;
                $budgetCode->save();
                if ($detail->balance > 0) {
                    $logType = 'debit';
                } else if ($detail->balance < 0) {
                    $logType = 'kredit';
                }
                $budgetCodeService = new BudgetCodeService($budgetCode);
                $budgetCodeService->createLog($number, $logType, $detail->balance, $formSubmission->pic()->first()->id);
            }

            // Update status to "Selesai"
            $formSubmission->status_id = 6;

            // Update date to now
            $formSubmission->date = Carbon::now()->toDateString();

            $formSubmission->saveWithoutEvents();
        }
    }

    /**
     * Handle the form submission "deleted" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function deleted(FormSubmission $formSubmission)
    {
        //
    }

    /**
     * Handle the form submission "restored" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function restored(FormSubmission $formSubmission)
    {
        //
    }

    /**
     * Handle the form submission "force deleted" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function forceDeleted(FormSubmission $formSubmission)
    {
        //
    }
}
