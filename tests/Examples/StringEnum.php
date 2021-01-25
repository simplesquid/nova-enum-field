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
    const Administrator = 'administrator';

    const Moderator = 'moderator';

    const Subscriber = 'subscriber';
}
