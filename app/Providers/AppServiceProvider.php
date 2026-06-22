<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TmaThresholdExceeded;
use App\Listeners\SendEarlyWarningNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            TmaThresholdExceeded::class,
            SendEarlyWarningNotification::class
        );
    }
}
