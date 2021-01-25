<?php

namespace SimpleSquid\Nova\Fields\Enum;

use BenSampo\Enum\Traits\QueriesFlaggedEnums;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Nova;

class EnumFilter extends Filter
{
    use QueriesFlaggedEnums;

    protected $column;

    protected $class;

    protected $flagged;

    public function __construct($column, $class)
    {
        $this->column = $column;
        $this->class = $class;

        $this->flagged = is_subclass_of($this->class, \BenSampo\Enum\FlaggedEnum::class);
    }

    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name ?: Nova::humanize($this->column);
        }

        $this->name = $name;
        return $this;
    }

    public function apply(Request $request, $query, $value)
    {
        if ($this->flagged && $value != $this->class::None) {
            return $this->scopeHasFlag($query, $this->column, $value);
        }

        return $query->where($this->column, $value);
    }

    public function options(Request $request)
    {
        return array_flip($this->class::asSelectArray());
    }
}
