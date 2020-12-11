<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use SimpleSquid\Nova\Fields\Enum\EnumFieldServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            EnumFieldServiceProvider::class,
        ];
    }

    protected function setUpDatabase($app)
    {
        $this->artisan('migrate:fresh');

        $app['db']->connection()->getSchemaBuilder()->create('example_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enum');
        });
    }
}
