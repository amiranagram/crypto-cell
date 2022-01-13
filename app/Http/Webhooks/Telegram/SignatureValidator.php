<?php

namespace App\Http\Webhooks\Telegram;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator as SignatureValidatorContract;
use Spatie\WebhookClient\WebhookConfig;

class SignatureValidator implements SignatureValidatorContract
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return true;
    }
}
