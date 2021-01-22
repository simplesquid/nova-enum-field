<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringModel;

class StringFilterTest extends TestCase
{
    private $filter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app, 'string');

        StringModel::create(['enum' => StringEnum::Moderator()]);
        StringModel::create(['enum' => StringEnum::Moderator()]);

        StringModel::create(['enum' => StringEnum::Administrator()]);

        $this->filter = new MockFilter(new EnumFilter('enum', StringEnum::class));
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        $this->filter->assertHasOption(StringEnum::Subscriber());

        $this->filter->assertHasOption(StringEnum::Moderator());

        $this->filter->assertHasOption(StringEnum::Administrator());
    }

    /** @test */
    public function it_returns_the_correct_number_of_results()
    {
        $response = $this->filter->apply(StringModel::class, StringEnum::Subscriber());

        $response->assertCount(0);

        $response = $this->filter->apply(StringModel::class, StringEnum::Moderator());

        $response->assertCount(2);

        $response = $this->filter->apply(StringModel::class, StringEnum::Administrator());

        $response->assertCount(1);
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        $response1 = $this->filter->apply(StringModel::class, StringEnum::Moderator());

        $response2 = $this->filter->apply(StringModel::class, StringEnum::Administrator());

        StringModel::whereEnum(StringEnum::Moderator())->each(function ($model) use ($response1, $response2) {
            $response1->assertContains($model);

            $response2->assertMissing($model);
        });

        StringModel::whereEnum(StringEnum::Administrator())->each(function ($model) use ($response1, $response2) {
            $response1->assertMissing($model);

            $response2->assertContains($model);
        });
    }
}
