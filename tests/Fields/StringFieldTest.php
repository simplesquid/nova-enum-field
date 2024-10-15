<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class StringFieldTest extends TestCase
{
    private $field;

    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app, 'string');

        $this->field = Enum::make('Enum')->attach(StringEnum::class);

        $this->model = StringModel::create(['enum' => StringEnum::Moderator]);
    }

    /** @test */
    public function it_resolves_enum_value()
    {
        $this->field->resolve($this->model);

        $this->assertSame(StringEnum::Moderator, $this->field->value);
    }

    /** @test */
    public function it_displays_enum_description()
    {
        $this->field->resolveForDisplay($this->model);

        $this->assertSame(StringEnum::Moderator()->description, $this->field->displayedAs ?? $this->field->value);
    }

    /** @test */
    public function it_fills_database_with_enum_value()
    {
        $request = new NovaRequest;
        $request->query->add(['enum' => StringEnum::Subscriber]);

        $this->field->fill($request, $this->model);

        $this->assertDatabaseHas('example_models', ['enum' => StringEnum::Moderator]);

        $this->model->save();

        $this->assertDatabaseHas('example_models', ['enum' => StringEnum::Subscriber]);

        $this->assertDatabaseMissing('example_models', ['enum' => StringEnum::Moderator]);
    }
}
