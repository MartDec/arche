<?php


namespace App\Upload;


use Slim\Psr7\UploadedFile;

abstract class Uploader
{
    const ABS_PUBLIC_PATH = __DIR__ . '/../../public/files/';

    protected ?string $generatedFileName = null;

    public function __construct(
        protected UploadedFile $uploadedFile
    ) {}

    public function getGeneratedFileName(): string
    {
        if (is_null($this->generatedFileName))
            $this->generateFileName($this->getFileExtension());

        return $this->generatedFileName;
    }

    public function getFilePath(): string
    {
        return 'files/' . static::FILE_TYPE . "/{$this->getGeneratedFileName()}";
    }

    public function getFileExtension(): string
    {
        $clientFileName = $this->uploadedFile->getClientFilename();
        return pathinfo($clientFileName, PATHINFO_EXTENSION);
    }

    public function upload(): static
    {
        if ($this->uploadedFile->getError() === UPLOAD_ERR_OK) {
            $this->uploadedFile->moveTo($this->getSavePath(static::FILE_TYPE));
            return $this;
        }else
            throw $this->getUploadException($this->uploadedFile->getError());
    }

    protected function getSavePath(): string
    {
        return self::ABS_PUBLIC_PATH . static::FILE_TYPE . "/{$this->getGeneratedFileName()}";
    }

    protected function generateFileName($extension): void
    {
        $fileName = bin2hex(random_bytes(8));
        $this->generatedFileName = "{$fileName}.{$extension}";
    }

    protected function getUploadException(int $errorCode): \Exception
    {
        $error = match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Wrong file extension was uploaded'
        };

        return new \Exception($error);
    }
}
