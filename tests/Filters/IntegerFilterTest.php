<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\IntegerModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class IntegerFilterTest extends TestCase
{
    private $filter;

    private $models = [];

    private $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->filter = new MockFilter(new EnumFilter('enum', IntegerEnum::class));

        $this->models[0] = IntegerModel::create(['enum' => IntegerEnum::Moderator]);

        $this->models[1] = IntegerModel::create(['enum' => IntegerEnum::Moderator]);

        $this->models[2] = IntegerModel::create(['enum' => IntegerEnum::Administrator]);

        $this->results = [
            IntegerEnum::Moderator => [0, 1],
            IntegerEnum::Administrator => [2],
            IntegerEnum::Subscriber => [],
        ];
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        foreach (IntegerEnum::getValues() as $enum) {
            $this->filter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        foreach ($this->results as $enum => $models) {
            $response = $this->filter->apply(IntegerModel::class, $enum);

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
