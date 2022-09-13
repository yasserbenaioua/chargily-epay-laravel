<?php

use Illuminate\Support\Str;
use YasserBenaioua\Chargily\Chargily;
use Illuminate\Validation\ValidationException;

it('will return the redirect url', function () {
    $chargily = new Chargily([
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
    ]);

    $redirectUrl = $chargily->getRedirectUrl();
    $token = Str::after($redirectUrl, 'checkout/');
    $tokenLength = Str::of($token)->length();

    expect($redirectUrl)->toStartWith('https://epay.chargily.com.dz');
    expect($tokenLength)->toEqual(64);
});

it('will throw an exception if validation failed', function () {
    $this->withoutExceptionHandling();

    config()->set('chargily.back_url', 'invalid-url');

    $chargily = new Chargily([
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
    ]);

    $chargily->getRedirectUrl();
})->throws(ValidationException::class);
