<?php

if (!function_exists('customize_hash')) {
    /**
     * Hash the given value against the customize_hash algorithm.
     * @note please change driver
     * @param string $value
     * @param array $options
     * @return string
     */
    function customize_hash($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (!function_exists('package_path')) {
    /**
     * Get the path to the package folder.
     *
     * @param string $path
     * @return string
     */
    function package_path($path = '')
    {
        return app()->basePath('packages') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('native_query_path')) {
    /**
     * Get the path to the native_query folder.
     *
     * @param string $path
     * @return string
     */
    function native_query_path($path = '')
    {
        return app()->basePath('packages/infrastructure/database/sql') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}


if (!function_exists('data_migrations_path')) {
    /**
     * Get the path to the data_migration folder.
     *
     * @param string $path
     * @return string
     */
    function data_migrations_path($path = '')
    {
        return app()->databasePath('data_migrations') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('parent_dir')) {
    /**
     * Get the path to the parent_dir folder.
     *
     * @param string $path
     * @return string
     */
    function parent_dir($path = '')
    {
        $dirs = explode('/', $path);
        return array_pop($dirs);
    }
}
if (!function_exists('doctrine_repo_path')) {
    /**
     * Get the path to the native_query folder.
     *
     * @param string $path
     * @return string
     */
    function doctrine_repo_path($path = '')
    {
        return app()->basePath(
            'packages/infrastructure/database/doctrine'
        ) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
