<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Owenoj\LaravelGetId3\GetId3;
use Slim\Psr7\UploadedFile;

class Song extends Model
{
    protected $fillable = [
        'title',
        'file',
        'duration',
        'artist_id',
        'album_id',
        'creator_id'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
