<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
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
        if (str_contains(request()->getHost(), 'ngrok') || request()->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            URL::forceScheme('https');
        }

        Event::listen(
            TmaThresholdExceeded::class,
            SendEarlyWarningNotification::class
        );
    }
}
