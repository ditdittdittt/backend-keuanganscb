<?php

namespace App\Observers;

use App\FormPettyCash;
use App\FormPettyCashDetail;
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
        // User ID
        $formPettyCash->user_id = auth()->user()->id;

        // Form Number
        $day = Carbon::now()->day;
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $year = Carbon::now()->year;
        $count = FormPettyCash::whereDate('created_at', Carbon::now()->toDate())->count() + 1;
        $count = str_pad($count, 2, '0', STR_PAD_LEFT);
        $code = "KK";
        $number = $code . "." . $count . "." . $day . $month . $year;
        $formPettyCash->number = $number;
    }

    /**
     * Handle the form petty cash "created" event.
     *
     * @param  \App\FormPettyCash  $formPettyCash
     * @return void
     */
    public function created(FormPettyCash $formPettyCash)
    {
        foreach ($this->request->details as $detail) {
            $formPettyCashDetail = new FormPettyCashDetail();
            $formPettyCashDetail->form_petty_cash_id = $formPettyCash->id;
            $formPettyCashDetail->budget_code_id = $detail['budget_code_id'];
            $formPettyCashDetail->nominal = $detail['nominal'];
            $formPettyCashDetail->save();
        }
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
            $formPettyCash->is_confirmed_pic && $formPettyCash->is_confirmed_manager_ops && $formPettyCash->is_confirmed_cashier &&
            ($formPettyCash->status_id == 1)
        ) {
            $formPettyCash->status_id = 2;
            $formPettyCash->save();
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
