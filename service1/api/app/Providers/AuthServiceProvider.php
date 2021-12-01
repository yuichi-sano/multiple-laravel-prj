<?php

namespace App\Providers;

use App\Extension\Auth\Guard\ExtensionJWTGuard;
use App\Extension\Auth\Provider\ExtensionDoctrineUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //add Extensions
        $this->registerDoctrineCustom();
        $this->registerJWTCustom();
    }

    /**
     * Doctrineで用意されたUserProviderをさらに拡張したUserProviderを呼び出します。
     */
    private function registerDoctrineCustom(){
        $this->app['auth']->provider('doctrine-custom', function ($app, $config) {
            $entity = $config['model'];
            $em = $app['registry']->getManagerForClass($entity);
            if (!$em) {
                throw new InvalidArgumentException("No EntityManager is set-up for {$entity}");
            }
            return new ExtensionDoctrineUserProvider(
                $app['hash'],
                $em,
                $entity
            );
        });
    }

    /**
     * Extend Laravel's Auth.
     *
     * @return void
     */
    private function registerJWTCustom()
    {
        $this->app['auth']->extend('jwt-custom', function ($app, $name, array $config) {
            $guard = new ExtensionJWTGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

}
