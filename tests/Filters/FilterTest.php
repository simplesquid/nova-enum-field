<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use PHPUnit\Framework\Attributes\Test;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FilterTest extends TestCase
{
    private $filter;

    private $mockFilter;

    protected function setUp(): void
    {
        $this->filter = new EnumFilter('enum', IntegerEnum::class);

        $this->mockFilter = new MockFilter($this->filter);
    }

    #[Test]
    public function it_is_a_select_filter()
    {
        $this->mockFilter->assertSelectFilter();
    }

    #[Test]
    public function it_has_a_default_name()
    {
        $this->assertEquals('Enum', $this->filter->name());
    }

    #[Test]
    public function it_can_have_a_different_name()
    {
        $this->assertInstanceOf(EnumFilter::class, $this->filter->name('Different name'));

        $this->assertEquals('Different name', $this->filter->name());
    }

    #[Test]
    public function it_accepts_an_optional_default_value()
    {
        $this->filter->default(IntegerEnum::Moderator);

        $this->assertEquals(IntegerEnum::Moderator, $this->filter->jsonSerialize()['currentValue']);

        $this->filter->default(IntegerEnum::Subscriber());

        $this->assertEquals(IntegerEnum::Subscriber, $this->filter->jsonSerialize()['currentValue']);
    }

    #[Test]
    public function it_has_no_default_value_by_default()
    {
        $this->assertEquals('', $this->filter->jsonSerialize()['currentValue']);
    }
}
