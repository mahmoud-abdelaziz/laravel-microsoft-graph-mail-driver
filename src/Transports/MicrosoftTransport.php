<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Transports;

use Mz\LaravelMicrosoftGraphMailDriver\Services\LaravelMicrosoftGraphMailDriverService;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MicrosoftTransport extends AbstractTransport
{
    /**
     * Create a new Custom transport instance.
     *
     * @param  LaravelMicrosoftGraphMailDriverService $service
     */
    public function __construct(private LaravelMicrosoftGraphMailDriverService $service)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $this->service->send(
            toMails: $this->mapContactsToNameEmail($email->getTo()),
            subject: $email->getSubject(),
            body: $email->getHtmlBody(),
            ccMails: $this->mapContactsToNameEmail($email->getCc()),
            bccMails: $this->mapContactsToNameEmail($email->getBcc()),
        );
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'microsoft';
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