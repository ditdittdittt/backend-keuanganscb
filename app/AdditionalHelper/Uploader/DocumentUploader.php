<?php

namespace App\AdditionalHelper\Uploader;

use Carbon\Carbon;
use UploaderInterface;

class DocumentUploader implements UploaderInterface
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
        $fileName = uniqid();
        $path = $this->file->storeAs('documents' . '/' . $this->docType . '/' . strval(Carbon::now()->year) . '/' . strval(Carbon::now()->month), $fileName . '.' . $this->extension, 'custom');
        return $path;
    }
}
