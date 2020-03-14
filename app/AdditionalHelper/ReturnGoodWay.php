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
        "error database" => 400,
        "unknown" => 500,
        "conflict" => 409
    );
    /**
     * Create a response blueprint for success query
     */
    public static function successReturn(
        object $data,
        string $modelName,
        string $messages = null,
        string $mode
    ) {
        if (!is_null($messages)) {
            $messages = str_replace("_", " ", $messages);
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

    /**
     * Create a response blueprint for failed query
     */
    public static function failedReturn(
        string $messages,
        string $mode
    ) {
        return response()->json([
            'error' => [
                'messages' => $messages,
            ]
        ], self::$http_response_code[$mode]);
    }

    /**
     * Multiple object return
     */
    public static function multipleReturn(
        array $data,
        string $messages,
        string $mode
    ) {
        $messages = str_replace("_", " ", $messages);
        if (!is_null($messages)) {
            return response()->json([
                'messages' => $messages,
                'data' => $data
            ], self::$http_response_code[$mode]);
        } else {
            return response()->json([
                'data' => $data
            ], self::$http_response_code[$mode]);
        }
    }
}
