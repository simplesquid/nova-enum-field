<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;

class IntegerFilterTest extends TestCase
{
    private $filter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        IntegerModel::create(['enum' => IntegerEnum::Moderator()]);
        IntegerModel::create(['enum' => IntegerEnum::Moderator()]);

        IntegerModel::create(['enum' => IntegerEnum::Administrator()]);

        $this->filter = new MockFilter(new EnumFilter('enum', IntegerEnum::class));
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        $this->filter->assertHasOption(IntegerEnum::Subscriber());

        $this->filter->assertHasOption(IntegerEnum::Moderator());

        $this->filter->assertHasOption(IntegerEnum::Administrator());
    }

    /** @test */
    public function it_returns_the_correct_number_of_results()
    {
        $response = $this->filter->apply(IntegerModel::class, IntegerEnum::Subscriber());

        $response->assertCount(0);

        $response = $this->filter->apply(IntegerModel::class, IntegerEnum::Moderator());

        $response->assertCount(2);

        $response = $this->filter->apply(IntegerModel::class, IntegerEnum::Administrator());

        $response->assertCount(1);
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        $response1 = $this->filter->apply(IntegerModel::class, IntegerEnum::Moderator());

        $response2 = $this->filter->apply(IntegerModel::class, IntegerEnum::Administrator());

        IntegerModel::whereEnum(IntegerEnum::Moderator())->each(function ($model) use ($response1, $response2) {
            $response1->assertContains($model);

            $response2->assertMissing($model);
        });

        IntegerModel::whereEnum(IntegerEnum::Administrator())->each(function ($model) use ($response1, $response2) {
            $response1->assertMissing($model);

            $response2->assertContains($model);
        });
    }
}
