<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\ExampleIntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\ExampleModel;

class ModelTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Tests\Examples\ExampleModel */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = ExampleModel::create(['enum' => ExampleIntegerEnum::Moderator()]);
    }

    /** @test */
    public function field_resolves_correct_value()
    {
        $field = Enum::make('Enum')->attachEnum(ExampleIntegerEnum::class);

        $field->resolve($this->model);

        $this->assertSame(1, $field->value);
    }

    /** @test */
    public function field_displays_correct_description()
    {
        $field = Enum::make('Enum')->attachEnum(ExampleIntegerEnum::class);

        $field->resolveForDisplay($this->model);

        $this->assertSame('Moderator', $field->value);
    }

    /** @test */
    public function field_fills_database_with_enum_value()
    {
        $field = Enum::make('Enum')->attachEnum(ExampleIntegerEnum::class);

        $this->model->enum = ExampleIntegerEnum::Subscriber();

        $field->fill(new NovaRequest(), $this->model);

        $this->assertDatabaseHas('example_models', ['enum' => 2]);
        $this->assertDatabaseMissing('example_models', ['enum' => 1]);
    }
}
