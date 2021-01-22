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

    protected function setUpDatabase($app, $type = 'integer')
    {
        $this->artisan('migrate:fresh');

        $app['db']->connection()->getSchemaBuilder()->create('example_models', function (Blueprint $table) use ($type) {
            $table->increments('id');
            $table->$type('enum');
        });
    }
}
