<?php

namespace SimpleSquid\Nova\Fields\Enum\Tests\Fields;

use JoshGaber\NovaUnit\Filters\MockFilter;
use SimpleSquid\Nova\Fields\Enum\EnumFilter;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedEnum;
use SimpleSquid\Nova\Fields\Enum\Tests\Examples\FlaggedModel;
use SimpleSquid\Nova\Fields\Enum\Tests\TestCase;

class FlaggedFilterTest extends TestCase
{
    private $filter;

    private $models = [];

    private $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->filter = new MockFilter(new EnumFilter('enum', FlaggedEnum::class));

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

    /** @test */
    public function it_contains_all_the_filter_values()
    {
        foreach (FlaggedEnum::getValues() as $enum) {
            $this->filter->assertHasOption($enum);
        }
    }

    /** @test */
    public function it_returns_the_correct_results()
    {
        foreach ($this->results as $enum => $models) {
            $response = $this->filter->apply(FlaggedModel::class, $enum);

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
