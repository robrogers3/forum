<?php

namespace App\Providers;

use Cache;
use App\Channel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            //Log::info(__METHOD__, [$query->sql, $query->bindings]);
            // $query->sql
            // $query->bindings
            // $query->time
        });
        \View::composer('*', function ($view) {
            static $channels;
            if (empty($channels)) {
                $channels = Channel::orderBy('name')->get();
            }
            $view->with('channels', $channels);
        });

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (false && $this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
