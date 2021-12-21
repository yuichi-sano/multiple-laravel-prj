<?php

namespace App\Providers;

use packages\infrastructure\database\doctrine as Doctrine;
use packages\infrastructure\database as Database;
use packages\domain\model as DomainModel;
use Illuminate\Support\ServiceProvider;

class DatasourceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerForInMemory();
    }

    private function registerForInMemory(){

        $this->app->bind(DomainModel\User\UserRepository::class, function($app) {
            return new Doctrine\user\DoctrineUserRepository($app['em'], $app['em']->getClassMetaData(DomainModel\User\User::class));
        });
        $this->app->bind(
            DomainModel\authentication\authorization\RefreshTokenRepository::class, function($app) {
                return new Doctrine\authentication\authorization\DoctrineRefreshTokenRepository(
                $app['em'], $app['em']->getClassMetaData(DomainModel\authentication\authorization\AuthenticationRefreshToken::class)
            );
        });
        $this->app->bind(
            DomainModel\merchant\MerchantRepository::class, function($app) {
            return new Doctrine\merchant\DoctrineMerchantRepository(
                $app['em'], $app['em']->getClassMetaData(DomainModel\merchant\Merchant::class)
            );
        });

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
