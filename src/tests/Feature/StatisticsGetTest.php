<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Response;

class StatisticsGetTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function test_get_statistics_and_get_200()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->withoutMiddleware()->getJson('/statistics');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'sum' => '10000.00',
            'avg' => '10000.00',
            'max' => '10000.00',
            'min' => '10000.00',
            'count' => 1
        ]);

    }

}
