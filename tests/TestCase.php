<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->artisan('migrate:fresh');

        $app['db']->connection()->getSchemaBuilder()->create('example_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enum');
        });
    }
}
