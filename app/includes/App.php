<?php

namespace App;

use DI\Bridge\Slim\Bridge;
use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;
use Symfony\Component\Dotenv\Dotenv;

class App
{
    protected ContainerInterface $container;

    protected SlimApp $app;

    public function __construct()
    {
    }

    public function boot(): static
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../.env');

        $definitions = require_once __DIR__ . '/definitions.php';
        $this->container = $this->createContainer($definitions);

        $this->app = Bridge::create($this->container);

        return $this;
    }

    private function createContainer(array $definitions): \DI\Container
    {
        $containerBuilder = new \DI\ContainerBuilder();
        if ($_ENV['APP_ENV'] === 'prod') {
            $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
            $containerBuilder->enableDefinitionCache();
        }

        $containerBuilder->addDefinitions($definitions);

        return $containerBuilder->build();
    }

    public function setMiddleware(): static
    {
        $this->app->addBodyParsingMiddleware();

        $this->app->addRoutingMiddleware();

        $this->app->addErrorMiddleware(false, true, true);

        return $this;
    }

    public function setRoutes(): static
    {
        
        $this->app = (new \App\Routes\API())->setRoutes($this->app);

        return $this;
    }

    public function run()
    {
        $this->app->run();
    }
}
