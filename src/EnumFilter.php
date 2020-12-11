<?php

namespace SimpleSquid\Nova\Fields\Enum;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Nova;

class EnumFilter extends Filter
{
    public $component = 'select-filter';

    protected $column;

    protected $class;

    public function __construct($column, $class, $name = null)
    {
        $this->column = $column;
        $this->class = $class;

        if (! is_null($name)) {
            $this->name = $name;
        }
    }

    public function name()
    {
        return $this->name ?: Nova::humanize($this->column);
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->where($this->column, $value);
    }

    public function options(Request $request)
    {
        return array_flip($this->class::asSelectArray());
    }
}
