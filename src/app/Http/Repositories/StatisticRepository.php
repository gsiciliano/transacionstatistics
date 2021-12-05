<?php

namespace App\Http\Repositories;

use App\Http\Resources\StatisticResource;

class StatisticRepository
{
    public function all($transactionsList){
        $transactionAmountList = array_map(function($item){
            return (float) $item['amount'];
        }, $transactionsList);
        return StatisticResource::make($transactionAmountList);
    }

}
