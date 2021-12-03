<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Redis;
use App\Http\Resources\StatisticResource;


class StatisticRepository
{
    public function all(){
        $transactionAmountList = array_map(function($item){
            return (float) $item['amount'];
        }, $this->allFromQueue());
        return empty($transactionAmountList) ? null : StatisticResource::make($transactionAmountList);

    }
    public function allFromQueue(){
        return array_map(function($key){
            return array_merge(['key'=>$key], Redis::hgetall($key));
        }, Redis::keys('*'));
    }

    public function removeFromQueue($key){
        Redis::del($key);
    }
}
