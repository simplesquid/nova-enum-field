<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;

class BooleanFilterTest extends TestCase
{
    private $filter;

    protected function setUp(): void
    {
        $this->filter = new MockFilter(new EnumBooleanFilter('enum', IntegerEnum::class));
    }

    /** @test */
    public function it_is_a_boolean_filter()
    {
        $this->filter->assertBooleanFilter();
    }
}
