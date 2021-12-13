<?php

namespace App\Providers;
use packages\domain\model\authentication\authorization\AuthenticationRefreshToken;
use packages\domain\model\authentication\authorization\RefreshToken;
use packages\domain\model\User\User;
use packages\infrastructure\database\doctrine as DoctrineRepos;
use packages\infrastructure\database as DatabaseRepos;
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

        $this->app->bind(DatabaseRepos\UserRepository::class, function($app) {
            return new DoctrineRepos\DoctrineUserRepository($app['em'], $app['em']->getClassMetaData(User::class));
        });
        $this->app->bind(DatabaseRepos\RefreshTokenRepository::class, function($app) {
            return new DoctrineRepos\DoctrineRefreshTokenRepository($app['em'], $app['em']->getClassMetaData(AuthenticationRefreshToken::class));
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
