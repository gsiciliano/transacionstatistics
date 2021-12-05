<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Response;

class TransactionPostTest extends TestCase
{
    public function test_post_transactions_unauthorized_and_get_401()
    /**
     * @test
     * @return void
     */
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function test_post_transactions_with_no_json_formatted_input_and_get_400()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->post('/transactions',$payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     * @return void
     */
    public function test_post_transactions_with_a_future_date_and_get_422()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->addDays(2)->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function test_post_transactions_with_non_numeric_amount_and_get_422()
    {
        $payload = [
            'amount'=> 'thousand',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    /**
     * @test
     * @return void
     */
    public function test_post_transactions_with_zero_amount_and_get_422()
    {
        $payload = [
            'amount'=> '0',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function test_post_transactions_in_memory_and_get_201()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     * @return void
     */
    public function test_post_transactions_older_than_60_second_that_persist_and_get_204()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->subMinutes(2)->toIso8601String()
        ];
        $response = $this->withoutMiddleware()->postJson('/transactions',$payload);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


}
