<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class IntegerFieldTest extends TestCase
{
    private $field;

    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->field = Enum::make('Enum')->attach(IntegerEnum::class);

        $this->model = IntegerModel::create(['enum' => IntegerEnum::Moderator]);
    }

    /** @test */
    public function it_resolves_enum_value()
    {
        $this->field->resolve($this->model);

        $this->assertSame(IntegerEnum::Moderator, $this->field->value);
    }

    /** @test */
    public function it_displays_enum_description()
    {
        $this->field->resolveForDisplay($this->model);

        $this->assertSame(IntegerEnum::Moderator()->description, $this->field->displayedAs);
    }

    /** @test */
    public function it_fills_database_with_enum_value()
    {
        $request = new NovaRequest();
        $request->query->add(['enum' => IntegerEnum::Subscriber]);

        $this->field->fill($request, $this->model);

        $this->assertDatabaseHas('example_models', ['enum' => IntegerEnum::Moderator]);

        $this->model->save();

        $this->assertDatabaseHas('example_models', ['enum' => IntegerEnum::Subscriber]);

        $this->assertDatabaseMissing('example_models', ['enum' => IntegerEnum::Moderator]);
    }
}
