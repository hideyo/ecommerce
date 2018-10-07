<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Hideyo\Ecommerce\Framework\Services\Order\Events\OrderChangeStatus' => [
            'Hideyo\Ecommerce\Framework\Services\Order\Events\Handlers\HandleOrderStatusValidated',
            'Hideyo\Ecommerce\Framework\Services\Order\Events\Handlers\HandleOrderStatusEmail'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}


