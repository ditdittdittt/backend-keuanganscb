<?php


namespace App\AdditionalHelper;


use Carbon\Carbon;
use ErrorException;

class UploadHelper
{
    protected $modelName = '';
    protected $file = '';
    protected $fileName = '';
    protected $fileType = '';
    protected $docType = '';

    public function __construct(string $modelName, $file, string $fileName, string $docType)
    {
        $this->modelName = $modelName;
        $this->file = $file;
        $this->fileName = $fileName;
        $this->docType = $docType;
    }

    public function insertAttachment()
    {
        $guessExtension = $this->file->guessExtension();
        switch ($guessExtension) {
            case 'jpeg':
            case 'jpg':
            case 'png':
                $this->fileType = 'images';
                break;

            default:
                throw new FileNotSupportedException("These Files are not supported");
                break;
        }

        $filePath = $this->file->storeAs($this->fileType . '/' . $this->docType . strval(Carbon::now()->year) . '/' . strval(Carbon::now()->month), $this->fileName . '.' . $guessExtension, 'custom');
        $filePath = env('APP_URL') . 'uploads/' . $filePath;

        return $filePath;
    }
}
