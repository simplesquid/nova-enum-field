<?php
/**
 * Copyright (c) 2019 Matthew Poulter. All rights reserved.
 */

namespace SimpleSquid\Nova\Fields\Enum;

use BenSampo\Enum\Rules\EnumValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Enum extends Select
{
    /**
     * Setup the Enum field with the Enum class.
     *
     * @param  string  $class
     *
     * @return $this
     */
    public function attachEnum($class)
    {
        return $this->options(call_user_func($class . '::toSelectArray'))
                    ->rules('required', new EnumValue($class, false))
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
}
