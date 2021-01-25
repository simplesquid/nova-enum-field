<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\FlaggedEnum as FlaggedEnumField;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FlaggedFieldTest extends TestCase
{
    private $field;

    private $model;

    private $values = [
        FlaggedEnum::ReadComments  => true,
        FlaggedEnum::WriteComments => true,
        FlaggedEnum::EditComments  => false,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->field = FlaggedEnumField::make('Enum')->attach(FlaggedEnum::class);

        $this->model = FlaggedModel::create(['enum' => FlaggedEnum::None]);
    }

    /** @test */
    public function it_starts_with_no_options()
    {
        $field = FlaggedEnumField::make('Enum');

        $this->assertEmpty($field->options);
    }

    /** @test */
    public function it_allows_an_enum_to_be_attached()
    {
        $this->assertNotEmpty($this->field->options);
    }

    /** @test */
    public function it_has_no_value_text()
    {
        $this->assertSame('None', $this->field->noValueText);
    }

    /** @test */
    public function it_displays_enum_options()
    {
        $this->assertCount(count(FlaggedEnum::getValues()) - 1, $this->field->options);

        foreach (FlaggedEnum::getValues() as $enum) {
            if ($enum === FlaggedEnum::None) {
                continue;
            }

            $this->assertContains([
                                      'label' => FlaggedEnum::getDescription($enum),
                                      'name'  => $enum
                                  ], $this->field->options);
        }
    }

    /** @test */
    public function it_resolves_enum_values()
    {
        $this->field->resolve($this->model);

        $this->assertCount(count(FlaggedEnum::getValues()) - 1, $this->field->value);

        foreach (array_keys($this->values) as $enum) {
            $this->assertEquals(false, $this->field->value[$enum]);
        }

        $this->model->enum = array_keys(array_filter($this->values));

        $this->field->resolve($this->model);

        $this->assertCount(count(FlaggedEnum::getValues()) - 1, $this->field->value);

        foreach ($this->values as $enum => $value) {
            $this->assertEquals($value, $this->field->value[$enum]);
        }
    }

    /** @test */
    public function it_fills_database_with_flagged_enum_value()
    {
        $request = new NovaRequest();
        $request->query->add(['enum' => json_encode($this->values)]);

        $this->field->fill($request, $this->model);

        $this->assertDatabaseHas('example_models', ['enum' => FlaggedEnum::None]);

        $this->model->save();

        $this->assertDatabaseHas('example_models', [
            'enum' => array_sum(array_keys(array_filter($this->values)))
        ]);

        $this->assertDatabaseMissing('example_models', ['enum' => FlaggedEnum::None]);
    }
}
