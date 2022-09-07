<?php

use YasserBenaioua\Chargily\Chargily;

it('gets the redirect url', function () {
    $epay_config = config('chargily');

    $chargily = new Chargily([
        //urls
        'urls' => [
            'back_url' => $epay_config['back_url'],
            'webhook_url' => $epay_config['webhook_url'],
        ],
        //mode
        'mode' => 'EDAHABIA', //OR CIB
        //payment details
        'payment' => [
            'number' => '1246498', // Payment or order number
            'client_name' => 'client name', // Client name
            'client_email' => 'client_email@mail.com', // This is where client receive payment receipt after confirmation
            'amount' => 75, //this the amount must be greater than or equal 75
            'discount' => 0, //this is discount percentage between 0 and 99
            'description' => 'payment-description', // this is the payment description

        ],
    ]);

    $redirectUrl = $chargily->getRedirectUrl();

    expect($redirectUrl)->toStartWith('https://epay.chargily.com.dz');
});
