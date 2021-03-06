<?php

namespace App\Http\Controllers;

use App\Http\Middlewares\GuestUserMiddleware;
use App\Router\Route;
use App\Models\User;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Dotenv\Dotenv;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;

class SessionController extends AbstractController
{
    #[Route('/register', Route::HTTP_METHOD_POST, GuestUserMiddleware::class)]
    public function register(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['email']) && isset($body['username']) && isset($body['password'])) {
            if (User::where('email', $body['email'])->exists())
                return $this->error("User {$body['email']} already exists", 400);

            $body['token'] = $this->generateToken($body['email'])->toString();
            $user = new User($body);
            $user->hashPassword();
            if ($user->save()) {
                return $this->json([
                    'error' => false,
                    'user' => $user->getAttributes()
                ]);
            }

            return $this->error('An error occured while creating yout profile', 500);
        }

        return $this->error('Fields are missing', 400);
    }

    #[Route('/login', Route::HTTP_METHOD_POST, GuestUserMiddleware::class)]
    public function login(): Response
    {
        $body = $this->getRequest()->getParsedBody();
        if (isset($body['email']) && isset($body['password'])) {
            if ($user = User::where('email', $body['email'])->first()) {
                if ($user->checkPassword($body['password'])) {
                    $user->token = $this->generateToken($body['email'])->toString();
                    $user->save();
                    return $this->json([
                        'error' => false,
                        'user' => $user->getAttributes()
                    ]);
                }

                return $this->error('Wrong password', 400);
            }

            return $this->error("User {$body['email']} not found", 404);
        }

        return $this->error('Fields are missing', 400);
    }

    protected function generateToken(string $email): Plain
    {
        $env = (new Dotenv())->parse(file_get_contents(__DIR__ . '/../../../.env'));
        $jwtKey = InMemory::plainText($env['JWT_TOKEN']);
        $config = Configuration::forSymmetricSigner(new Sha256(), $jwtKey);
        $token = $config->builder()
            ->issuedBy($env['CLIENT_BASEPATH'])
            ->issuedAt(new DateTimeImmutable())
            ->withClaim('user_email', $email);

        return $token->getToken($config->signer(), $config->signingKey());
    }
}