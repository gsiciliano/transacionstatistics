<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Http\Response;

class TransactionGetTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function test_get_transactions_unauthorized_and_get_401()
    {
        $response = $this->getJson('/transactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function test_get_transactions_without_from_parameter_and_get_422()
    {
        $response = $this->withoutMiddleware()->getJson('/transactions');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function test_get_transactions_with_bad_from_parameter_and_get_422()
    {
        $response = $this->withoutMiddleware()->getJson('/transactions?from=today');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function test_get_transactions_from_persistence_and_get_200()
    {
        $now = Carbon::now();
        Transaction::factory()->count(1)->create([
            'amount'=>1000,
            'timestamp' => $now->format('Y-m-dH:i:s.v')
        ]);

        $response = $this->withoutMiddleware()->getJson('/transactions?from='.$now->format('Y-m-d\TH:i:s.v\Z'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1);
        $response->assertJsonStructure(['*' => ['amount','timestamp']]);
        $response->assertExactJson([ [
            'amount' => "1000.0",
            'timestamp' => $now->format('Y-m-d\TH:i:s.v\Z')
        ] ]);
    }


}
