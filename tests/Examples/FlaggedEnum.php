<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Examples;

use BenSampo\Enum\FlaggedEnum as BaseFlaggedEnum;

/**
 * @method static static ReadComments()
 * @method static static WriteComments()
 * @method static static EditComments()
 * @method static static None()
 */
final class FlaggedEnum extends BaseFlaggedEnum
{
    const ReadComments      = 1 << 0;
    const WriteComments     = 1 << 1;
    const EditComments      = 1 << 2;
}
