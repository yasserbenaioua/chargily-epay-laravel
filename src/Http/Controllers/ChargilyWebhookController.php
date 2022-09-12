<?php

namespace YasserBenaioua\Chargily\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProcessor;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;
use Symfony\Component\HttpFoundation\Response;
use YasserBenaioua\Chargily\Validators\ChargilySignatureValidator;
use YasserBenaioua\Chargily\Jobs\ProcessChargilyWebhookJob;

class ChargilyWebhookController
{
    public function __invoke(Request $request)
    {
        $webhookConfig = new WebhookConfig([
            'name' => 'Chargily',
            'signing_secret' => config('chargily.secret'),
            'signature_header_name' => 'Signature',
            'signature_validator' => ChargilySignatureValidator::class,
            'webhook_profile' => ProcessEverythingWebhookProfile::class,
            'webhook_model' => config('chargily.model'),
            'process_webhook_job' => ProcessChargilyWebhookJob::class
        ]);

        try {
            (new WebhookProcessor($request, $webhookConfig))->process();
        } catch (InvalidWebhookSignature) {
            return response()->json(['message' => 'invalid signature'], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['message' => 'ok']);
    }
}
