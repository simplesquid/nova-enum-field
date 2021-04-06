<?php

namespace SimpleSquid\Nova\Fields\Enum;

use BenSampo\Enum\Traits\QueriesFlaggedEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Nova;

class EnumBooleanFilter extends BooleanFilter
{
    use QueriesFlaggedEnums;

    protected $column;

    protected $class;

    protected $default;

    protected $flagged;

    protected $scope = 'any';

    public function __construct($column, $class)
    {
        $this->column = $column;
        $this->class = $class;

        $this->flagged = is_subclass_of($this->class, \BenSampo\Enum\FlaggedEnum::class);
    }

    public function filterAnyFlags()
    {
        $this->scope = 'any';

        return $this;
    }

    public function filterAllFlags()
    {
        $this->scope = 'all';

        return $this;
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
        $enums = array_keys(array_filter($value));

        if (empty($enums)) {
            return $query;
        }

        if ($this->flagged && $this->scope === 'all') {
            return $this->scopeHasAllFlags($query, $this->column, $enums);
        }

        return $query->where(
            function ($query) use ($enums) {
                if ($this->flagged) {
                    $query = $this->scopeHasAnyFlags($query, $this->column, $enums);

                    $enums = in_array($this->class::None, $enums) ? [$this->class::None] : [];
                } else {
                    $query->where($this->column, array_shift($enums));
                }

                foreach ($enums as $enum) {
                    $query->orWhere($this->column, $enum);
                }
            }
        );
    }

    public function options(Request $request)
    {
        if ($this->flagged && $this->scope === 'all') {
            return array_flip(Arr::except($this->class::asSelectArray(), $this->class::None));
        }

        return array_flip($this->class::asSelectArray());
    }

    public function default()
    {
        if (isset(func_get_args()[0])) {
            $this->default = collect(is_array(func_get_args()[0]) ? func_get_args()[0] : [func_get_args()[0]])
                ->map(function ($value, $key) {
                    return is_subclass_of($value, \BenSampo\Enum\Enum::class) ? $value->value : $value;
                })->all();

            return $this;
        }

        if (is_null($this->default)) {
            return parent::default();
        }

        return collect($this->default)->mapWithKeys(function ($option) {
            return [$option => true];
        })->all() + parent::default();
    }
}
