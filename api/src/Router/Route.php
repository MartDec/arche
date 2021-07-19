<?php

namespace App\Router;

use App\Http\Middlewares\JsonBodyParserMiddleware;
use App\Http\Middlewares\AccessControlMiddleware;
use Slim\App;
use Attribute;
use ReflectionClass;
use ReflectionMethod;
use HaydenPierce\ClassFinder\ClassFinder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class Route
{
    const HTTP_METHOD_GET       = 'get';
    const HTTP_METHOD_POST      = 'post';
    const HTTP_METHOD_DELETE    = 'delete';

    private static array $called = [];

    public function __construct(
        private string  $path,
        private string  $method     = self::HTTP_METHOD_GET,
        private ?string $middleware = null
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getMiddleware(): ?string
    {
        return $this->middleware;
    }

    public static function getRoutes(App $app)
    {
        $app->addMiddleware(new JsonBodyParserMiddleware());
        $app->addMiddleware(new AccessControlMiddleware());
        $app->addRoutingMiddleware();
        $controllers = ClassFinder::getClassesInNamespace('App\Http\Controllers', ClassFinder::RECURSIVE_MODE);
        foreach ($controllers as $controller) {
            $class = new ReflectionClass($controller);
            $prefix = self::getPrefix($class);

            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                self::listen($app, $method, $controller, $prefix);
            }
        }
        foreach ($app->getRouteCollector()->getRoutes() as $route) {
            $pattern = $route->getPattern();
            if (!in_array($pattern, self::$called)) {
                $app->options($pattern, function (ServerRequestInterface $request, ResponseInterface $response) {
                    return $response;
                });
                self::$called[] = $pattern;
            }
        }
    }

    private static function getPrefix(ReflectionClass $class)
    {
        $routeAttributes = $class->getAttributes(self::class);
        $prefix = null;
        if (!empty($routeAttributes)) {
            $prefix = $routeAttributes[0]->newInstance()->getPath();
        }

        return $prefix;
    }

    private static function listen(
        App $app,
        ReflectionMethod $method,
        string $controller,
        ?string $prefix
    ) {
        $attributes = $method->getAttributes(self::class);
        if (empty($attributes))
            return;

        foreach ($attributes as $attribute) {
            $route = $attribute->newInstance();
            $httpMethod = strtolower($route->getMethod());
            $path = $prefix . $route->getPath();
            $middleware = $route->getMiddleware();

            if ($middleware) {
                $app->$httpMethod(
                    $path,
                    [ new $controller($method->getName()), 'call' ]
                )->add(new $middleware());
            } else {
                $app->$httpMethod(
                    $path,
                    [ new $controller($method->getName()), 'call' ]
                );
            }
        }
    }
}