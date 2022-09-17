<?php

use Illuminate\Support\Str;
use YasserBenaioua\Chargily\Chargily;
use Illuminate\Validation\ValidationException;
use Mockery\MockInterface;
use YasserBenaioua\Chargily\RedirectUrl;

it('will return the redirect url', function () {
    $this->instance(
        RedirectUrl::class,
        Mockery::mock(RedirectUrl::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRedirectUrl')->andReturn(checkoutUrl());
        })
    );

    $redirectUrlClass = $this->app->make(RedirectUrl::class);

    $redirectUrl = $redirectUrlClass->getRedirectUrl();
    $token = Str::after($redirectUrl, 'checkout/');
    $tokenLength = Str::of($token)->length();

    expect($redirectUrl)->toStartWith('https://epay.chargily.com.dz');
    expect($tokenLength)->toEqual(64);
});

it('will throw an exception if validation failed', function () {
    $this->withoutExceptionHandling();

    config()->set('chargily.back_url', 'invalid-url');

    $chargily = new Chargily(configs());

    $chargily->getRedirectUrl();
})->throws(ValidationException::class);
