<?php

namespace App\Providers;
use packages\domain\model\User\User;
use packages\infrastructure\database\doctrine as DoctrineRepos;
use packages\infrastructure\database as DatabaseRepos;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //if(config()=== 'prd')
        $this->registerForInMemory();
    }

    private function registerForInMemory(){

        $this->app->bind(
            \packages\service\UserGetInterface::class,
            \packages\service\UserGetService::class
        );
        $this->app->bind(
            \packages\service\authentication\AccessTokenGetInterface::class,
            \packages\service\authentication\AccessTokenGetService::class
        );
        $this->app->bind(
            \packages\service\authentication\RefreshTokenUpdateInterface::class,
            \packages\service\authentication\RefreshTokenUpdateService::class
        );
        $this->app->bind(
            \packages\service\authentication\AccountAuthenticationInterface::class,
            \packages\service\authentication\AccountAuthenticationService::class
        );

    }
    private function registerForMock(){

        $this->app->bind(
            \packages\service\UserGetInterface::class,
            \packages\service\TestUserGetService::class
        );

    }



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
