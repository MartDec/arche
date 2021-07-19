<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title',
        'thumbnail',
        'artist_id',
        'creator_id'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'album_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
