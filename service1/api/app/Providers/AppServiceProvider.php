<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use packages\infrastructure\database\doctrine\DoctrineTransactionManager;
use packages\service\authentication\AccessTokenGetInterface;
use packages\service\authentication\AccessTokenGetService;
use packages\service\authentication\AccountAuthenticationInterface;
use packages\service\authentication\AccountAuthenticationService;
use packages\service\authentication\RefreshTokenUpdateInterface;
use packages\service\authentication\RefreshTokenUpdateService;
use packages\service\helper\TransactionManagerInterface;
use packages\service\jp\ZipCodeGetInterface;
use packages\service\jp\ZipCodeGetService;
use packages\service\jp\ZipCodeMigrationApplyInterface;
use packages\service\jp\ZipCodeMigrationApplyService;
use packages\service\merchant\MerchantGetService;
use packages\service\MerchantGetInterface;
use packages\service\TestUserGetService;
use packages\service\UserGetInterface;
use packages\service\UserGetService;
use packages\service\yamato\YamatoMigrationApplyInterface;
use packages\service\yamato\YamatoMigrationApplyService;

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

    private function registerForInMemory()
    {
        $this->app->bind(
            UserGetInterface::class,
            UserGetService::class
        );
        $this->app->bind(
            \packages\service\merchant\MerchantGetInterface::class,
            MerchantGetService::class
        );
        $this->app->bind(
            AccessTokenGetInterface::class,
            AccessTokenGetService::class
        );
        $this->app->bind(
            RefreshTokenUpdateInterface::class,
            RefreshTokenUpdateService::class
        );
        $this->app->bind(
            AccountAuthenticationInterface::class,
            AccountAuthenticationService::class
        );
        $this->app->singleton(
            TransactionManagerInterface::class,
            DoctrineTransactionManager::class
        );
        $this->app->bind(
            ZipCodeMigrationApplyInterface::class,
            ZipCodeMigrationApplyService::class
        );
        $this->app->bind(
            ZipCodeGetInterface::class,
            ZipCodeGetService::class
        );
    }

    private function registerForMock()
    {
        $this->app->bind(
            MerchantGetInterface::class,
            TestUserGetService::class
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
