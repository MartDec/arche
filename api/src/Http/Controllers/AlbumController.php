<?php

namespace App\Http\Controllers;

use App\Http\Middlewares\LoggedUserMiddleware;
use App\Models\Album;
use App\Models\Song;
use App\Router\Route;
use App\Upload\ThumbnailUploader;
use Psr\Http\Message\ResponseInterface as Response;

#[Route('/albums')]
class AlbumController extends AbstractController
{
    #[Route('')]
    public function getAll(): Response
    {
        if (intval($this->getParam('latest')) === 1)
            $albums = Album::query()
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        elseif ($artistId = $this->getParam('artist'))
            $albums = Album::where('artist_id', $artistId)->get();
        else
            $albums = Album::all();

        foreach ($albums as $album) {
            $album->creator = $album->creator()->get()->first();
            $album->artist = $album->artist()->get()->first();
            $album->songs = $album->songs()->get();
        }

        return !empty($albums->toArray()) ?
            $this->json([
                'error' => false,
                'albums' => $albums
            ]) :
            $this->error('Albums not found', 404);
    }

    #[Route('/{id:[0-9]+}')]
    public function getOne(): Response
    {
        $albumId = $this->getArg('id');
        $album = Album::find($albumId);

        if (!is_null($album)) {
            $album->artist = $album->artist()->get()->first();
            $album->songs = $album->songs()->get();
            $album->creator = $album->creator()->get()->first();

            return $this->json([
                'error' => false,
                'album' => $album->getAttributes()
            ]);
        }

        return $this->error('Album not found', 404);
    }

    #[Route('', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function create(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['title']) && isset($body['artist_id'])) {
            $file = $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL);
            $uploader = (new ThumbnailUploader($file))->upload();
            $body['thumbnail'] = $uploader->getFilePath();
            $body['creator_id'] = $this->currentUser()->id;
            $album = new Album($body);
            if ($album->save()) {
                return $this->json([
                    'error' => false,
                    'album' => $album
                ]);
            }

            return $this->error('An error occured while creating album', 500);
        }

        return $this->error('Fields missing', 400);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function update(): Response
    {
        $albumId = $this->getArg('id');
        $body = $this->getRequest()->getParsedBody();
        $album = Album::find($albumId);

        if (!is_null($album)) {
            if (!is_null($this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL)) &&
                $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL)->getClientFilename() !== '') {

                $file = $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL);
                $uploader = (new ThumbnailUploader($file))->upload();
                $body['thumbnail'] = $uploader->getFilePath();
            }

            $album->fill($body);
            if ($album->save()) {
                return $this->json([
                    'error' => false,
                    'album' => $album->getAttributes()
                ]);
            }

            return $this->error('An error occured while trying to save album', 500);
        }

        return $this->error('Album not found', 404);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_DELETE, LoggedUserMiddleware::class)]
    public function delete(): Response
    {
        $albumId = $this->getArg('id');
        $album = Album::find($albumId);

        if (!is_null($album)) {
            Song::where('album_id', $album->id)->delete();

            if ($album->delete()) {
                unlink(__DIR__ . "/../../../public/{$album->thumbnail}");
                return $this->json([
                    'error' => false,
                    'message' => "Album {$album->name} successfully deleted"
                ]);
            }

            return $this->error('An error occured while trying to delete album', 500);
        }

        return $this->error('Album not found', 404);
    }
}
