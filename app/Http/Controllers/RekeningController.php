<?php

namespace App\Http\Controllers;

use App\Http\Requests\RekeningRequest;
use App\AdditionalHelper\ReturnGoodWay;
use App\Rekening;
use Exception;
use App\AdditionalHelper\SeparateException;

class RekeningController extends Controller
{
    protected $modelName = 'Rekening';

    public function index()
    {
        $rekenings = Rekening::all();
        return ReturnGoodWay::successReturn(
            $rekenings,
            $this->modelName,
            "List of all " . $this->modelName,
            'success'
        );
    }

    public function store(RekeningRequest $request)
    {
        try {
            $rekening = new Rekening();
            $rekening->bank_code = $request->bank_code;
            $rekening->bank_name = $request->bank_name;
            $rekening->account_number = $request->account_number;
            $rekening->account_owner = $request->account_owner;
            $rekening->save();
            return ReturnGoodWay::successReturn(
                $rekening,
                $this->modelName,
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function destroy(Rekening $rekening)
    {
        try {
            $rekening->delete();
            return ReturnGoodWay::successReturn(
                $rekening,
                $this->modelName,
                $this->modelName . " with id " . $rekening->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
