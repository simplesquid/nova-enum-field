<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use PHPUnit\Framework\TestCase;
use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\ExampleStringEnum;

class StringEnumTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Enum */
    private $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Enum::make('Enum');

        $this->field->attachEnum(ExampleStringEnum::class);
    }

    /** @test */
    public function field_resolves_correct_value()
    {
        $this->field->resolve(['enum' => ExampleStringEnum::Moderator()]);

        $this->assertSame('moderator', $this->field->value);
    }

    /** @test */
    public function field_displays_correct_description()
    {
        $this->field->resolveForDisplay(['enum' => ExampleStringEnum::Moderator()]);

        $this->assertSame('Moderator', $this->field->value);
    }
}
