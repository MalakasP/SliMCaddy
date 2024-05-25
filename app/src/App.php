<?php

declare(strict_types=1);

namespace App;

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;
use Symfony\Component\Dotenv\Dotenv;
use App\Routes\API;
use App\Middleware\JsonResponseHeader;


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

        $this->container = $this->createContainer();

        $this->app = Bridge::create($this->container);

        return $this;
    }

    public function setMiddleware(): static
    {
        $this->app->addBodyParsingMiddleware();

        $this->app->addRoutingMiddleware();

        $errorMiddleware = $this->app->addErrorMiddleware(false, true, true);

        $errorHandler = $errorMiddleware->getDefaultErrorHandler();

        $errorHandler->forceContentType('application/json');

        $this->app->addMiddleware(new JsonResponseHeader);

        return $this;
    }

    public function setRoutes(): static
    {
        $this->app = (new API())->setRoutes($this->app);

        return $this;
    }

    public function run()
    {
        $this->app->run();
    }

    private function createContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();

        if ('prod' === $_ENV['APP_ENV']) {
            $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
            $containerBuilder->enableDefinitionCache();
        }

        $containerBuilder->addDefinitions(__DIR__ . '/definitions.php');

        return $containerBuilder->build();
    }
}
