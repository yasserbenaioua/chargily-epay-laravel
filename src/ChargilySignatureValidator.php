<?php

namespace YasserBenaioua\Chargily;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class ChargilySignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        if (! config('chargily.verify_signature')) {
            return true;
        }

        $signatureHeaderContent = $request->header($config->signatureHeaderName);

        $signature = Str::after($signatureHeaderContent, 'sha256=');

        if (! $signature) {
            return false;
        }

        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            return false;
        }
        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);

        return hash_equals($signature, $computedSignature);
    }
}
