<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\SensorDataRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class SensorsData
{

    public function __construct(
        private SensorDataRepository $repository,
        private Validator $validator
    ) {
        $this->validator->mapFieldsRules([
            'sensor_address' => ['required', 'integer', ['min', 1]],
            'property_id' => ['required', 'integer', ['min', 1]],
            'value' => ['required', 'integer']
        ]);
    }

    public function showAll(Request $request, Response $response): Response
    {
        $sensors_data = $this->repository->getAll();

        if (false === $sensors_data) {
            throw new \Slim\Exception\HttpNotFoundException($request, 'Sensors data not found.');
        }

        $body = json_encode($sensors_data);

        $response->getBody()->write($body);

        return $response;
    }

    public function showByAddress(Request $request, Response $response, string $address): Response
    {
        $params = $request->getQueryParams();

        $property_id = !empty($params['property_id']) ? $params['property_id'] : 0;

        $sensor_data = $this->repository->getByAddress(intval($address), intval($property_id));

        if (false === $sensor_data) {
            throw new \Slim\Exception\HttpNotFoundException($request, 'Sensor data by address not found.');
        }

        $body = json_encode($sensor_data);

        $response->getBody()->write($body);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {

            $response->getBody()
                ->write(json_encode($this->validator->errors()));

            return $response->withStatus(400);
        }

        $id = $this->repository->create($body);

        $body = json_encode([
            'id' => intval($id)
        ]);

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    public function delete(Request $request, Response $response, string $address): Response
    {
        $rows = $this->repository->delete(intval($address));

        $body = json_encode([
            'message' => "Sensor {$address} data deleted.",
            'rows'    => $rows
        ]);

        $response->getBody()->write($body);

        return $response;
    }
}
