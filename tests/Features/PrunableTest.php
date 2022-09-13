<?php

use function Spatie\PestPluginTestTime\testTime;
use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;

it('will prune records after the configured amount of days', function () {
    testTime()->freeze();

    config()->set('chargily.prune_webhook_calls_after_days', 5);

    ChargilyWebhookCall::create([
        'name' => 'dummy name',
        'url' => 'https://example.com',
    ]);

    testTime()->addDays(5)->subSecond();
    $this->artisan('model:prune');
    expect(ChargilyWebhookCall::count())->toBe(1);

    testTime()->addSecond();
    $this->artisan('model:prune', [
        '--model' => [ChargilyWebhookCall::class],
    ]);
    expect(ChargilyWebhookCall::count())->toBe(0);
});
