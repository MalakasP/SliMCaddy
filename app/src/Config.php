<?php

declare(strict_types=1);

namespace App;

class Config
{
    protected array $config = [];

    public function __construct()
    {
        $this->config = [
            'db' => [
                'host'     => $_ENV['DB_HOST'],
                'user'     => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'dbname'   => $_ENV['DB_DATABASE'],
                'driver'   => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
                'port'     => $_ENV['DB_PORT'] ?? 3306,
            ]
        ];
    }

    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }
}
