<?php


namespace App\AdditionalHelper;


use Carbon\Carbon;

class UploadHelper
{
    static public function insertAttachment($request, string $type, string $name){
        $guessExtension = $request->file('file')->getOriginalClientExtension();

        $attachment = $request->file('file')->storeAs($type . '/' . strval(Carbon::now()->year) . '/' . strval(Carbon::now()->month), $name . '.' . $guessExtension, 'custom');
        $attachment = env('APP_URL') . 'uploads/' . $attachment;

        return $attachment;
    }
}
