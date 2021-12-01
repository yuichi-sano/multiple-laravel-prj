<?php

namespace App\Providers;
use packages\Domain\Model\User\User;
use packages\Infrastructure\Database\Doctrine as DoctrineRepos;
use packages\Infrastructure\Database as DatabaseRepos;
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
            \packages\Service\UserGetInterface::class,
            \packages\Service\UserGetService::class
        );

    }
    private function registerForMock(){

        $this->app->bind(
            \packages\Service\UserGetInterface::class,
            \packages\Service\TestUserGetService::class
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
