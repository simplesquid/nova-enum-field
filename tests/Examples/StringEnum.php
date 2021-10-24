<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Examples;

use BenSampo\Enum\Enum;

/**
 * @method static Administrator()
 * @method static Moderator()
 * @method static Subscriber()
 */
class StringEnum extends Enum
{
    public const Administrator = 'administrator';

    public const Moderator = 'moderator';

    public const Subscriber = 'subscriber';
}
