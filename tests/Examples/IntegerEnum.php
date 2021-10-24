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
    public const Administrator = 0;

    public const Moderator = 1;

    public const Subscriber = 2;
}
