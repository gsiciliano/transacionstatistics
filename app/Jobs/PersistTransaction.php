<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Repositories\StatisticRepository;
use App\Http\Repositories\TransactionRepository;

class PersistTransaction implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $statisticRepository = new StatisticRepository();
        $transactionList = $statisticRepository->allFromQueue();

        array_walk($transactionList, function($item) use ($statisticRepository){
            $dataTimeStamp = Carbon::parse($item['timestamp']);
            var_dump($dataTimeStamp->diffInSeconds());
            if ($dataTimeStamp->diffInSeconds() > config('time-limits.statistics.seconds')) {
                $transactionRepository = new TransactionRepository();
                if($transactionRepository->save(['amount'=>$item['amount'],'timestamp'=>$item['timestamp']])){
                    $statisticRepository->removeFromQueue($item['key']);
                }
            }
        });
    }
}
