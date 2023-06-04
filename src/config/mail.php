<?php
return [
  "microsoft" => [
      "transport" => "microsoft",
      "tenant_id" => env("MAIL_MICROSOFT_TENANT_ID"),
      "client_id" => env("MAIL_MICROSOFT_CLIENT"),
      "client_secret" => env("MAIL_MICROSOFT_CLIENT_SECRET"),
      "user_id" => env("MAIL_USERNAME"),
  ],
];