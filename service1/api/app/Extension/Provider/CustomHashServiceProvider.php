<?php

namespace App\Extension\Provider;

use App\Extension\Hasher\MD5Hasher;
use App\Extension\Hasher\SHA256Hasher;
use App\Extension\Hasher\SHA512Hasher;
use Illuminate\Hashing\HashManager;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Hash;

class CustomHashServiceProvider extends HashServiceProvider
{
    /**
     * config/hashing.php
     * 'driver' => 'bcrypt'|'sha256'|'md5'|'sha512'
     */
    public function boot()
    {
        Hash::extend('sha256', function ($app) {
            return new SHA256Hasher();
        });
        Hash::extend('md5', function ($app) {
            return new MD5Hasher();
        });
        Hash::extend('sha512', function ($app) {
            return new SHA512Hasher();
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
        $this->SHA512Hash();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $provides = $this->defaultProvides();
        array_merge($provides, $this->md5Provides());
        array_merge($provides, $this->SHA256Provides());
        array_merge($provides, $this->SHA512Provides());
        return $provides;
    }

    public function defaultHash()
    {
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

    public function md5Hash()
    {
        $this->app->singleton('md5hash', function ($app) {
            return new MD5Hasher($app);
        });

        $this->app->singleton('md5hash.driver', function ($app) {
            return $app['md5hash']->driver();
        });
    }

    public function md5Provides(): array
    {
        return ['md5hash', 'md5hash.driver'];
    }

    public function SHA256Hash()
    {
        $this->app->singleton('sha256hash', function ($app) {
            return new SHA256Hasher($app);
        });

        $this->app->singleton('sha256hash.driver', function ($app) {
            return $app['sha256hash']->driver();
        });
    }

    public function SHA256Provides(): array
    {
        return ['sha256hash', 'sha256hash.driver'];
    }

    public function SHA512Hash()
    {
        $this->app->singleton('sha512hash', function ($app) {
            return new SHA512Hasher($app);
        });

        $this->app->singleton('sha512hash.driver', function ($app) {
            return $app['sha512hash']->driver();
        });
    }

    public function SHA512Provides(): array
    {
        return ['sha512hash', 'sha512hash.driver'];
    }
}
