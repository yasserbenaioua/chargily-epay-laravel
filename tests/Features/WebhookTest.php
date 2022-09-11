<?php

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use YasserBenaioua\Chargily\Exceptions\InvalidConfig;
use YasserBenaioua\Chargily\Tests\TestClasses\HandleChargilyWebhookJob;
use YasserBenaioua\Chargily\Tests\TestClasses\HandleChargilyWebhookNumberTwoJob;

beforeEach(function () {
    Route::chargilyWebhook('webhooks');

    config()->set('chargily.secret', 'test_secret');

    Bus::fake([
        HandleChargilyWebhookJob::class,
        HandleChargilyWebhookNumberTwoJob::class
    ]);
});

it('will accept a webhook with a valid signature', function () {
    $payload = ['a' => 1];

    $this
        ->postJson('webhooks', $payload, addSignature($payload))
        ->assertSuccessful();
});

it('will not accept a webhook with an invalid signature', function () {
    $headers = [
        'Signature' => 'invalid-signature',
    ];

    $payload = ['a' => 1];

    $this
        ->postJson('webhooks', $payload, $headers)
        ->assertForbidden();
});

it('will accept a webhook with an invalid signature when validation is turned off', function () {
    config()->set('chargily.verify_signature', false);

    $headers = [
        'Signature' => 'invalid-signature',
    ];

    $payload = ['a' => 1];

    $this
        ->postJson('webhooks', $payload, $headers)
        ->assertSuccessful();
});

it('will dispatch a handle job', function () {
    config()->set('chargily.jobs', [
        HandleChargilyWebhookJob::class,
        HandleChargilyWebhookNumberTwoJob::class,
    ]);

    $payload = [];

    $this
        ->postJson('webhooks', $payload, addSignature($payload))
        ->assertSuccessful();

    Bus::assertDispatched(HandleChargilyWebhookJob::class);
    Bus::assertDispatched(HandleChargilyWebhookNumberTwoJob::class);
});

it('will throw an exception if secret key not set', function () {
    $this->withoutExceptionHandling();

    config()->set('chargily.secret', '');

    $payload = [];

    $this->postJson('webhooks', $payload, addSignature($payload));

})->throws(InvalidConfig::class);
