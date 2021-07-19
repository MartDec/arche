<?php

namespace App\Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class LoggedUserMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeaderLine('Authorization'))
            return $handler->handle($request);

        $response = (new Response(401))->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'error' => true,
            'message' => 'You cant access this route'
        ]));

        return $response;
    }
}