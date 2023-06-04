<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Transports;

use Illuminate\Mail\Transport\Transport;
use Mz\LaravelMicrosoftGraphMailDriver\Services\LaravelMicrosoftGraphMailDriverService;

class MicrosoftTransport extends Transport
{
    /**
     * Guzzle client instance.
     *
     * @var LaravelMicrosoftGraphMailDriverService
     */
    protected $service;

    /**
     * Create a new Custom transport instance.
     *
     * @param  LaravelMicrosoftGraphMailDriverService $service
     */
    public function __construct(LaravelMicrosoftGraphMailDriverService $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->service->send(
            toMails: $this->mapContactsToNameEmail($message->getTo()),
            subject: $message->getSubject(),
            body: $message->getBody(),
            ccMails: $this->mapContactsToNameEmail($message->getCc()),
            bccMails: $this->mapContactsToNameEmail($message->getBcc()),
        );
        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }
    protected function mapContactsToNameEmail($contacts)
    {
        $formatted = [];
        if (empty($contacts)) {
            return [];
        }
        foreach ($contacts as $address => $display) {
            $formatted[] =  $address;
        }
        return $formatted;
    }

}