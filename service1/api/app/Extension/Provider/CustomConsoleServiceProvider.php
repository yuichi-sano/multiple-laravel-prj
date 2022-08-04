<?php

namespace App\Extension\Provider;

//use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;

class CustomConsoleServiceProvider extends ConsoleSupportServiceProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        //MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
