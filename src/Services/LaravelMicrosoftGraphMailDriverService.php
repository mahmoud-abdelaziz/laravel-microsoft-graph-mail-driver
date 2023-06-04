<?php

namespace Mz\LaravelMicrosoftGraphMailDriver\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class LaravelMicrosoftGraphMailDriverService
{
    private $tenant_id;
    private $client_id;
    private $client_secret;
    private $user_id;

    public function __construct()
    {
        $this->tenant_id = Config::get("mail.mailers.microsoft.tenant_id");
        $this->client_id = Config::get("mail.mailers.microsoft.client_id");
        $this->client_secret = Config::get("mail.mailers.microsoft.client_secret");
        $this->user_id = Config::get("mail.mailers.microsoft.user_id");
    }

    public function generateAccessToken()
    {
        $tenant_id = $this->tenant_id;
        $url = "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/token";
        $response = Http::asForm()->post($url, [
            "client_id" => $this->client_id,
            "grant_type" => "client_credentials",
            "client_secret" => $this->client_secret,
            "scope" => "https://graph.microsoft.com/.default",
        ]);
        $access_token = $response->json("access_token");
        $expires_in = $response->json("expires_in");
        Cache::put("microsoft_mail_access_token", $access_token, $expires_in - 10);
        return $response->json("access_token");
    }

    public function getAccessToken()
    {
        $cached_value = Cache::get("microsoft_mail_access_token");
        return $cached_value ?? $this->generateAccessToken();
    }

    public function send(array $toMails, string $subject, string $body, array $ccMails = [], array $bccMails = [])
    {
        $user_id = $this->user_id;
        $url = "https://graph.microsoft.com/v1.0/users/$user_id/sendMail";
        $accessToken = $this->getAccessToken();
        $response = Http::asJson()->withHeaders([
            "Authorization" => "Bearer $accessToken",
        ])->post($url, [
            "message" => [
                "subject" => $subject,
                "body" => [
                    "contentType" => "Html",
                    "content" => $body
                ],
                "toRecipients" => $this->formatMails($toMails),
                "bccRecipients" => $this->formatMails($bccMails),
                "ccRecipients" => $this->formatMails($ccMails),
            ],
            "saveToSentItems" => false
        ]);
        return $response->status();
    }

    private function formatMails(array $list)
    {
        return array_map(fn($mail) => (
        ["emailAddress" => [
            "address" => $mail
        ]]
        ), $list);

    }
}