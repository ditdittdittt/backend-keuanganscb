<?php

namespace App\AdditionalHelper;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use PDOException;

class SeparateException
{
    public static function checkException(Exception $err, string $modelName)
    {
        if ($err instanceof ModelNotFoundException) {
            $err_type = 'not found';
            $msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof UnauthorizedException) {
            $err_type = 'forbidden';
            $msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof PDOException) {
            $err_type = 'error_database';
            $msg = $err;
        } else {
            $err_type = 'unknown';
            $msg = 'unknown error';
        }
        return ReturnGoodWay::failedReturn($msg, $err_type);
    }
}
