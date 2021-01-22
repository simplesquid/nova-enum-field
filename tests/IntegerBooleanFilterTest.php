<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;

class IntegerBooleanFilterTest extends TestCase
{
    private $filter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        IntegerModel::create(['enum' => IntegerEnum::Moderator()]);
        IntegerModel::create(['enum' => IntegerEnum::Moderator()]);

        IntegerModel::create(['enum' => IntegerEnum::Administrator()]);

        $this->filter = new MockFilter(new EnumBooleanFilter('enum', IntegerEnum::class));
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        $this->filter->assertHasOption(IntegerEnum::Subscriber());

        $this->filter->assertHasOption(IntegerEnum::Moderator());

        $this->filter->assertHasOption(IntegerEnum::Administrator());
    }

    public function optionsProvider(): array
    {
        return [
            [true, false, false, 0],
            [true, true, false, 2],
            [true, false, true, 1],
            [true, true, true, 3],
            [false, false, false, 3],
            [false, true, false, 2],
            [false, false, true, 1],
            [false, true, true, 3],
        ];
    }

    /**
     * @dataProvider optionsProvider
     * @test
     */
    public function it_returns_the_correct_number_of_results(
        bool $subscriber,
        bool $moderator,
        bool $administrator,
        int $count
    ) {
        $value = [
            IntegerEnum::Subscriber => $subscriber,
            IntegerEnum::Moderator => $moderator,
            IntegerEnum::Administrator => $administrator,
        ];

        $response = $this->filter->apply(IntegerModel::class, $value);

        $response->assertCount($count);
    }

    /**
     * @dataProvider optionsProvider
     * @test
     */
    public function it_returns_the_correct_results(
        bool $subscriber,
        bool $moderator,
        bool $administrator,
        int $count
    ) {
        $value = [
            IntegerEnum::Subscriber => $subscriber,
            IntegerEnum::Moderator => $moderator,
            IntegerEnum::Administrator => $administrator,
        ];

        $response = $this->filter->apply(IntegerModel::class, $value);

        // None selected should show all models
        if (count(array_unique($value)) === 1 && $subscriber === false) {
            $subscriber = $moderator = $administrator = true;
        }

        IntegerModel::whereEnum(IntegerEnum::Subscriber())->each(function ($model) use ($response, $subscriber) {
            if ($subscriber) {
                return $response->assertContains($model);
            }

            $response->assertMissing($model);
        });

        IntegerModel::whereEnum(IntegerEnum::Moderator())->each(function ($model) use ($response, $moderator) {
            if ($moderator) {
                return $response->assertContains($model);
            }

            $response->assertMissing($model);
        });

        IntegerModel::whereEnum(IntegerEnum::Administrator())->each(function ($model) use ($response, $administrator) {
            if ($administrator) {
                return $response->assertContains($model);
            }

            $response->assertMissing($model);
        });
    }
}
