<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use PHPUnit\Framework\Attributes\Test;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedModel;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FlaggedBooleanFilterTest extends TestCase
{
    private $filter;

    private $mockFilter;

    private $models = [];

    private $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->filter = new EnumBooleanFilter('enum', FlaggedEnum::class);

        $this->mockFilter = new MockFilter($this->filter);

        $this->models[0] = FlaggedModel::create(['enum' => FlaggedEnum::None]);

        $this->models[1] = FlaggedModel::create(['enum' => FlaggedEnum::ReadComments]);

        $this->models[2] = FlaggedModel::create([
            'enum' => array_sum([
                FlaggedEnum::ReadComments,
                FlaggedEnum::WriteComments,
            ]),
        ]);

        $this->results = [
            FlaggedEnum::None => [0],
            FlaggedEnum::ReadComments => [1, 2],
            FlaggedEnum::WriteComments => [2],
            FlaggedEnum::EditComments => [],
        ];
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

    #[Test]
    public function it_contains_all_the_filter_values()
    {
        foreach (array_keys($this->results) as $enum) {
            $this->mockFilter->assertHasOption($enum);
        }
    }

    #[Test]
    public function it_can_filter_by_all_selected_options()
    {
        $this->filter->filterAllFlags();

        foreach (array_keys($this->results) as $enum) {
            if ($enum === FlaggedEnum::None) {
                $this->mockFilter->assertOptionMissing($enum);

                continue;
            }

            $this->mockFilter->assertHasOption($enum);
        }
    }

    #[Test]
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

            $response->assertCount(count($models));

            foreach ($models as $contain) {
                $response->assertContains($this->models[$contain]);
            }

            foreach (array_diff(array_keys($this->models), $models) as $missing) {
                $response->assertMissing($this->models[$missing]);
            }
        }
    }

    #[Test]
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

            $response->assertCount(count($models));

            foreach ($models as $contain) {
                $response->assertContains($this->models[$contain]);
            }

            foreach (array_diff(array_keys($this->models), $models) as $missing) {
                $response->assertMissing($this->models[$missing]);
            }
        }
    }
}
