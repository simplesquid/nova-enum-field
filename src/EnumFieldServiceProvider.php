<?php

namespace SimpleSquid\Nova\Fields\Enum;

use BenSampo\Enum\Enum;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\ServiceProvider;

class EnumFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! in_array(Arrayable::class, class_implements(Enum::class))) {
            Enum::macro('asSelectArray', function () {
                return self::toSelectArray();
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
