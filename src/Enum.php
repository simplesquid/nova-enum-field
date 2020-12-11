<?php

namespace SimpleSquid\Nova\Fields\Enum;

use BenSampo\Enum\Rules\EnumValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Enum extends Select
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->resolveUsing(
            function ($value) {
                return $value instanceof \BenSampo\Enum\Enum ? $value->value : $value;
            }
        );

        $this->displayUsing(
            function ($value) {
                return $value instanceof \BenSampo\Enum\Enum ? $value->description : $value;
            }
        );

        $this->fillUsing(
            function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                if ($request->exists($requestAttribute)) {
                    $model->{$attribute} = $request[$requestAttribute];
                }
            }
        );
    }

    public function attach($class)
    {
        return $this->options($class::asSelectArray())
                    ->rules('required', new EnumValue($class, false));
    }

    /**
     * @deprecated deprecated since version 2.3
     */
    public function attachEnum($class)
    {
        return $this->attach($class);
    }
}
