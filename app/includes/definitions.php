<?php

use App\Config;
use App\DB;

return [
    Config::class => DI\create(Config::class),
    DB::class     => function (Config $config) {
        return new \App\DB($config->db);
    }
];
