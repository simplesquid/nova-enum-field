<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use SimpleSquid\Nova\Fields\Enum\Enum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringEnum;

class StringFieldTest extends TestCase
{
    /** @var \SimpleSquid\Nova\Fields\Enum\Enum */
    private $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Enum::make('Enum');

        $this->field->attach(StringEnum::class);
    }

    /** @test */
    public function it_resolves_correct_value()
    {
        $this->field->resolve(['enum' => StringEnum::Moderator()]);

        $this->assertSame('moderator', $this->field->value);
    }

    /** @test */
    public function it_displays_correct_description()
    {
        $this->field->resolveForDisplay(['enum' => StringEnum::Moderator()]);

        $this->assertSame('Moderator', $this->field->value);
    }
}
