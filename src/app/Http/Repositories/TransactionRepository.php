<?php

namespace App\Http\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\TransactionResource;

class TransactionRepository
{
    /**
     * Get all transactions from a given timestamp.
     *
     * @param  string  $timeStamp
     * @return App\Http\Resources\TransactionResource collection
     */
    public function get($timeStamp){

        $transactions = Transaction::when($timeStamp, function($query ,$timeStamp){
            return $query->where('timestamp', '>=', $timeStamp);
        })->orderBy('timestamp')->get();

        return TransactionResource::collection($transactions);
    }

    /**
     * Get all queue transactions.
     *
     * @return Array transactions
     */
    public function getAllFromQueue(){
        return array_map(function($key){
            return array_merge(['key'=>$key], Redis::hgetall($key));
        }, Redis::keys('*'));
    }

    /**
     * Store a transaction.
     *
     * @param Array validated data
     * @return App\Models\Transaction transaction
     */
    public function save($data){
        return Transaction::create($data);
    }

    /**
     * Queue a transaction in memory with microtime key.
     *
     * @param Array validated data
     * @return String operation result
     */
    public function addToQueue($data){
        return Redis::hmset(microtime(),$data);
    }

    /**
     * Remove a queued transaction with a given key
     *
     * @param string key
     * @return int deletion result
     */
    public function removeFromQueue($key){
        return Redis::del($key);
    }

    /**
     * Remove all queued transactions
     *
     * @return int number of item removed
     */
    public function removeAllQueuedItems(){
        $cachedItems = count(Redis::keys('*'));
        Redis::flushdb();
        return $cachedItems;
    }

    /**
     * Remove all persistent transactions
     *
     * @return int number of item removed
     */
    public function truncateTable(){
        return DB::table('transactions')->delete();
    }
}
