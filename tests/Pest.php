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

function configs()
{
    return [
        //mode
        'mode' => 'EDAHABIA', //OR CIB

        //payment details
        'payment' => [
            'number' => rand(), // Payment or order number
            'client_name' => 'client name', // Client name
            'client_email' => 'client_email@mail.com', // This is where client receive payment receipt after confirmation
            'amount' => 175, //this the amount must be greater than or equal 75
            'discount' => 0, //this is discount percentage between 0 and 99
            'description' => 'payment-description', // this is the payment description
        ],
    ];
}

function checkoutUrl()
{
    return 'https://epay.chargily.com.dz/checkout/9e679d420a94fdaaf5b8fa322d4996f3a511a36d194b3cf295beff337a23f4bc';
}
