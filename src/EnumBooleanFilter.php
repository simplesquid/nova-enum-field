<?php

namespace SimpleSquid\Nova\Fields\Enum;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Nova;

class EnumBooleanFilter extends BooleanFilter
{
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
        $enums = array_keys(array_filter($value));

        return empty($enums) ? $query : $query->where(
            function ($query) use ($enums) {
                $query->where($this->column, array_shift($enums));

                foreach ($enums as $enum) {
                    $query->orWhere($this->column, $enum);
                }
            }
        );
    }

    public function options(Request $request)
    {
        return array_flip($this->class::asSelectArray());
    }
}
