<?php

namespace App\AdditionalHelper;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use PDOException;

class SeparateException
{
    protected $err_type = '';

    public function __construct(Exception $err)
    {
        $this->err_type = $err;
    }

    public function checkException(string $modelName)
    {
        $err = $this->err_type;
        if ($err instanceof ModelNotFoundException) {
            $err_type = 'not found';
            $err_msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof UnauthorizedException) {
            $err_type = 'forbidden';
            $err_msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof PDOException) {
            $err_type = 'error_database';
            $err_msg = $err;
        } else {
            $err_type = 'unknown';
            $err_msg = 'unknown error';
        }
        return ReturnGoodWay::failedReturn($err_msg, $err_type);
    }
}
