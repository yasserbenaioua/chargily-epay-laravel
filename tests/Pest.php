<?php

use YasserBenaioua\Chargily\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function addSignature(array $payload = [], array $headers = []): array
{
    $signingSecret = config('chargily.secret');

    $signature = hash_hmac('sha256', json_encode($payload), $signingSecret);

    $headers['Signature'] = "sha256={$signature}";

    return $headers;
}
