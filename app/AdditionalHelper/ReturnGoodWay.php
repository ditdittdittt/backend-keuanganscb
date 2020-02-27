<?php

namespace App\AdditionalHelper;

class ReturnGoodWay
{
    private static $http_response_code = array(
        "success" => 200,
        "not found" => 404,
        "forbidden" => 403,
        "created" => 201,
        "bad request" => 400,
        "error_database" => 400,
        "unknown" => 500
    );

    public static function successReturn(
        object $data,
        string $modelName,
        string $messages = null,
        string $mode
    ) {
        if (!is_null($messages)) {
            return response()->json([
                'messages' => $messages,
                strtolower($modelName) => $data
            ], self::$http_response_code[$mode]);
        } else {
            return response()->json([
                strtolower($modelName) => $data
            ], self::$http_response_code[$mode]);
        }
    }

    public static function failedReturn(
        string $messages,
        string $mode
    ) {
        return response()->json([
            'failed' => [
                'messages' => $messages,
            ]
        ], self::$http_response_code[$mode]);
    }
}
