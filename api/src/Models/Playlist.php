<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'title',
        'thumbnail',
        'creator_id'
    ];

    public function songs()
    {
        return $this->belongsToMany(
            Song::class,
            'playlist_songs',
            'song_id',
            'playlist_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
