<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\SensorsData;
use App\Middleware\RequireAPIKey;
use Slim\App as SlimApp;
use Slim\Routing\RouteCollectorProxy;

class API
{

    public function setRoutes(SlimApp $app): SlimApp
    {
        $app->group('/api', function (RouteCollectorProxy $group) {
            $group->get('/sensors/data', [SensorsData::class, 'showAll']);

            $group->get('/sensors/{address}/data', [SensorsData::class, 'showByAddress']);

            $group->post('/sensors/data', [SensorsData::class, 'create']);

            $group->delete('/sensors/{address}/data', [SensorsData::class, 'delete']);
        })->add(RequireAPIKey::class);

        return $app;
    }
}
