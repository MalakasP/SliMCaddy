<?php

namespace App\Routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App as SlimApp;

class API
{

    public function setRoutes(SlimApp $app): SlimApp
    {
        $app->get('/hello/{name}', function ($name, Response $response, \App\DB $db) {
            $respose_data = [
                'message' => "Hello, $name!",
                'db_users' => $db->fetchAssociative("SELECT table_name FROM information_schema.tables WHERE table_schema = 'ESP-BLE-MESH'"),
            ];

            $response->getBody()->write(json_encode($respose_data));

            return $response->withHeader('Content-Type', 'application/json');
        });

        return $app;
    }
}
