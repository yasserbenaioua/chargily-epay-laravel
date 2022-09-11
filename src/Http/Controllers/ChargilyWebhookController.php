<?php

namespace YasserBenaioua\Chargily\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProcessor;
use Symfony\Component\HttpFoundation\Response;
use YasserBenaioua\Chargily\ChargilySignatureValidator;

class ChargilyWebhookController
{
    public function __invoke(Request $request)
    {
        $webhookConfig = new WebhookConfig([
            'name' => 'Chargily',
            'signing_secret' => config('chargily.secret'),
            'signature_header_name' => 'Signature',
            'signature_validator' => ChargilySignatureValidator::class,
            'webhook_profile' => config('chargily.profile'),
            'webhook_model' => config('chargily.model'),
            'process_webhook_job' => config('chargily.job'),
            'store_headers' => [
                'X-GitHub-Event',
                'X-GitHub-Delivery',
            ],
        ]);

        try {
            (new WebhookProcessor($request, $webhookConfig))->process();
        } catch (InvalidWebhookSignature) {
            return response()->json(['message' => 'invalid signature'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['message' => 'ok']);
    }
}
