<?php

namespace Hideyo\Providers;

use Illuminate\Support\ServiceProvider;

use Hideyo\Services\Cart\Cart;

class CartServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cart', function ($app) {

            $storage = $app['session'];
            $events = $app['events'];
            $instanceName = 'cart';
            $session_key = '88uuiioo99888';

            return new Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('cart')
                );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('');
    }
}