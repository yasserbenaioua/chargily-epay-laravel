<?php

use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;
use YasserBenaioua\Chargily\Jobs\ProcessChargilyWebhookJob;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;

return [

    'key'           => env('CHARGILY_API_KEY', 'api_MmrIjunBOQuJIx9VtJscf5qWNpePJdjIqwHtvjo7unluwO5dpTQnjkq1jesfqtRu'),
    'secret'        => env('CHARGILY_API_SECRET', 'secret_fb6efd31d2df6556b109ddb98a3152106a7814bcf727be5238e718b74b59fafc'),
    'back_url'      => 'https://c85e-41-105-5-5.ngrok.io/back',
    'webhook_url'   => 'https://c85e-41-105-5-5.ngrok.io/chargily/webhook',

    /*
     * You can define the job that should be run when a chargily webhook hits your application
     * here.
     */
    'jobs' => [
        // \App\Jobs\HandleChargilyWebhook::class,
    ],

    /*
     * This model will be used to store all incoming webhooks.
     * It should be or extend `YasserBenaioua\Chargily\Models\ChargilyWebhookCall`
     */
    'model' => ChargilyWebhookCall::class,

    /*
     * When running `php artisan model:prune` all stored Chargily webhook calls
     * that were successfully processed will be deleted.
     *
     * More info on pruning: https://laravel.com/docs/8.x/eloquent#pruning-models
     */
    'prune_webhook_calls_after_days' => 10,

    /*
     * The classname of the job to be used. The class should equal or extend
     * YasserBenaioua\Chargily\ProcessChargilyWebhookJob.
     */
    'job' => ProcessChargilyWebhookJob::class,

    /**
     * This class determines if the webhook call should be stored and processed.
     */
    'profile' => ProcessEverythingWebhookProfile::class,

    /*
     * When disabled, the package will not verify if the signature is valid.
     * This can be handy in local environments.
     */
    'verify_signature' => env('CHARGILY_SIGNATURE_VERIFY', true),
];
