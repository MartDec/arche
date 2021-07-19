<?php


namespace App\Http\Controllers;


use App\Http\Middlewares\LoggedUserMiddleware;
use App\Models\Playlist;
use App\Router\Route;
use App\Upload\ThumbnailUploader;
use Psr\Http\Message\ResponseInterface as Response;

#[Route('/playlists')]
class PlaylistController extends AbstractController
{
    #[Route('')]
    public function getAll(): Response
    {
        if (intval($this->getParam('latest')) === 1)
            $playlists = Playlist::query()
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        else
            $playlists = Playlist::all();

        foreach ($playlists as $playlist) {
            $playlist->creator = $playlist->creator()->get()->first();
            $playlist->songs = $playlist->songs()->get();
        }

        return $this->json([
            'error' => false,
            'playlists' => $playlists
        ]);
    }

    #[Route('/{id:[0-9]+}')]
    public function  getOne(): Response
    {
        $playlistId = $this->getArg('id');
        $playlist = Playlist::find($playlistId);

        if (!is_null($playlist)) {
            $playlist->creator = $playlist->creator()->get()->first();
            $playlist->songs = $playlist->songs()->get();

            return $this->json([
                'error' => false,
                'playlist' => $playlist
            ]);
        }

        return $this->error('Playlist not found', 404);
    }

    #[Route('', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function create(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['title'])) {
            $file = $this->getUploadedFile(self::UPLOAD_TYPE_THUMBNAIL);
            $uploader = (new ThumbnailUploader($file))->upload();
            $body['thumbnail'] = $uploader->getFilePath();
            $body['creator_id'] = $this->currentUser()->id;
            $playlist = new Playlist($body);
            if ($playlist->save()) {
                return $this->json([
                    'error' => false,
                    'playlist' => $playlist
                ]);
            }

            return $this->error('An error occured while creating playlist', 500);
        }

        return $this->error('Fields missing', 400);
    }
}
