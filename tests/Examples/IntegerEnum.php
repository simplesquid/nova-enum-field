<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Examples;

use BenSampo\Enum\Enum;

/**
 * @method static Administrator()
 * @method static Moderator()
 * @method static Subscriber()
 */
class IntegerEnum extends Enum
{
    const Administrator = 0;

    const Moderator = 1;

    const Subscriber = 2;
}
