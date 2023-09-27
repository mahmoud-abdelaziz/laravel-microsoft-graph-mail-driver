<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Mz\LaravelMicrosoftGraphMailDriver\Services\LaravelMicrosoftGraphMailDriverService;
use Mz\LaravelMicrosoftGraphMailDriver\Transports\MicrosoftTransport;

class LaravelMicrosoftGraphMailDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mail.php', 'mail.mailers'
        );
        Mail::extend('microsoft', function (array $config = []) {
            return new MicrosoftTransport(new LaravelMicrosoftGraphMailDriverService());
        });
    }
}