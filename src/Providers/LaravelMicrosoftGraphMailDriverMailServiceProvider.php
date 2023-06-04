<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Providers;

use Illuminate\Mail\MailServiceProvider;
use Mz\LaravelMicrosoftGraphMailDriver\LaravelMicrosoftGraphMailDriverMailManager;

class LaravelMicrosoftGraphMailDriverMailServiceProvider extends MailServiceProvider
{
    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function ($app) {
            return new LaravelMicrosoftGraphMailDriverMailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}