<?php

namespace App\Http\Controllers;

use App\Models\User;
use Owenoj\LaravelGetId3\GetId3;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractController
{
    const UPLOAD_TYPE_THUMBNAIL = 'thumbnail';
    const UPLOAD_TYPE_SONG      = 'song';

    protected ?Request  $request    = null;
    protected ?Response $response   = null;
    protected ?array    $args       = null;

    public function __construct(
        protected string $method
    ) {}

    public function call(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        $methodName = $this->method;
        return $this->$methodName();
    }

    public function currentUser(): ?User
    {
        $user = null;
        if ($token = $this->getRequest()->getHeaderLine('Authorization')) {
            $token = str_replace('Bearer ', '', $token);
            $user = User::where('token', $token)->first();
        }

        return $user;
    }

    public function json(array $data, int $status = 200): Response
    {
        $response = $this->getResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
        $response->getBody()->write(json_encode($data));

        return $response;
    }

    public function error(string $message, int $status): Response
    {
        return $this->json([
            'error' => true,
            'message' => $message
        ], $status);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getArg(string $name): mixed
    {
        return $this->args[$name];
    }

    public function getParams(): array
    {
        return $this->getRequest()->getQueryParams();
    }

    public function getParam($name): mixed
    {
        $params = $this->getParams();
        return $params[$name] ?? null;
    }

    public function getUploadedFiles()
    {
        return $this->getRequest()->getUploadedFiles();
    }

    public function getUploadedFile($type)
    {
        $files = $this->getUploadedFiles();
        return $files[$type] ?? null;
    }
}
