<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;

class FieldTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->model = IntegerModel::create(['enum' => IntegerEnum::Moderator()]);
    }

    /** @test */
    public function it_resolves_correct_value()
    {
        $field = Enum::make('Enum')->attach(IntegerEnum::class);

        $field->resolve($this->model);

        $this->assertSame(1, $field->value);
    }

    /** @test */
    public function it_displays_correct_description()
    {
        $field = Enum::make('Enum')->attach(IntegerEnum::class);

        $field->resolveForDisplay($this->model);

        $this->assertSame('Moderator', $field->value);
    }

    /** @test */
    public function it_fills_database_with_enum_value()
    {
        $field = Enum::make('Enum')->attach(IntegerEnum::class);

        $request = new NovaRequest();
        $request->query->add(['enum' => IntegerEnum::Subscriber()]);

        $field->fill($request, $this->model);

        $this->model->save();

        $this->assertDatabaseHas('example_models', ['enum' => 2]);
        $this->assertDatabaseMissing('example_models', ['enum' => 1]);
    }
}
