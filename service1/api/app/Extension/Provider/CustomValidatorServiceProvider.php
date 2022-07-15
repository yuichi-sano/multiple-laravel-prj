<?php

namespace App\Extension\Provider;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 郵便番号 ハイフンありなしのバリデーション
         *
         * @return bool
         */
        Validator::extend(
            'zipcode',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^[0-9]{3}-?[0-9]{4}$/', $value);
            },
        );
        // 半角カナのみ受け付ける
        Validator::extend(
            "half_kana",
            function ($attribute, $value, $parameters, $validator) {
                return preg_match('/\A[ｦ-ﾟ]+\z/u', $value);
            },
        );
        Validator::extend(
            'ip_address',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match(
                    '/^((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])$/',
                    $value
                );
            },
        );

        Validator::extend(
            'float',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match('/((^[0-9]{0,3})(.[0-9]{0,4}$))|(^[0-9]{0,3}$)/', $value);
            },
        );
    }
}
