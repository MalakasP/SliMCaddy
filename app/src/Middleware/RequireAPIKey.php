<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Factory\ResponseFactory;

class RequireAPIKey implements MiddlewareInterface
{

    public function __construct(private ResponseFactory $factory)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$request->hasHeader('X-API-KEY')) {
            $response = $this->factory->createResponse();

            $response->getBody()
                    ->write(json_encode('API key is missing!'));

            return $response->withStatus(400);
        }

        if ($request->getHeaderLine('X-API-KEY') !== $_ENV['API_KEY']) {
            $response = $this->factory->createResponse();

            $response->getBody()
                    ->write(json_encode('Invalid API key!'));

            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
