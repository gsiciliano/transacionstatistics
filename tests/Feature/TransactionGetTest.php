<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionGetTest extends TestCase
{
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
        $response = $this->withoutMiddleware()->getJson('/transactions?from='.Carbon::now()->format('Y-m-dTH:i:s.vZ'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10);
        $response->assertJsonStructure(['*' => ['amount','timestamp']]);
    }

}
