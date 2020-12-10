# An enum field for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/simplesquid/nova-enum-field.svg?style=flat-square)](https://packagist.org/packages/simplesquid/nova-enum-field)
[![Build Status](https://img.shields.io/travis/simplesquid/nova-enum-field/master.svg?style=flat-square)](https://travis-ci.org/simplesquid/nova-enum-field)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/simplesquid/nova-enum-field.svg?style=flat-square)](https://packagist.org/packages/simplesquid/nova-enum-field)

Laravel Nova field to add enums to resources. This field uses the [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum) package, so make sure to check out the installation instructions there first.

![Screenshot of the enum field](https://github.com/simplesquid/nova-enum-field/raw/master/docs/screenshot.png)

## Installation

You can install this package in a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require simplesquid/nova-enum-field
```

## Setup

This package requires that you use Attribute Casting in your models. From the docs at [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum#attribute-casting), this can be done like so:

```php
use BenSampo\Enum\Traits\CastsEnums;
use BenSampo\Enum\Tests\Enums\UserType;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use CastsEnums;

    protected $casts = [
        'user_type' => UserType::class,
    ];
}
```

## Usage

You can use the `Enum` field in your Nova resource like so:

```php
namespace App\Nova;

use BenSampo\Enum\Tests\Enums\UserType;
use SimpleSquid\Nova\Fields\Enum\Enum;

class Example extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            Enum::make('User Type')->attachEnum(UserType::class),

            // ...
        ];
    }
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Matthew Poulter](https://github.com/mdpoulter)
- [Ben Sampson](https://github.com/BenSampo)
- [atymic](https://github.com/atymic)
- [Robin D'Arcy](https://github.com/rdarcy1)
- [All Contributors](../../contributors)

Package skeleton based on [spatie/skeleton-php](https://github.com/spatie/skeleton-php).

## About us

SimpleSquid is a small web development and design company based in Cape Town, South Africa.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
