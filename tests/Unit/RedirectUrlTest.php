<?php

use YasserBenaioua\Chargily\Chargily;

it('throws exception when api key not provided', function () {
    //config()->set('chargily.key', 'api_MmrIjunBOQuJIx9VtJscf5qWNpePJdjIqwHtvjo7unluwO5dpTQnjkq1jesfqtRu');
    //config()->set('chargily.secret', 'secret_fb6efd31d2df6556b109ddb98a3152106a7814bcf727be5238e718b74b59fafc');
    // config()->set('chargily.urls.back_url', 'http://laravel.com');
    // config()->set('chargily.urls.webhook_url', 'http://laravel.com');

   $epay_config = config('chargily');
    $chargily = new Chargily([
        //credentials
        // 'api_key' => $epay_config['key'],
        'api_secret' => $epay_config['secret'],
        //urls
        'urls' => $epay_config['urls'],
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

        ]
    ]);

    // get redirect url
    $redirectUrl = $chargily->getRedirectUrl();
    // dd($chargily->getRedirectUrl());
})->throws(Exception::class, 'The api key field is required.');
