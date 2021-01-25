<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedModel;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;

class FlaggedBooleanFilterTest extends TestCase
{
    private $filter;

    private $mockFilter;

    private $models = [];

    private $results = [
        FlaggedEnum::None          => [0],
        FlaggedEnum::ReadComments  => [1, 2],
        FlaggedEnum::WriteComments => [2],
        FlaggedEnum::EditComments  => [],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase($this->app);

        $this->models[0] = FlaggedModel::create(['enum' => FlaggedEnum::None]);
        $this->models[1] = FlaggedModel::create(['enum' => FlaggedEnum::ReadComments]);
        $this->models[2] = FlaggedModel::create([
                                                    'enum' => array_sum([
                                                                            FlaggedEnum::ReadComments,
                                                                            FlaggedEnum::WriteComments
                                                                        ])
                                                ]);

        $this->filter = new EnumBooleanFilter('enum', FlaggedEnum::class);
        $this->mockFilter = new MockFilter($this->filter);
    }

    private function getOptions(array $keys, array $options = [[]]): array
    {
        if (empty($keys)) {
            return $options;
        }

        $current = array_shift($keys);
        $newOptions = [];

        foreach ($options as $option) {
            $newOptions[] = $option + [$current => true];
            $newOptions[] = $option + [$current => false];
        }

        return $this->getOptions($keys, $newOptions);
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        foreach (array_keys($this->results) as $enum) {
            $this->mockFilter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_can_filter_by_all_selected_options()
    {
        $this->filter->filterAllFlags();

        foreach (array_keys($this->results) as $enum) {
            if ($enum === FlaggedEnum::None) {
                continue;
            }

            $this->mockFilter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_returns_the_correct_number_of_results_when_filtering_by_any_flag()
    {
        foreach ($options = $this->getOptions(FlaggedEnum::getValues()) as $option) {
            $response = $this->mockFilter->apply(IntegerModel::class, $option);

            // None selected should show all models
            if (count(array_filter($option)) === 0) {
                $response->assertCount(3);
            } else {
                $response->assertCount(count(array_unique(array_merge(...array_intersect_key($this->results, array_filter($option))))));
            }
        }
    }

    /** @test */
    public function it_returns_the_correct_results_when_filtering_by_any_flag()
    {
        foreach ($options = $this->getOptions(FlaggedEnum::getValues()) as $option) {
            $response = $this->mockFilter->apply(IntegerModel::class, $option);

            // None selected should show all models
            if (count(array_filter($option)) === 0) {
                $models = array_keys($this->models);
            } else {
                $models = array_unique(array_merge(...array_intersect_key($this->results, array_filter($option))));
            }

            foreach ($models as $contain) {
                $response->assertContains($this->models[$contain]);
            }

            foreach (array_diff(array_keys($this->models), $models) as $missing) {
                $response->assertMissing($this->models[$missing]);
            }
        }
    }

    /** @test */
    public function it_returns_the_correct_number_of_results_when_filtering_by_all_flags()
    {
        $this->filter->filterAllFlags();

        foreach ($options = $this->getOptions(array_diff(FlaggedEnum::getValues(), [FlaggedEnum::None])) as $option) {
            $response = $this->mockFilter->apply(IntegerModel::class, $option);

            // None selected should show all models
            if (count(array_filter($option)) === 0) {
                $response->assertCount(3);
            } else {
                $response->assertCount(count(array_intersect(array_keys($this->models), ...array_intersect_key($this->results, array_filter($option)))));
            }
        }
    }

    /** @test */
    public function it_returns_the_correct_results_when_filtering_by_all_flags()
    {
        $this->filter->filterAllFlags();

        foreach ($options = $this->getOptions(array_diff(FlaggedEnum::getValues(), [FlaggedEnum::None])) as $option) {
            $response = $this->mockFilter->apply(IntegerModel::class, $option);

            // None selected should show all models
            if (count(array_filter($option)) === 0) {
                $models = array_keys($this->models);
            } else {
                $models = array_intersect(array_keys($this->models), ...array_intersect_key($this->results, array_filter($option)));
            }

            foreach ($models as $contain) {
                $response->assertContains($this->models[$contain]);
            }

            foreach (array_diff(array_keys($this->models), $models) as $missing) {
                $response->assertMissing($this->models[$missing]);
            }
        }
    }
}
