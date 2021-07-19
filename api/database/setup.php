<?php

namespace Database;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database/connection.php';


connect();

if (!Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('username');
        $table->string('password');
        $table->string('token')->unique();
        $table->timestamps();
    });
}

if (!Capsule::schema()->hasTable('artists')) {
    Capsule::schema()->create('artists', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('thumbnail')->unique()->nullable();
        $table->foreignId('creator_id');
        $table->timestamps();

        $table->foreign('creator_id')->references('id')->on('users');
    });
}

if (!Capsule::schema()->hasTable('albums')) {
    Capsule::schema()->create('albums', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('thumbnail')->unique()->nullable();
        $table->foreignId('artist_id');
        $table->foreignId('creator_id');
        $table->timestamps();

        $table->foreign('artist_id')->references('id')->on('artists');
        $table->foreign('creator_id')->references('id')->on('users');
    });
}

if (!Capsule::schema()->hasTable('songs')) {
    Capsule::schema()->create('songs', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('file')->unique()->nullable();
        $table->string('duration');
        $table->foreignId('artist_id');
        $table->foreignId('album_id');
        $table->foreignId('creator_id');
        $table->timestamps();

        $table->foreign('artist_id')->references('id')->on('artists');
        $table->foreign('album_id')->references('id')->on('albums');
        $table->foreign('creator_id')->references('id')->on('users');
    });
}

if (!Capsule::schema()->hasTable('playlists')) {
    Capsule::schema()->create('playlists', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('thumbnail')->unique()->nullable();
        $table->foreignId('creator_id');
        $table->timestamps();

        $table->foreign('creator_id')->references('id')->on('users');
    });
}

if (!Capsule::schema()->hasTable('playlist_songs')) {
    Capsule::schema()->create('playlist_songs', function (Blueprint $table) {
        $table->foreignId('playlist_id');
        $table->foreignId('song_id');

        $table->foreign('playlist_id')->references('id')->on('playlists');
        $table->foreign('song_id')->references('id')->on('songs');
    });
}
