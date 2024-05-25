<?php

declare(strict_types=1);

use App\Config;
use App\DB;

return [
    Config::class => DI\create(Config::class),
    DB::class     => function (Config $config) {
        return new DB($config->db);
    }
];
