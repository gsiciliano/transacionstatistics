<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\TransactionController;

class TransactionTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function test_get_transaction_model_instance()
    {
        $this->assertInstanceOf(Transaction::class, Transaction::factory()->make());
    }

}
