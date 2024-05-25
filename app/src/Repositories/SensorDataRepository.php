<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DB;
use Doctrine\DBAL\ParameterType;

class SensorDataRepository
{

    public function __construct(private DB $db)
    {
    }

    public function getAll(): bool|array
    {
        $sensors_data = $this->db->fetchAllAssociative("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 100");

        return $sensors_data;
    }

    public function getByAddress(int $address, int $property_id = 0): bool|array
    {
        $sql = 'SELECT 
                sd.id,
                sd.sensor_address,
                sd.property_id,
                sd.value,
                pm.multiplier,
                CAST(sd.value / pm.multiplier AS DECIMAL(10,2)) AS floating_value
                FROM sensor_data sd
                JOIN property_multipliers pm ON sd.property_id = pm.property_id
                WHERE sd.sensor_address = :address';

        if (0 !== $property_id) {
            $sql .= " AND sd.property_id = :property_id";
        }

        $params = [
            'address' => $address,
            'property_id' => $property_id
        ];

        $params_types = [
            ParameterType::INTEGER,
            ParameterType::INTEGER,
        ];

        $sensor_data = $this->db->fetchAllAssociative($sql, $params, $params_types);

        return $sensor_data;
    }

    public function create(array $sensor_data): string|int|false
    {
        $params_types = [
            ParameterType::INTEGER,
            ParameterType::INTEGER,
            ParameterType::INTEGER
        ];

        $this->db->insert('sensor_data', $sensor_data, $params_types);

        return $this->db->lastInsertId();
    }

    public function delete(int $address): int|string
    {
        $params = [
            'sensor_address' => $address
        ];

        $params_types = [
            ParameterType::INTEGER
        ];

        return $this->db->delete('sensor_data', $params, $params_types);
    }
}
