<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Filters;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\NoCastsModel;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\StringEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class NoCastsFilterTest extends TestCase
{
    private $filter;

    private $models = [];

    private $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app, 'string');

        $this->filter = new MockFilter(new EnumFilter('enum', StringEnum::class));

        $this->models[0] = NoCastsModel::create(['enum' => StringEnum::Moderator]);

        $this->models[1] = NoCastsModel::create(['enum' => StringEnum::Moderator]);

        $this->models[2] = NoCastsModel::create(['enum' => StringEnum::Administrator]);

        $this->results = [
            StringEnum::Moderator     => [0, 1],
            StringEnum::Administrator => [2],
            StringEnum::Subscriber    => [],
        ];
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        foreach (StringEnum::getValues() as $enum) {
            $this->filter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        foreach ($this->results as $enum => $models) {
            $response = $this->filter->apply(NoCastsModel::class, $enum);

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
