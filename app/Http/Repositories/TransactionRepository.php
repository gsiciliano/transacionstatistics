<?php

namespace App\Http\Repositories;

use App\Models\Transaction;
use App\Resources\TransactionResource;

class TransactionRepository
{
    public function get($fromTime){

        $transactions = Transaction::when($fromTime, function($query ,$fromTime){
            return $query->where('timestamp', '>=', $fromTime);
        })->orderBy('timestamp')->get();

        return TransactionResource::collection($transactions);
    }

    public function save($data){
        return Transaction::create($data);
    }
}
