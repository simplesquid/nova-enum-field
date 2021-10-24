<?php

namespace SimpleSquid\Nova\Fields\Enum;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class FlaggedEnum extends BooleanGroup
{
    public $noValueText = 'None';

    public function attach($class)
    {
        $this->resolveUsing(
            function ($value) use ($class) {
                if (! $value instanceof \BenSampo\Enum\FlaggedEnum) {
                    return $value;
                }

                return collect($value->getValues())->mapWithKeys(function ($flag) use ($value) {
                    return [$flag => $value->hasFlag($flag)];
                })->except($class::None)->all();
            }
        );

        $this->fillUsing(
            function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($class) {
                if ($request->exists($requestAttribute)) {
                    $value = json_decode($request[$requestAttribute], true);

                    $model->{$attribute} = $class::flags(array_keys(array_filter($value)));
                }
            }
        );

        return $this->options(Arr::except($class::asSelectArray(), $class::None));
    }
}
