# An enum field for Laravel Nova applications
[![Latest Version](https://img.shields.io/github/release/simplesquid/nova-enum-field.svg?style=flat-square)](https://github.com/simplesquid/nova-enum-field/releases)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/simplesquid/nova-enum-field.svg?style=flat-square)](https://packagist.org/packages/simplesquid/nova-vend)

A Laravel Nova field to add enums to resources. This field uses the [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum) package, so make sure to check out the installation instructions there first.

![Screenshot of the enum field](https://github.com/simplesquid/nova-enum-field/raw/master/img/screenshot.png)

Contributions, issues and suggestions are very much welcome.

## Installation

You can install this package in a Laravel app that uses  [Nova](https://nova.laravel.com) via composer:

```bash
composer require simplesquid/nova-enum-field
```

## Usage

You can use the `Enum` field in your Nova resource like so:

```php
namespace App\Nova;

use App\Enums\UserRole;
use SimpleSquid\Nova\Fields\Enum\Enum;

class User extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            Enum::make('Role')->attachEnum(UserRole::class),

            // ...
        ];
    }
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md).

## Credits

- [Matthew Poulter](https://github.com/mdpoulter)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.