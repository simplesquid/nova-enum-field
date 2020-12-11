<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use BenSampo\Enum\Rules\EnumValue;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\ExampleIntegerEnum;

class IntegerEnumTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Enum */
    private $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Enum::make('Enum');

        $this->field->attach(ExampleIntegerEnum::class);
    }

    /** @test */
    public function field_starts_with_no_options_and_rules()
    {
        $field = Enum::make('Enum');

        $this->assertArrayNotHasKey('options', $field->meta);
        $this->assertEmpty($field->rules);
    }

    /** @test */
    public function an_enum_can_be_attached_to_the_field()
    {
        $this->assertArrayHasKey('options', $this->field->meta);

        $this->assertEquals([
                                [
                                    'label' => 'Administrator',
                                    'value' => 0,
                                ],
                                [
                                    'label' => 'Moderator',
                                    'value' => 1,
                                ],
                                [
                                    'label' => 'Subscriber',
                                    'value' => 2,
                                ],
                            ], $this->field->meta['options']);
    }

    /** @test */
    public function attaching_an_enum_adds_correct_rules()
    {
        $this->assertContains('required', $this->field->rules);

        $this->assertContainsEquals(new EnumValue(ExampleIntegerEnum::class, false), $this->field->rules);
    }

    /** @test */
    public function field_resolves_correct_value()
    {
        $this->field->resolve(['enum' => ExampleIntegerEnum::Moderator()]);

        $this->assertSame(1, $this->field->value);
    }

    /** @test */
    public function field_displays_correct_description()
    {
        $this->field->resolveForDisplay(['enum' => ExampleIntegerEnum::Moderator()]);

        $this->assertSame('Moderator', $this->field->value);
    }
}
