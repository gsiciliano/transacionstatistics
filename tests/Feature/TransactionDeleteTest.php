<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Http\Response;

class TransactionDeleteTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function test_delete_transactions_unauthorized_and_get_401()
    {
        $response = $this->deleteJson('/transactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function test_delete_transactions_persistent_and_in_memory_and_get_200()
    {
        Transaction::factory()->count(10)->create();
        $response = $this->withoutMiddleware()->deleteJson('/transactions');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(["deleted"=>11]);
    }
}
