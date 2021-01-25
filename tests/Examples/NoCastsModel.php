<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Examples;

use Illuminate\Database\Eloquent\Model;

class NoCastsModel extends Model
{
    public $table = 'example_models';

    protected $guarded = [];

    public $timestamps = false;
}
