<?php

namespace Mz\LaravelMicrosoftGraphMailDriver;

use Illuminate\Mail\MailManager;
use Mz\LaravelMicrosoftGraphMailDriver\Services\LaravelMicrosoftGraphMailDriverService;
use Mz\LaravelMicrosoftGraphMailDriver\Transports\MicrosoftTransport;

class LaravelMicrosoftGraphMailDriverMailManager extends MailManager
{
    public function createMicrosoftTransport()
    {
        return new MicrosoftTransport(new LaravelMicrosoftGraphMailDriverService());
    }
}