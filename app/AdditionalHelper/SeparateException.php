<?php

namespace App\AdditionalHelper;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use PDOException;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Exceptions\UnauthorizedException as RoleUnauthorized;

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
            $err_type = 'not_found';
            $err_msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof UnauthorizedException) {
            $err_type = 'forbidden';
            $err_msg = $modelName . ' ' . $err_type;
        } else if ($err instanceof PDOException) {
            $err_type = 'error_database';
            $err_msg = $err;
        } else if ($err instanceof RoleDoesNotExist) {
            $err_type = 'not_found';
            $err_msg = 'Role ' . $err_type;
        } else if ($err instanceof PermissionDoesNotExist) {
            $err_type = 'not_found';
            $err_msg = 'Permission ' . $err_type;
        } else if ($err instanceof RoleUnauthorized) {
            $err_type = 'forbidden';
            $err_msg = $modelName . ' ' . $err_type . ' to do this';
        } else if ($err instanceof RoleAlreadyExists || $err instanceof PermissionAlreadyExists) {
            $err_type = 'conflict';
            $err_msg = $modelName . ' already exist';
        } else {
            $err_type = 'unknown';
            $err_msg = $err;
        }
        return ReturnGoodWay::failedReturn($err_msg, $err_type);
    }
}
