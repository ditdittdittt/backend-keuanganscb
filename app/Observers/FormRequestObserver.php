<?php

namespace App\Observers;

use App\AdditionalHelper\UploadHelper;
use App\FormRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormRequestObserver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /** 
     * Handle the form request "creating" event.
     * 
     * @param \App\FormRequest $formRequest
     * @return void
     */
    public function creating(FormRequest $formRequest)
    {
        // User Id based on who's login
        // $formRequest->user_id = auth()->user()->id;

        // Attachment file
        if ($this->request->hasFile('attachment')) {
            $uploadHelper = new UploadHelper(
                $this->modelName,
                $this->request->file('attachment'),
                uniqid(),
                'proposal'
            );
            $filePath = $uploadHelper->insertAttachment();
            $formRequest->attachment = $filePath;
        }

        // Transfer method needs detail
        if ($formRequest->method == "Transfer") {
            $formRequest->bank_name = $this->request->bank_name;
            $formRequest->bank_code = $this->request->bank_code;
            $formRequest->account_number = $this->request->account_number;
            $formRequest->account_owner = $this->request->account_owner;
        }

        // Form Number
        $day = Carbon::now()->day;
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $year = Carbon::now()->year;
        $count = FormRequest::whereDate('created_at', Carbon::now()->toDate())->count() + 1;
        $count = str_pad($count, 2, '0', STR_PAD_LEFT);
        $code = "UM";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formRequest->number = $number;
    }

    /**
     * Handle the form request "created" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function created(FormRequest $formRequest)
    {
    }

    /**
     * Handle the form request "updating" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function updating(FormRequest $formRequest)
    {
        if ($this->request->file('attachment') != null) {
            $uploadHelper = new UploadHelper($this->modelName, $this->request->file('attachment'), uniqid(), 'proposal');
            $filePath = $uploadHelper->insertAttachment();
            $formRequest->attachment = $filePath;
        } else {
            $formRequest->attachment = null;
        }
        if ($this->request->method == "Transfer") {
            $formRequest->bank_name = $this->request->bank_name;
            $formRequest->bank_code = $this->request->bank_code;
            $formRequest->account_number = $this->request->account_number;
            $formRequest->account_owner = $this->request->account_owner;
        } else if ($this->request->method == "Cash") {
            $formRequest->bank_name = null;
            $formRequest->bank_code = null;
            $formRequest->account_number = null;
            $formRequest->account_owner = null;
        }
    }

    /**
     * Handle the form request "updated" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function updated(FormRequest $formRequest)
    {
        if (
            $formRequest->is_confirmed_verificator && $formRequest->is_confirmed_head_dept &&
            $formRequest->is_confirmed_pic &&
            $formRequest->is_confirmed_cashier &&
            ($formRequest->status_id == 1)
        ) {
            $formRequest->status_id = 2;
            $formRequest->save();
        }
    }

    /**
     * Handle the form request "deleted" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function deleted(FormRequest $formRequest)
    {
        //
    }

    /**
     * Handle the form request "restored" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function restored(FormRequest $formRequest)
    {
        //
    }

    /**
     * Handle the form request "force deleted" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function forceDeleted(FormRequest $formRequest)
    {
        //
    }
}
