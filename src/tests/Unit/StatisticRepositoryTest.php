<?php

namespace Tests\Unit;

use App\Http\Repositories\StatisticRepository;
use App\Http\Resources\StatisticResource;
use Carbon\Carbon;
use Tests\Unit\RedisTestSetUp;
use PHPUnit\Framework\TestCase;

class StatisticRepositoryTest extends TestCase
{
    use RedisTestSetUp;
    /**
     * @test
     * @return void
     */
    public function test_get_statistics_resource()
    {
        $payload = [[
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ]];
        $statisticRepository = new StatisticRepository();
        $statisticsResource = $statisticRepository->all($payload);
        $this->assertInstanceOf(StatisticResource::class,$statisticsResource);
    }

}
