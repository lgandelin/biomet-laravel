<?php

namespace Webaccess\BiometLaravel;

use Illuminate\Support\ServiceProvider;

class BiometLaravelServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $basePath = __DIR__.'/../../';

        include __DIR__.'/Http/routes.php';

        $this->loadViewsFrom($basePath.'resources/views/', 'biomet');
        $this->loadTranslationsFrom($basePath.'resources/lang/', 'biomet');

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
        /*App::bind('SignupInteractor', function () {
             return new SignupInteractor(
                 new EloquentPlatformRepository(),
                 new EloquentAdministratorRepository(),
                 new EloquentNodeRepository(),
                 new DigitalOceanService(),
                 new LaravelLoggerService()
             );
        });*/

        /*$this->commands([
            SetNodeAvailable::class,
        ]);*/
    }
}
