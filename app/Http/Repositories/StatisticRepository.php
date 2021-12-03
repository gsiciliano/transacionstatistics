<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Redis;
use App\Http\Resources\StatisticResource;


class StatisticRepository
{
    public function all(){
        $lastTransactionAmount = array_map(function($key){
            return (float) Redis::hgetall($key)['amount'];
        }, Redis::keys('*'));

        return StatisticResource::make($lastTransactionAmount);

    }
}
