<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @return void
     */
    public function delete_response_code_401()
    {
        $response = $this->deleteJson('/transactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function delete_response_code_200()
    {
        Transaction::factory()->count(10)->create();
        $response = $this->withoutMiddleware()->deleteJson('/transactions');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(["deleted"=>10]);
    }
}
