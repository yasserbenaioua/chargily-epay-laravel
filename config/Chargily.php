<?php

return [
    'key' => 'your-api-key', // you can you found it on your epay.chargily.com.dz Dashboard
    'secret' => 'your-api-secret', // you can you found it on your epay.chargily.com.dz Dashboard

    'urls' => [
        'back_url' => "valid-url-to-redirect-after-payment", // this is where client redirected after payment processing
        'webhook_url' => "valid-url-to-receive-payment-informations", // this is where you receive payment informations
    ],
];
