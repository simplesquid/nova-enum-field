<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class BooleanFilterTest extends TestCase
{
    private $filter;

    private $mockFilter;

    protected function setUp(): void
    {
        $this->filter = new EnumBooleanFilter('enum', IntegerEnum::class);

        $this->mockFilter = new MockFilter($this->filter);
    }

    /** @test */
    public function it_is_a_boolean_filter()
    {
        $this->mockFilter->assertBooleanFilter();
    }

    /** @test */
    public function it_has_a_default_name()
    {
        $this->assertEquals('Enum', $this->filter->name());
    }

    /** @test */
    public function it_can_have_a_different_name()
    {
        $this->assertInstanceOf(EnumBooleanFilter::class, $this->filter->name('Different name'));

        $this->assertEquals('Different name', $this->filter->name());
    }

    /** @test */
    public function it_accepts_optional_default_values()
    {
        $this->filter->default(IntegerEnum::Moderator);

        $this->assertEquals([
            IntegerEnum::Administrator => false,
            IntegerEnum::Moderator => true,
            IntegerEnum::Subscriber => false,
        ], $this->filter->jsonSerialize()['currentValue']);

        $this->filter->default(IntegerEnum::Administrator());

        $this->assertEquals([
            IntegerEnum::Administrator => true,
            IntegerEnum::Moderator => false,
            IntegerEnum::Subscriber => false,
        ], $this->filter->jsonSerialize()['currentValue']);

        $this->filter->default([
            IntegerEnum::Subscriber,
            IntegerEnum::Moderator(),
        ]);

        $this->assertEquals([
            IntegerEnum::Administrator => false,
            IntegerEnum::Moderator => true,
            IntegerEnum::Subscriber => true,
        ], $this->filter->jsonSerialize()['currentValue']);
    }

    /** @test */
    public function it_has_no_default_value_by_default()
    {
        $this->assertEquals([
            IntegerEnum::Administrator => false,
            IntegerEnum::Moderator => false,
            IntegerEnum::Subscriber => false,
        ], $this->filter->jsonSerialize()['currentValue']);
    }
}
