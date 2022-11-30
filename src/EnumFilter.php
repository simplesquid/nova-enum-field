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

    protected $default;

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

    public function key()
    {
        return 'enum_filter_'.$this->column;
    }

    public function options(Request $request)
    {
        return array_flip($this->class::asSelectArray());
    }

    public function default()
    {
        if (isset(func_get_args()[0])) {
            $this->default = is_subclass_of(func_get_args()[0], \BenSampo\Enum\Enum::class) ? func_get_args()[0]->value : func_get_args()[0];

            return $this;
        }

        if (is_null($this->default)) {
            return parent::default();
        }

        return $this->default;
    }
}
