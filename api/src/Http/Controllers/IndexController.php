<?php

namespace App\Http\Controllers;


use App\Router\Route;
use Psr\Http\Message\ResponseInterface as Response;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->json([
            'error' => false,
            'message' => 'Hello world!'
        ]);
    }
}
