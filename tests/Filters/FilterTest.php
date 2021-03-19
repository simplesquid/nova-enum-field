<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FilterTest extends TestCase
{
    private $filter;

    private $emptyFilter;

    private $mockFilter;

    protected function setUp(): void
    {
        $this->filter = new EnumFilter('enum', IntegerEnum::class, IntegerEnum::Moderator());
        $this->emptyFilter = new EnumFilter('enum', IntegerEnum::class);

        $this->mockFilter = new MockFilter($this->filter);
    }

    /** @test */
    public function it_is_a_select_filter()
    {
        $this->mockFilter->assertSelectFilter();
    }

    /** @test */
    public function it_has_a_default_name()
    {
        $this->assertEquals('Enum', $this->filter->name());
    }

    /** @test */
    public function it_can_have_a_different_name()
    {
        $this->assertInstanceOf(EnumFilter::class, $this->filter->name('Different name'));

        $this->assertEquals('Different name', $this->filter->name());
    }

    /** @test */
    public function it_has_a_default_value()
    {
        $this->assertEquals(IntegerEnum::Moderator(), $this->filter->jsonSerialize()['currentValue']);
    }

    /** @test */
    public function it_shouldnt_have_a_value_if_no_default()
    {
        $this->assertEquals('', $this->emptyFilter->jsonSerialize()['currentValue']);
    }
}
