<?php

namespace App\Observers;

use App\FormSubmission;
use App\FormSubmissionUsers;
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
        $code = "FS";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formSubmission->number = $number;
    }

    /**
     * Handle the form submission "created" event.
     *
     * @param  \App\FormSubmission  $formSubmission
     * @return void
     */
    public function created(FormSubmission $formSubmission)
    {
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
            $formSubmission->is_confirmed_cashier &&
            ($formSubmission->status_id == 1)
        ) {
            $formSubmission->status_id = 2;
            $formSubmission->save();
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
