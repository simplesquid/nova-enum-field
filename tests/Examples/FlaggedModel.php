<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Examples;

use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;

class FlaggedModel extends Model
{
    use CastsEnums;

    public $table = 'example_models';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'enum' => FlaggedEnum::class,
    ];
}
