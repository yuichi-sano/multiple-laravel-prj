<?php

namespace App\Providers;
use packages\Domain\Model\User\User;
use packages\Infrastructure\Database\Doctrine as DoctrineRepos;
use packages\Infrastructure\Database as DatabaseRepos;
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
