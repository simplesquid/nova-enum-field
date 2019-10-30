<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use BenSampo\Enum\Enum;

/**
 * @method static Administrator()
 * @method static Moderator()
 */
class ExampleEnum extends Enum
{
    const Administrator = 0;
    const Moderator = 1;
}
