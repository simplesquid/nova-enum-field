<?php
/**
 * Copyright (c) 2019 Matthew Poulter. All rights reserved.
 */

namespace SimpleSquid\Nova\Fields\Enum;

use Laravel\Nova\Fields\Select;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Contracts\Support\Arrayable;
use Laravel\Nova\Http\Requests\NovaRequest;

class Enum extends Select
{
    /**
     * Setup the Enum field with the Enum class.
     *
     * @param  string  $enumClass
     *
     * @return $this
     */
    public function attachEnum($enumClass)
    {
        return $this->options($this->getEnumOptions($enumClass))
                    ->rules('required', new EnumValue($enumClass, false))
                    ->resolveUsing(
                        function ($enum) {
                            return $enum ? $enum->value : null;
                        })
                    ->displayUsing(
                        function ($enum) {
                            return $enum ? $enum->description : null;
                        });
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  NovaRequest  $request
     * @param  string       $requestAttribute
     * @param  object       $model
     * @param  string       $attribute
     *
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }

    protected function getEnumOptions(string $enumClass): array
    {
        // Since laravel-enum v2.2.0, the method has been named 'asSelectArray'
        if (in_array(Arrayable::class, class_implements($enumClass))) {
            return $enumClass::asSelectArray();
        }

        return $enumClass::toSelectArray();
    }
}
