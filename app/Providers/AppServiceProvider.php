<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // use bootstrap paginator
        Paginator::useBootstrap();
        // create a custom validator to check password strength
        Validator::extend('nist_password', 'App\Rules\CustomValidator@validatePasswordStrength');
    }
}
