<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new \App\App)->boot()->setMiddleware()->setRoutes()->run();
