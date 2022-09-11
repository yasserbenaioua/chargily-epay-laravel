
# A laravel package for chargily epay gateway

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yasserbenaioua/chargily-epay-laravel.svg?style=flat-square)](https://packagist.org/packages/yasserbenaioua/chargily-epay-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/yasserbenaioua/chargily-epay-laravel/run-tests?label=tests)](https://github.com/yasserbenaioua/chargily-epay-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/yasserbenaioua/chargily-epay-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/yasserbenaioua/chargily-epay-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/yasserbenaioua/chargily-epay-laravel.svg?style=flat-square)](https://packagist.org/packages/yasserbenaioua/chargily-epay-laravel)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require yasserbenaioua/chargily-epay-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="chargily-epay-laravel-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="chargily"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="chargily-epay-laravel-views"
```

## Usage

```php
$chargily = new YasserBenaioua\Chargily();
echo $chargily->echoPhrase('Hello, YasserBenaioua!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yasser Benaioua](https://github.com/yasserbenaioua)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
