<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'thumbnail',
        'creator_id'
    ];

    public function albums()
    {
        return $this->hasMany(Album::class, 'artist_id', 'id');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'artist_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
