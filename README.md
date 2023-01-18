# Redirector SEO for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/the-3labs-team/nova-redirector-seo.svg?style=flat-square)](https://packagist.org/packages/the-3labs-team/nova-redirector-seo)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/the-3labs-team/nova-redirector-seo/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/the-3labs-team/nova-redirector-seo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/the-3labs-team/nova-redirector-seo/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/the-3labs-team/nova-redirector-seo/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/the-3labs-team/nova-redirector-seo.svg?style=flat-square)](https://packagist.org/packages/the-3labs-team/nova-redirector-seo)

## Installation

You can install the package via composer:

```bash
composer require the-3labs-team/nova-redirector-seo
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="nova-redirector-seo-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="nova-redirector-seo-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="nova-redirector-seo-views"
```

## Usage

```php
$novaRedirectorSeo = new The3LabsTeam\NovaRedirectorSeo();
echo $novaRedirectorSeo->echoPhrase('Hello, The3LabsTeam!');
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

- [Stefano Novelli](https://github.com/The-3Labs-Team)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
