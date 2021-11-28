<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionPostTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function post_response_code_401()
    /**
     * @test
     * @return void
     */
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601ZuluString()
        ];
        $response = $this->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function post_response_code_400()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601ZuluString()
        ];
        $response = $this->withoutMiddleware()->post('/transactions',$payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     * @return void
     */
    public function post_response_code_422_with_a_future_datetime()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->addDays(2)->toIso8601ZuluString()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function post_response_code_422_with_a_non_numeric_amount()
    {
        $payload = [
            'amount'=> 'thousand',
            'timestamp' => Carbon::now()->toIso8601ZuluString()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function post_response_code_201_put_new_data()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601ZuluString()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     * @return void
     */
    public function post_response_code_204_put_new_data_older_than_60_sec()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->subMinutes(2)->toIso8601ZuluString()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


}
