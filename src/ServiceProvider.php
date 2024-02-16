<?php

namespace SnakeInk\Crude;

use Illuminate\Contracts\Support\DeferrableProvider;
use SnakeInk\Crude\Commands\Generators\MakeRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SnakeInk\Crude\Commands\Generators\MakeController;
use SnakeInk\Crude\Commands\Generators\MakeFactory;
use SnakeInk\Crude\Commands\Generators\MakeModel;
use SnakeInk\Crude\Commands\Generators\MakePolicy;
use SnakeInk\Crude\Commands\Generators\MakeRouteTest;
use SnakeInk\Crude\Commands\Generators\MakeService;
use SnakeInk\Crude\Commands\Generators\MakeValidator;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $translationsPath = __DIR__.'/../lang';

        $this->loadJsonTranslationsFrom($translationsPath);

        $this->loadTranslationsFrom(
            path: $translationsPath,
            namespace: 'sss'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeController::class,
                MakeFactory::class,
                MakeModel::class,
                MakePolicy::class,
                MakeRepository::class,
                MakeRouteTest::class,
                MakeService::class,
                MakeValidator::class
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
