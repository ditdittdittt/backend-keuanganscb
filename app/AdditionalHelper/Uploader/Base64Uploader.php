<?php

namespace App\AdditionalHelper\Uploader;

use Carbon\Carbon;
use App\AdditionalHelper\Uploader\UploaderInterface;
use Illuminate\Support\Facades\Storage;

class Base64Uploader implements UploaderInterface
{
    protected $file;
    protected $extension;
    protected $docType;

    public function __construct($file, $extension, $docType)
    {
        $this->file = $file;
        $this->extension = $extension;
        $this->docType = $docType;
    }

    public function insert()
    {
        $this->file = str_replace("data:image/png;base64,", "", $this->file);
        $this->file = str_replace(" ", "+", $this->file);
        $fileName = uniqid() . $this->extension;
        $path = "images/" . $this->docType . '/' . strval(Carbon::now()->year) . '/' . strval(Carbon::now()->month) . '/' . $fileName;
        Storage::disk('custom')->put($path, base64_decode($this->file));
        return $path;
    }
}
