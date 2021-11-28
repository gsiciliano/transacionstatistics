<?php

namespace Tests\Unit;

use App\Models\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function get_transaction_model()
    {
        $this->assertInstanceOf(Transaction::class, Transaction::factory()->make());
    }
}
