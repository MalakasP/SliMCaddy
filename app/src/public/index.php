<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

/**
 * Add the Slim built-in error middleware to the app middleware stack
 *
 * @param bool                 $displayErrorDetails
 * @param bool                 $logErrors
 * @param bool                 $logErrorDetails
 * @param LoggerInterface|null $logger
 */
$app->addErrorMiddleware(false, true, true);

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

    $respose_data = [
        'message' => "Hello, $name!",
    ];

    $response->getBody()->write(json_encode($respose_data));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
?>