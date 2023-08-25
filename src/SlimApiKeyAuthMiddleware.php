<?php

namespace SlimApiKeyAuthMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpForbiddenException;

final class SlimApiKeyAuthMiddleware implements MiddlewareInterface
{
    private array $apiKeys;

    public function __construct($apiKeys)
    {
        $this->apiKeys = (array)$apiKeys;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $apiKey = $request->getHeaderLine('x_api_key');

        if (!$apiKey) {
            throw new HttpUnauthorizedException($request);
        }

        if (!in_array($apiKey, $this->apiKeys)) {
            throw new HttpForbiddenException($request);
        }

        return $handler->handle($request);
    }
}