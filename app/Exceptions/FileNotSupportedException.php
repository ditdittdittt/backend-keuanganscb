<?php

namespace App\Exceptions;

use App\AdditionalHelper\ReturnGoodWay;
use Exception;

class FileNotSupportedException extends Exception
{
    protected $message = "";

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return ReturnGoodWay::failedReturn($this->message, 'not found');
    }
}
