<?php

namespace App\Extension\Provider;
use App\Extension\Hasher\MD5Hasher;
use Illuminate\Hashing\HashManager;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Hash;
class CustomHashServiceProvider extends HashServiceProvider
{

    /**
     * config/hashing.php
     * 'driver' => 'bcrypt'|'sha256'|'md5'
     */
    public function boot()
    {
        Hash::extend('sha256', function ($app) {
            return new SHA256Hasher();
        });
        Hash::extend('md5', function ($app) {
            return new MD5Hasher();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->defaultHash();
        $this->md5Hash();
        $this->SHA256Hash();
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $provides = $this->defaultProvides();
        array_merge($provides,$this->md5Provides());
        array_merge($provides,$this->SHA256Provides());
        return $provides;
    }


    public function defaultHash(){
        $this->app->singleton('hash', function ($app) {
            return new HashManager($app);
        });

        $this->app->singleton('hash.driver', function ($app) {
            return $app['hash']->driver();
        });
    }

    public function defaultProvides(): array
    {
        return ['hash', 'hash.driver'];
    }


    public function md5Hash(){
        $this->app->singleton('md5hash', function ($app) {
            return new MD5Hasher($app);
        });

        $this->app->singleton('md5hash.driver', function ($app) {
            return $app['md5hash']->driver();
        });
    }
    public function md5Provides(): array{
        return ['md5hash', 'md5hash.driver'];
    }

    public function SHA256Hash(){
        $this->app->singleton('sha256hash', function ($app) {
            return new SHA256Hasher($app);
        });

        $this->app->singleton('sha256hash.driver', function ($app) {
            return $app['sha256hash']->driver();
        });
    }
    public function SHA256Provides(): array{
        return ['sha256hash', 'sha256hash.driver'];
    }

}
