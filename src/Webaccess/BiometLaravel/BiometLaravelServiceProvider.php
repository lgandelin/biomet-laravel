<?php

namespace Webaccess\BiometLaravel;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webaccess\BiometLaravel\Commands\CreateUserCommand;
use Webaccess\BiometLaravel\Commands\GenerateDataFromExcelCommand;
use Webaccess\BiometLaravel\Commands\GenerateRandomDatabaseDataCommand;
use Webaccess\BiometLaravel\Commands\GenerateRandomJSONDataCommand;
use Webaccess\BiometLaravel\Commands\GenerateSampleExcelDataCommand;
use Webaccess\BiometLaravel\Commands\HandleExcelCommand;
use Webaccess\BiometLaravel\Http\Middlewares\AdminClientsMiddleware;
use Webaccess\BiometLaravel\Http\Middlewares\AdminMiddleware;

class BiometLaravelServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(Router $router)
    {
        $basePath = __DIR__.'/../../';

        include __DIR__.'/Http/routes.php';

        $this->loadViewsFrom($basePath.'resources/views/', 'biomet');
        $this->loadTranslationsFrom($basePath.'resources/lang/', 'biomet');
        $router->middleware('admin', AdminMiddleware::class);
        $router->middleware('admin_clients', AdminClientsMiddleware::class);

        $this->publishes([
            $basePath.'resources/assets/css' => base_path('public/css'),
            $basePath.'resources/assets/js' => base_path('public/js'),
            $basePath.'resources/assets/fonts' => base_path('public/fonts'),
            $basePath.'resources/assets/img' => base_path('public/img'),
        ], 'assets');

        $this->publishes([
            $basePath.'database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    public function register()
    {
        $this->commands([
            CreateUserCommand::class,
            GenerateRandomDatabaseDataCommand::class,
            GenerateRandomJSONDataCommand::class,
            GenerateSampleExcelDataCommand::class,
            HandleExcelCommand::class,
            GenerateDataFromExcelCommand::class,
        ]);

        $this->app->register(
            'Webaccess\BiometLaravel\AuthServiceProvider'
        );
    }
}
