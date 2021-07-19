<?php

namespace App\Http\Controllers;

use App\Http\Middlewares\LoggedUserMiddleware;
use App\Router\Route;
use Psr\Http\Message\ResponseInterface as Response;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/update', Route::HTTP_METHOD_POST, LoggedUserMiddleware::class)]
    public function update(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if ($user = $this->currentUser()) {
            foreach ($body as $attribute => $value) {
                $user->$attribute = $value;
                if ($attribute === 'password')
                    $user->hashPassword();
            }
    
            if ($user->save()) {
                return $this->json([
                    'error' => false,
                    'user' => $user->getAttributes()
                ]);
            }

            return $this->error('An error occured while trying to update your account', 500);
        }

        return $this->error('Impossible to retrieve your account', 500);
    }

    #[Route('/delete', Route::HTTP_METHOD_DELETE, LoggedUserMiddleware::class)]
    public function register(): Response
    {
        $user = $this->currentUser();
        if ($user) {
            $user->delete();
            return $this->json([
                'error' => false,
                'message' => "User {$user->username} successfully deleted"
            ]);
        }

        return $this->error('An error occured while trying to delete your account', 500);
    }
}