<?php

namespace App\Observers;

use App\AdditionalHelper\UploadHelper;
use App\FormPettyCash;
use App\FormPettyCashDetail;
use App\FormPettyCashUsers;
use App\Services\BudgetCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormPettyCashObserver
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the form petty cash "creating" event.
     * @param \App\FormPettyCash $formPettyCash
     * @return void
     */
    public function creating(FormPettyCash $formPettyCash)
    {
        // Form Number
        $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $year = Carbon::now()->year;
        $count = FormPettyCash::whereDate('created_at', Carbon::now()->toDate())->count() + 1;
        $count = str_pad($count, 2, '0', STR_PAD_LEFT);
        $code = "INV.KK";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formPettyCash->invoice_number = $number;
    }

    /**
     * Handle the form petty cash "created" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function created(FormPettyCash $formPettyCash)
    {
        // Store Details
        foreach ($this->request->details as $detail) {
            $formPettyCashDetail = new FormPettyCashDetail();
            $formPettyCashDetail->form_petty_cash_id = $formPettyCash->id;
            $formPettyCashDetail->budget_code_id = $detail['budget_code_id'];
            $formPettyCashDetail->nominal = $detail['nominal'];
            $formPettyCashDetail->save();
        }

        // User pivot and roles
        $pivot = new FormPettyCashUsers();
        $pivot->user_id = auth()->user()->id;
        $pivot->role_name = 'pic';
        $pivot->form_petty_cash_id = $formPettyCash->id;
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
     * Handle the form petty cash "updated" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function updated(FormPettyCash $formPettyCash)
    {
        if (
            $formPettyCash->is_confirmed_pic && $formPettyCash->is_confirmed_manager_ops &&
            ($formPettyCash->status_id == 1)
        ) {
            $formPettyCash->status_id = 2;
            $formPettyCash->save();
        }

        if (
            $formPettyCash->isDirty('is_confirmed_cashier')
        ) {
            // Update number
            $day = str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT);
            $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
            $year = Carbon::now()->year;
            $count = FormPettyCash::where(function ($query) {
                $query->where('is_confirmed_cashier', 1);
                $query->whereDate('date', Carbon::now()->toDate());
            })->count() + 1;
            $count = str_pad($count, 2, '0', STR_PAD_LEFT);
            $code = "KK";
            $number = $code . "." . $count . "." . $day . $month . $year;
            $formPettyCash->number = $number;

            // Decrease budget code balance
            foreach ($formPettyCash->details as $detail) {
                $budgetCode = $detail->budgetCode;
                $budgetCode->balance = $budgetCode->balance - $detail->nominal;
                $budgetCode->save();
                $budgetCodeService = new BudgetCodeService($budgetCode);
                $budgetCodeService->createLog($number, 'kredit', $detail->nominal, $formPettyCash->pic()->first()->id);
            }

            // Update status to "Terbayarkan"
            $formPettyCash->status_id = 3;

            // Update date to now
            $formPettyCash->date = Carbon::now()->toDateString();

            $formPettyCash->saveWithoutEvents();
        }
    }

    /**
     * Handle the form petty cash "deleted" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function deleted(FormPettyCash $formPettyCash)
    {
        //
    }

    /**
     * Handle the form petty cash "restored" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function restored(FormPettyCash $formPettyCash)
    {
        //
    }

    /**
     * Handle the form petty cash "force deleted" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function forceDeleted(FormPettyCash $formPettyCash)
    {
        //
    }
}
