<?php

namespace App\Http\Controllers;

use App\Http\Middlewares\LoggedUserMiddleware;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use App\Router\Route;
use App\Upload\ThumbnailUploader;
use App\Upload\Uploader;
use Psr\Http\Message\ResponseInterface as Response;

#[Route('/artists')]
class ArtistController extends AbstractController
{
    #[Route('')]
    public function getAll(): Response
    {
        if (intval($this->getParam('latest')) === 1)
            $artists = Artist::query()
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        else
            $artists = Artist::all();

        foreach ($artists as $artist) {
            $artist->creator = $artist->creator()->get()->first();
            $artist->albums = $artist->albums()->get();
            $artist->songs = $artist->songs()->get();
        }

        return $this->json([
            'error' => false,
            'artists' => $artists
        ]);
    }

    #[Route('/{id:[0-9]+}')]
    public function getOne(): Response
    {
        $artistId = $this->getArg('id');
        $artist = Artist::find($artistId);

        if (!is_null($artist)) {
            $artist->creator = $artist->creator()->get()->first();
            $artist->albums = $artist->albums()->get();
            $artist->songs = $artist->songs()->get();

            return $this->json([
                'error' => false,
                'artist' => $artist
            ]);
        }

        return $this->error('Artist not found', 404);
    }

    #[Route('', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function create(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['name'])) {
            $file = $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL);
            $uploader = (new ThumbnailUploader($file))->upload();
            $body['thumbnail'] = $uploader->getFilePath();
            if (!$this->currentUser())
                return $this->error('You cant access this route', 401);

            $body['creator_id'] = $this->currentUser()?->id;
            $artist = new Artist($body);
            if ($artist->save()) {
                return $this->json([
                    'error' => false,
                    'artist' => $artist
                ]);
            }

            return $this->error('An error occured while creating artist', 500);
        }

        return $this->error('Fields missing', 400);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function update(): Response
    {
        $artistId = $this->getArg('id');
        $body = $this->getRequest()->getParsedBody();
        $artist = Artist::find($artistId);

        if (!is_null($artist)) {
            if (!is_null($this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL)) &&
                $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL)->getClientFilename() !== '') {

                $file = $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL);
                unlink(__DIR__ . '/../../../' . $artist->thumbnail);
                $uploader = (new ThumbnailUploader($file))->upload();
                $filename = $uploader->getFilePath();
                $body['thumbnail'] = $filename;
            }

            $artist->fill($body);
            if ($artist->save()) {
                return $this->json([
                    'error' => false,
                    'artist' => $artist->getAttributes()
                ]);
            }

            return $this->error('An error occured while trying to save artist', 500);
        }

        return $this->error('Artist not found', 404);
    }

    #[Route('/{id:[0-9]+}', Route::HTTP_METHOD_DELETE, LoggedUserMiddleware::class)]
    public function delete(): Response
    {
        $artistId = $this->getArg('id');
        $artist = Artist::find($artistId);

        if (!is_null($artist)) {
            Song::where('artist_id', $artist->id)->delete();
            Album::where('artist_id', $artist->id)->delete();

            if ($artist->delete()) {
                unlink(__DIR__ . '/../../../' . $artist->thumbnail);
                return $this->json([
                    'error' => false,
                    'message' => "Artist {$artist->name} successfully deleted"
                ]);
            }

            return $this->error('An error occured while trying to delete artist', 500);
        }

        return $this->error('Artist not found', 404);
    }
}
