<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Fields;

use BenSampo\Enum\Rules\EnumValue;
use Laravel\Nova\Http\Requests\NovaRequest;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FieldTest extends TestCase
{
    private $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->field = Enum::make('Enum')->attach(IntegerEnum::class);
    }

    /** @test */
    public function it_starts_with_no_options_and_rules()
    {
        $field = Enum::make('Enum');

        $this->assertArrayNotHasKey('options', $field->meta);

        $this->assertEmpty($field->rules);
    }

    /** @test */
    public function it_allows_an_enum_to_be_attached()
    {
        $this->assertIsObject($this->field);
        $this->assertTrue(property_exists($this->field, 'optionsCallback'));
    }

    /** @test */
    public function it_adds_correct_rules()
    {
        $this->assertContains('required', $this->field->rules);

        $this->assertContainsEquals(new EnumValue(IntegerEnum::class, false), $this->field->rules);
    }

    /** @test */
    public function it_displays_enum_options()
    {
        $this->assertCount(count(IntegerEnum::getValues()), $this->field->optionsCallback);

        $this->assertSame(IntegerEnum::asSelectArray(), $this->field->optionsCallback);
    }

    /** @test */
    public function it_can_be_nullable()
    {
        $this->field->nullable();

        $this->assertNotContains('required', $this->field->rules);
        $this->assertContains('nullable', $this->field->rules);
        $this->assertFalse($this->field->isRequired(new NovaRequest()));
    }
}
