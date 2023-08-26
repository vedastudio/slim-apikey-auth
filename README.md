# Slim 4 API Key Authentication Middleware
Simple API Key Authentication Middleware for Slim 4 Framework. Useful to apply API Key authentication on a Slim REST API.

## Install
```
composer require vedastudio/slim-apikey-auth
```

## Usage
Allowed API Keys are passed as an array or single Api Key. Only mandatory parameter is $apiKeys.
The verified key must be placed in the HTTP header of the request in a special field called x-api-key.
You may add middleware to a Slim application, to an individual Slim application route or to a route group.

## Application middleware
Application middleware is invoked for every incoming HTTP request.
``` php
<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use SlimApiKeyAuth;

require __DIR__ . '/../vendor/autoload.php';

$app->add(new ApiKeyAuthMiddleware($apiKeys));

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello World');
    return $response;
});

$app->run();
```

## Route middleware
Route middleware is invoked only if its route matches the current HTTP request method and URI. Route middleware is specified immediately after you invoke any of the Slim application’s routing methods
``` php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use SlimApiKeyAuth;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello ');
    return $response;
})->add(new ApiKeyAuthMiddleware($apiKeys));

$app->run();
```
## Error Handling Example
``` php
<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Exception\HttpException;
use SlimApiKeyAuth;

require __DIR__ . '/../vendor/autoload.php';

$app->add(new ApiKeyAuthMiddleware($apiKeys));

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello World');
    return $response;
});

try {
    $app->run();
} catch (HttpException $e) {
    die(json_encode(
        array(
            'status'=>'failed',
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'description'=>$e->getDescription()
        )
    ));
}
```
