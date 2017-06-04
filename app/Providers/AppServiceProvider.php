<?php

namespace App\Providers;

use Cache;
use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function ($view) {
            static $channels;
            if (empty($channels)) {
                $channels = Channel::orderBy('name')->get();
            }
            $view->with('channels', $channels);
        });
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
