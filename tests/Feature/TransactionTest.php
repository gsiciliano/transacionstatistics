<?php

namespace Tests\Feature;

use App\Http\Requests\TransactionRequest;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @return void
     */
    public function get_response_code_401()
    {
        $response = $this->getJson('/transactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function get_response_code_422()
    {
        $response = $this->withoutMiddleware()->getJson('/transactions');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function get_response_code_200_with_some_data()
    {
        Transaction::factory()->count(10)->create();
        $response = $this->withoutMiddleware()->getJson('/transactions?from='.Carbon::now()->toIso8601ZuluString());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10);
        $response->assertJsonStructure(['*' => ['amount','timestamp']]);
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
    public function post_response_code_401()
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
