<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use BenSampo\Enum\Enum;

/**
 * @method static Administrator()
 * @method static Moderator()
 */
class ExampleStringEnum extends Enum
{
    const Administrator = 'administrator';
    const Moderator = 'moderator';
}
