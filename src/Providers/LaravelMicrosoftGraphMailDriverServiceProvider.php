<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelMicrosoftGraphMailDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mail.php', 'mail.mailers'
        );
    }
}