<?php


namespace App\Upload;


use Owenoj\LaravelGetId3\GetId3;

class SongUploader extends Uploader
{
    const FILE_TYPE = 'songs';

    public function getSongDuration(): string
    {
        $fileMetadata = new GetId3($this->getSavePath());
        return $fileMetadata->getPlaytime() ?: '0:0';
    }
}
