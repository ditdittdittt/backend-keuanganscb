<?php


namespace App\AdditionalHelper;

use App\AdditionalHelper\Uploader\Base64Uploader;
use App\AdditionalHelper\Uploader\DocumentUploader;
use App\AdditionalHelper\Uploader\ImageUploader;
use App\Exceptions\FileNotSupportedException;

class UploadHelper
{
    protected $uploader = null;

    public function __construct($file, string $docType)
    {
        if ($docType == 'signatures') {
            $extension = 'signatures';
        } else {
            $extension = $file->guessExtension();
        }
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
            case 'png':
                $this->uploader = new ImageUploader($file, $extension, $docType);
                break;

            case 'pdf':
                $this->uploader = new DocumentUploader($file, $extension, $docType);
                break;

            case 'signatures';
                $this->uploader = new Base64Uploader($file, ".png", "signatures");
                break;

            default:
                throw new FileNotSupportedException("These Files are not supported");
                break;
        }
    }

    public function insertAttachment()
    {
        $filePath = $this->uploader->insert();
        $filePath = env('APP_URL') . '/uploads/' . $filePath;
        return $filePath;
    }
}
