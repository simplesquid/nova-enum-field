# An enum field for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/simplesquid/nova-enum-field.svg?style=flat-square)](https://packagist.org/packages/simplesquid/nova-enum-field)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/simplesquid/nova-enum-field/Run%20tests?label=tests)](https://github.com/simplesquid/nova-enum-field/actions?query=workflow%3A"Run+tests"+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/simplesquid/nova-enum-field/Check%20&%20fix%20styling?label=code%20style)](https://github.com/simplesquid/nova-enum-field/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/simplesquid/nova-enum-field.svg?style=flat-square)](https://packagist.org/packages/simplesquid/nova-enum-field)

Laravel Nova field to add enums to resources. This field uses the [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum) package, so make sure to check out the installation instructions there first.

![Screenshot of the enum field](https://github.com/simplesquid/nova-enum-field/raw/main/docs/screenshot.png)

## Installation

You can install this package in a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require simplesquid/nova-enum-field
```

## Setup

It is strongly recommended that you use Attribute Casting in your models. From the docs at [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum#attribute-casting), this can be done like this:

```php
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    protected $casts = [
        'user_type' => UserType::class,
    ];
}
```

## Usage

You can use the `Enum` field in your Nova resource like this:

```php
namespace App\Nova;

use App\Enums\UserType;
use SimpleSquid\Nova\Fields\Enum\Enum;

class Example extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            Enum::make('User Type')->attach(UserType::class),

            // ...
        ];
    }
}
```

### Flagged Enums

You can use the `FlaggedEnum` field in your Nova resource like this (see [Flagged/Bitwise Enum](https://github.com/BenSampo/laravel-enum#flaggedbitwise-enum) setup):

```php
namespace App\Nova;

use App\Enums\UserPermissions;
use SimpleSquid\Nova\Fields\Enum\FlaggedEnum;

class Example extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            FlaggedEnum::make('User Permissions')->attach(UserPermissions::class),

            // ...
        ];
    }
}
```

### Filters

If you would like to use the provided Nova Select filter (which is compatible with both the `Enum` and `FlaggedEnum` fields), you can include it like this:

```php
namespace App\Nova;

use App\Enums\UserPermissions;
use App\Enums\UserType;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;

class Example extends Resource
{
    // ...

    public function filters(Request $request)
    {
        return [
            new EnumFilter('user_type', UserType::class),
            
            new EnumFilter('user_permissions', UserPermissions::class),
            
            // With optional filter name:
            (new EnumFilter('user_type', UserType::class))
                ->name('Type of user'),
                
             // With optional default value:
            (new EnumFilter('user_type', UserType::class))
                ->default(UserType::Administrator),
        ];
    }
}
```

Alternatively, you may wish to use the provided Nova Boolean filter (which is also compatible with both the `Enum` and `FlaggedEnum` fields):

```php
namespace App\Nova;

use App\Enums\UserPermissions;
use App\Enums\UserType;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;

class Example extends Resource
{
    // ...

    public function filters(Request $request)
    {
        return [
            new EnumBooleanFilter('user_type', UserType::class),
            
            new EnumBooleanFilter('user_permissions', UserPermissions::class),
            
            // With optional filter name:
            (new EnumBooleanFilter('user_type', UserType::class))
                ->name('Type of user'),
                
            // With optional default values:
            (new EnumBooleanFilter('user_type', UserType::class))
                ->default([
                    UserType::Administrator,
                    UserType::Moderator,
                ]),
            
            // When filtering a FlaggedEnum, it will default to filtering
            // by ANY flags, however you may wish to filter by ALL flags:
            (new EnumBooleanFilter('user_permissions', UserPermissions::class))
                ->filterAllFlags(),
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
- [Antoni Siek](https://github.com/ImJustToNy)
- [All Contributors](../../contributors)

Package skeleton based on [spatie/skeleton-php](https://github.com/spatie/skeleton-php).

## About us

SimpleSquid is a small web development and design company based in Valkenburg, Netherlands.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
