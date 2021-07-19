<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Router\Route;
use App\Http\Middlewares\LoggedUserMiddleware;
use App\Upload\SongUploader;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\UploadedFile;

#[Route('/songs')]
class SongController extends AbstractController
{
    #[Route('')]
    public function getAll(): Response
    {
        if ($albumId = intval($this->getParam('album')))
            $songs = Song::where('album_id', $albumId)->get();
        elseif ($artistId = intval($this->getParam('artist')))
            $songs = Song::where('artist_id', $artistId)->get();
        else
            $songs = Song::all();

        foreach ($songs as $song) {
            $song->artist = $song->artist()->get()->first();
            $song->album = $song->album()->get()->first();
            $song->creator = $song->creator()->get()->first();
        }

        return $this->json([
            'error' => false,
            'songs' => $songs
        ]);
    }

    #[Route('/{id:[0-9]+}')]
    public function getOne(): Response
    {
        $songId = $this->getArg('id');
        $song = Song::find($songId);

        if (!is_null($song)) {
            $song->artist = $song->artist()->get()->first();
            $song->album = $song->album()->get()->first();
            $song->creator = $song->creator()->get()->first();

            return $this->json([
                'error' => false,
                'song' => $song
            ]);
        }

        return $this->error('Song not found', 404);
    }

    #[Route('', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function create(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['title']) && isset($body['artist_id']) && isset($body['album_id']) &&
            $this->getUploadedFile(self::UPLOAD_TYPE_SONG)?->getClientFilename() !== '') {

            try {
                $file = $this->getUploadedFile(self::UPLOAD_TYPE_SONG);
                $uploader = (new SongUploader($file))->upload();
                $song = new Song();
                $song->file = $uploader->getFilePath();
                $song->duration = $uploader->getSongDuration();
                $song->creator_id = $this->currentUser()->id;
                $song->fill($body);

                if ($song->save()) {
                    return $this->json([
                        'error' => false,
                        'song' => $song
                    ]);
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), 500);
            }

            return $this->error('An error occured while creating song', 500);
        }

        return $this->error('Fields missing', 400);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function update(): Response
    {
        $songId = $this->getArg('id');
        $body = $this->getRequest()->getParsedBody();
        $song = Song::find($songId);

        if (!is_null($song)) {
            $song->title = $body['title'];
            if ($song->save()) {
                return $this->json([
                    'error' => false,
                    'song' => $song->getAttributes()
                ]);
            }

            return $this->error('An error occured while trying to save song', 500);
        }

        return $this->error('Song not found', 404);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_DELETE, LoggedUserMiddleware::class)]
    public function delete(): Response
    {
        $songId = $this->getArg('id');
        $song = Song::find($songId);

        if (!is_null($song)) {
            if ($song->delete()) {
                unlink(__DIR__ . "/../../../public/{$song->file}");
                return $this->json([
                    'error' => false,
                    'message' => "Song {$song->name} successfully deleted"
                ]);
            }

            return $this->error('An error occured while trying to delete song', 500);
        }

        return $this->error('Song not found', 404);
    }
}
