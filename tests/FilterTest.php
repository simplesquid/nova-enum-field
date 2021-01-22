<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;

class FilterTest extends TestCase
{
    private $filter;

    protected function setUp(): void
    {
        $this->filter = new MockFilter(new EnumFilter('enum', IntegerEnum::class));
    }

    /** @test */
    public function it_is_a_select_filter()
    {
        $this->filter->assertSelectFilter();
    }
}
