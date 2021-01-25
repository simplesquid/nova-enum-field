<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests;

use JoshGaber\NovaUnit\Filters\MockFilter;
use Laravel\Nova\NovaServiceProvider;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedModel;

class FlaggedFilterTest extends TestCase
{
    private $filter;

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

        app()->register(NovaServiceProvider::class);

        $this->setUpDatabase($this->app);

        $this->models[0] = FlaggedModel::create(['enum' => FlaggedEnum::None]);

        $this->models[1] = FlaggedModel::create(['enum' => FlaggedEnum::ReadComments]);

        $this->models[2] = FlaggedModel::create([
                                                    'enum' => array_sum([
                                                                            FlaggedEnum::ReadComments,
                                                                            FlaggedEnum::WriteComments
                                                                        ])
                                                ]);

        $this->filter = new MockFilter(new EnumFilter('enum', FlaggedEnum::class));
    }

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        foreach (array_keys($this->results) as $enum) {
            $this->filter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_returns_the_correct_number_of_results()
    {
        foreach ($this->results as $enum => $models) {
            $response = $this->filter->apply(FlaggedModel::class, $enum);

            $response->assertCount(count($models));
        }
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        foreach ($this->results as $enum => $models) {
            $response = $this->filter->apply(FlaggedModel::class, $enum);

            foreach ($models as $contain) {
                $response->assertContains($this->models[$contain]);
            }

            foreach (array_diff([0, 1, 2], $models) as $missing) {
                $response->assertMissing($this->models[$missing]);
            }
        }
    }
}
