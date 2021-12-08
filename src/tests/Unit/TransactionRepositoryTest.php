<?php

namespace Tests\Unit;

use Tests\TestUtils;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\MockPredisConnection;
use App\Http\Repositories\TransactionRepository;

class TransactionRepositoryTest extends TestCase
{
    use RedisTestSetUp;

    /**
     * @test
     * @return void
     */
    public function test_get_transaction_model_instance()
    {
        $this->assertInstanceOf(Transaction::class, Transaction::factory()->make());
    }

    public function test_redis_is_mocked()
    {
        $this->assertInstanceOf(MockPredisConnection::class, Redis::connection());
    }
    /**
     * @test
     * @return void
     */
    public function test_get_transactions(){
        $now = Carbon::now()->toIso8601String();
        $transactionFactory = Transaction::factory()->create([
            'amount' => '500',
            'timestamp' => $now
        ]);
        $transactionRepository = new TransactionRepository();
        $transactionCollection = $transactionRepository->get($now);
        $this->assertIsObject($transactionCollection);
        $this->assertTrue($transactionCollection->contains('timestamp', $transactionFactory->timestamp));
        $this->assertTrue($transactionCollection->contains('amount', $transactionFactory->amount));
    }

    /**
     * @test
     * @return void
     */
    public function test_get_all_qeued_transactions_and_then_remove_them()
    {
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $redis = new Redis();
        $queueResponse = $redis::hmset(microtime(), $payload);
        $transactionRepository = new TransactionRepository();
        $transactionsFromQueue = TestUtils::array_flatten($transactionRepository->getAllFromQueue());
        $transactionsRemovedFromQueue = $transactionRepository->removeAllQueuedItems();
        $this->assertEquals('OK', $queueResponse);
        $this->assertIsArray($transactionsFromQueue);
        $this->assertArrayHasKey('amount',$transactionsFromQueue);
        $this->assertArrayHasKey('timestamp',$transactionsFromQueue);
        $this->assertEquals(1,$transactionsRemovedFromQueue);
    }

    /**
     * @test
     * @return void
     */
    public function test_add_transaction_to_queue_and_then_delete_it(){

        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $transactionRepository = new TransactionRepository();
        $queueResponse = $transactionRepository->addToQueue($payload);
        $insertedKey = TestUtils::array_flatten(Redis::keys('*'));
        $deletionResponse = $transactionRepository->removeFromQueue($insertedKey);
        $this->assertEquals('OK', $queueResponse);
        $this->assertEquals(2, $deletionResponse);
    }


    /**
     * @test
     * @return void
     */
    public function test_save_transaction(){
        $payload = [
            'amount'=> '10000',
            'timestamp' => Carbon::now()->toIso8601String()
        ];
        $transactionFactory = Transaction::factory()->make($payload);
        $transactionRepository = new TransactionRepository();
        $transactionSaved = $transactionRepository->save($payload);
        $this->assertInstanceOf(Transaction::class, $transactionSaved);
        $this->assertEquals($transactionFactory->amount, $transactionSaved->amount);
        $this->assertEquals($transactionFactory->timestamp, $transactionSaved->timestamp);
    }

    /**
     * @test
     * @return void
     */
    public function test_truncate_transaction_table()
    {
        $numberOfTransactions = 10;
        Transaction::factory()->count($numberOfTransactions)->create();
        $transactionRepository = new TransactionRepository();
        $this->assertEquals($numberOfTransactions, $transactionRepository->truncateTable());
    }

}
