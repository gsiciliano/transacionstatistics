<?php

namespace App\Http\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\TransactionResource;

class TransactionRepository
{
    public function get($fromTime){

        $transactions = Transaction::when($fromTime, function($query ,$fromTime){
            return $query->where('timestamp', '>=', $fromTime);
        })->orderBy('timestamp')->get();

        return TransactionResource::collection($transactions);
    }

    public function getAllFromQueue(){
        return array_map(function($key){
            return array_merge(['key'=>$key], Redis::hgetall($key));
        }, Redis::keys('*'));
    }

    public function save($data){
        return Transaction::create($data);
    }

    public function addToQueue($data){
        return Redis::hmset(microtime(),$data);
    }

    public function removeFromQueue($key){
        Redis::del($key);
    }

    public function removeAllQueuedItems(){
        $cachedItems = count(Redis::keys('*'));
        Redis::flushdb();
        return $cachedItems;
    }

    public function truncateTable(){
        return DB::table('transactions')->delete();
    }
}
