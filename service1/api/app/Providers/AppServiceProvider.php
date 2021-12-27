<?php

namespace App\Providers;
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
            \packages\service\merchant\MerchantGetInterface::class,
            \packages\service\merchant\MerchantGetService::class
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
        $this->app->singleton(
            \packages\service\helper\TransactionManagerInterface::class,
            function($app) {
                return new \packages\infrastructure\database\doctrine\DoctrineTransactionManager::class;
            }
        );

    }
    private function registerForMock(){

        $this->app->bind(
            \packages\service\MerchantGetInterface::class,
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
