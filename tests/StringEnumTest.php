<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use PHPUnit\Framework\TestCase;
use SimpleSquid\Nova\Fields\Enum\Enum;

class StringEnumTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Enum */
    private $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Enum::make('Enum Field');

        $this->field->attachEnum(ExampleStringEnum::class);
    }

    /** @test */
    public function field_resolves_correct_value()
    {
        $this->field->resolve(['enum_field' => ExampleStringEnum::Moderator]);

        $this->assertSame('moderator', $this->field->value);
    }

    /** @test */
    public function field_displays_correct_description()
    {
        $this->field->resolveForDisplay(['enum_field' => ExampleStringEnum::Moderator]);

        $this->assertSame('moderator', $this->field->value);
    }
}
