
# A laravel package for chargily epay gateway

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yasserbenaioua/chargily-epay-laravel.svg?style=flat-square)](https://packagist.org/packages/yasserbenaioua/chargily-epay-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/yasserbenaioua/chargily-epay-laravel/run-tests?label=tests)](https://github.com/yasserbenaioua/chargily-epay-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/yasserbenaioua/chargily-epay-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/yasserbenaioua/chargily-epay-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/yasserbenaioua/chargily-epay-laravel.svg?style=flat-square)](https://packagist.org/packages/yasserbenaioua/chargily-epay-laravel)

## Installation

You can install the package via composer:

```bash
composer require yasserbenaioua/chargily-epay-laravel
```

You must publish the config file with:

```bash
php artisan vendor:publish --provider="YasserBenaioua\Chargily\ChargilyServiceProvider" --tag="chargily-config"
```

This is the contents of the config file that will be published at `config/chargily.php` :

```php
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
```

Next, you must publish the migration with:

```bash
php artisan vendor:publish --provider="YasserBenaioua\Chargily\ChargilyServiceProvider" --tag="chargily-migrations"
```

After the migration has been published, you can create the `chargily_webhook_calls` table by running the migrations:

```bash
php artisan migrate
```
Finally, take care of the routing: At the chargily config file you must configure at what URL Chargily webhook should be sent. In the routes file of your app you must pass that route to the `Route::githubWebhooks` route macro:

```php
Route::chargilyWebhook('webhook-route-configured-at-the-chargily-config-file');
```

Behind the scenes this macro will register a `POST` route to a controller provided by this package. We recommend to put it in the `api.php` routes file, so no session is created when a webhook comes in, and no CSRF token is needed.

Should you, for any reason, have to register the route in your `web.php` routes file, then you must add that route to the `except` array of the `VerifyCsrfToken` middleware:

```php
protected $except = [
    'webhook-route-configured-at-the-chargily-config-file',
];
```

## Usage
Firsly, you may create a payment like this:
```php
use YasserBenaioua\Chargily\Chargily;

$chargily = new Chargily([
    //mode
    'mode' => 'EDAHABIA', //OR CIB
    //payment details
    'payment' => [
        'number' => 'payment-number-from-your-side', // Payment or order number
        'client_name' => 'client name', // Client name
        'client_email' => 'client_email@mail.com', // This is where client receive payment receipt after confirmation
        'amount' => 75, //this the amount must be greater than or equal 75
        'discount' => 0, //this is discount percentage between 0 and 99
        'description' => 'payment-description', // this is the payment description
    ]
]);
```
then use `getRedirectUrl()` methode to get the checkout link:
```php
use YasserBenaioua\Chargily\Chargily;

$chargily = new Chargily([
    //mode
    'mode' => 'EDAHABIA', //OR CIB
    //payment details
    'payment' => [
        'number' => 'payment-number-from-your-side', // Payment or order number
        'client_name' => 'client name', // Client name
        'client_email' => 'client_email@mail.com', // This is where client receive payment receipt after confirmation
        'amount' => 75, //this the amount must be greater than or equal 75
        'discount' => 0, //this is discount percentage between 0 and 99
        'description' => 'payment-description', // this is the payment description
    ]
]);

$redirectUrl = $chargily->getRedirectUrl();
//like : https://epay.chargily.com.dz/checkout/random_token_here

return redirect($redirectUrl);
```

Chargily will sign all requests hitting the webhook url of your app. This package will automatically verify if the signature is valid.

Unless something goes terribly wrong, this package will always respond with a `200` to webhook requests. All webhook requests with a valid signature will be logged in the `chargily_webhook_calls` table. The table has a payload column where the entire payload of the incoming webhook is saved.

If the signature is not valid, the request will not be logged in the `chargily_webhook_calls` table but a `Spatie\WebhookClient\Exceptions\InvalidWebhookSignature` exception will be thrown. If something goes wrong during the webhook request the thrown exception will be saved in the exception column. In that case the controller will send a `500` instead of `200`.

There are two ways this package enables you to handle webhook requests: you can opt to queue a job or listen to the events the package will fire.

To handle webhook requests you can define a job that does the work. Here's an example of such a job:

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;

class HandleChargilyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public ChargilyWebhookCall $webhookCall
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // do your work here

        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}

```

After having created your job you must register it at the `jobs` array in the `chargily.php` config file.

```php
// config/chargily.php

'jobs' => [
    \App\Jobs\HandleChargilyWebhook::class,
],
```

## Deleting processed webhooks

The `YasserBenaioua\Chargily\Models\ChargilyWebhookCall` is `MassPrunable`. To delete all processed webhooks every day you can schedule this command.

```php
$schedule->command('model:prune', [
    '--model' => [\YasserBenaioua\Chargily\Models\ChargilyWebhookCall::class],
])->daily();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Yasser Benaioua](https://github.com/yasserbenaioua)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
