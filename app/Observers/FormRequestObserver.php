<?php

namespace App\Observers;

use App\AdditionalHelper\UploadHelper;
use App\BudgetCode;
use App\FormRequest;
use App\FormRequestDetail;
use App\FormRequestUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\FormCreated;
use App\Services\BudgetCodeService;

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

        // Attachment file
        if ($this->request->hasFile('attachment')) {
            $uploadHelper = new UploadHelper(
                $this->request->file('attachment'),
                'proposal'
            );
            $filePath = $uploadHelper->insertAttachment();
            $formRequest->attachment = $filePath;
        }

        // Transfer method needs detail
        if ($formRequest->method == "transfer") {
            $formRequest->bank_name = $this->request->bank_name;
            $formRequest->bank_code = $this->request->bank_code;
            $formRequest->account_number = $this->request->account_number;
            $formRequest->account_owner = $this->request->account_owner;
        }

        // Form Number
        $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $year = Carbon::now()->year;
        $count = FormRequest::whereDate('created_at', Carbon::now()->toDate())->count() + 1;
        $count = str_pad($count, 2, '0', STR_PAD_LEFT);
        $code = "INV.UM";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formRequest->invoice_number = $number;
    }

    /**
     * Handle the form request "created" event.
     *
     * @param  \App\FormRequest  $formRequest
     * @return void
     */
    public function created(FormRequest $formRequest)
    {
        // Store Details
        foreach ($this->request->details as $detail) {
            $formRequestDetail = new FormRequestDetail();
            $formRequestDetail->form_request_id = $formRequest->id;
            $formRequestDetail->budget_code_id = $detail['budget_code_id'];
            $formRequestDetail->nominal = $detail['nominal'];
            $formRequestDetail->save();
        }

        // User pivot and roles
        $pivot = new FormRequestUsers();
        $pivot->user_id = auth()->user()->id;
        $pivot->role_name = 'pic';
        $pivot->form_request_id = $formRequest->id;
        if ($this->request->hasFile('signature')) {
            $uploadHelper = new UploadHelper(
                $this->request->file('signature'),
                "signatures"
            );
            $filePath = $uploadHelper->insertAttachment();
            $pivot->attachment = $filePath;
        }
        $pivot->save();

        // Notify Telegram
        // $formRequest->notify(new FormCreated($formRequest));
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
            $uploadHelper = new UploadHelper(
                $this->request->file('attachment'),
                'proposal'
            );
            $filePath = $uploadHelper->insertAttachment();
            $formRequest->attachment = $filePath;
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
            $formRequest->is_confirmed_head_office &&
            ($formRequest->status_id == 1)
        ) {
            $formRequest->status_id = 2;
            $formRequest->saveWithoutEvents();
        }

        if ($formRequest->isDirty('is_confirmed_cashier')) {
            // Update tanggal
            $formRequest->date = Carbon::now()->toDateString();

            /**
             * Update form number every cashier confirmed
             */
            $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
            $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
            $year = Carbon::now()->year;
            // Count form request which confirmed by cashier in that day
            $count = FormRequest::where(function ($query) {
                $query->where('is_confirmed_cashier', 1);
                $query->whereDate('date', Carbon::now()->toDate());
            })->count() + 1;
            $count = str_pad($count, 2, '0', STR_PAD_LEFT);
            $code = "UM";
            $number = $code . "." . $count . "." . $day . $month . $year;
            $formRequest->number = $number;

            // Reduce budget code balance
            foreach ($formRequest->details as $detail) {
                $budgetCode = $detail->budgetCode;
                $budgetCode->balance = $budgetCode->balance - $detail->nominal;
                $budgetCode->save();
                $budgetCodeService = new BudgetCodeService($budgetCode);
                $budgetCodeService->createLog($number, "kredit", $detail->nominal, $formRequest->pic()->first()->id);
            }

            /**
             * After cashier confirm, update status to 3 ( Terbayarkan )
             */
            $formRequest->status_id = 3;

            // Save without event triggered
            $formRequest->saveWithoutEvents();
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
