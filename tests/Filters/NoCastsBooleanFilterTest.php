<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use PHPUnit\Framework\Attributes\Test;
use SimpleSquid\Nova\Fields\Enum\EnumBooleanFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\NoCastsModel;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class NoCastsBooleanFilterTest extends TestCase
{
    private $filter;

    private $models = [];

    private $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app, 'string');

        $this->filter = new MockFilter(new EnumBooleanFilter('enum', StringEnum::class));

        $this->models[0] = NoCastsModel::create(['enum' => StringEnum::Moderator]);

        $this->models[1] = NoCastsModel::create(['enum' => StringEnum::Moderator]);

        $this->models[2] = NoCastsModel::create(['enum' => StringEnum::Administrator]);

        $this->results = [
            StringEnum::Moderator => [0, 1],
            StringEnum::Administrator => [2],
            StringEnum::Subscriber => [],
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
        foreach (StringEnum::getValues() as $enum) {
            $this->filter->assertHasOption($enum);
        }
    }

    #[Test]
    public function it_returns_the_correct_results()
    {
        foreach ($options = $this->getOptions(StringEnum::getValues()) as $option) {
            $response = $this->filter->apply(NoCastsModel::class, $option);

            // None selected should show all models
            if (count(array_filter($option)) === 0) {
                $models = array_keys($this->models);
            } else {
                $models = array_unique(array_merge(...array_values(array_intersect_key($this->results, array_filter($option)))));
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
