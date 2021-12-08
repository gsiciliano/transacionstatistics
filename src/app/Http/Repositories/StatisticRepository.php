<?php

namespace App\Http\Repositories;

use App\Http\Resources\StatisticResource;

class StatisticRepository
{
    /**
     * Get statistics for queued transactions
     *
     * @param array transactionsList
     * @return App\Http\Resources\StatisticResource transactionStatistics
     */
    public function all($transactionsList){
        $transactionAmountList = array_map(function($item){
            return (float) $item['amount'];
        }, $transactionsList);
        return StatisticResource::make($transactionAmountList);
    }

}
