<?php

use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;

return [

    /*
    * Chargily Api Key
    * You can found it on your epay.chargily.com.dz Dashboard.
    */
    'key' => env('CHARGILY_API_KEY'),

    /*
    * Chargily Api Secret
    * Your Chargily secret, which is used to verify incoming requests from Chargily.
    * You can found it on your epay.chargily.com.dz Dashboard.
    */
    'secret' => env('CHARGILY_API_SECRET'),

    /*
    * This is where client redirected after payment processing.
    */
    'back_url' => 'valid-url-to-redirect-after-payment',

    /*
    * This is where you receive payment informations.
    */
    'webhook_url' => 'valid-url-to-receive-payment-informations',

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
     * When disabled, the package will not verify if the signature is valid.
     * This can be handy in local environments.
     */
    'verify_signature' => env('CHARGILY_SIGNATURE_VERIFY', true),
];
