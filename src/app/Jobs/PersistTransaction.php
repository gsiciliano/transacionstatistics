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
        $transactionRepository = new TransactionRepository();
        $transactionsList = $transactionRepository->getAllFromQueue();

        array_walk($transactionsList, function($transaction){
            $dataTimeStamp = Carbon::parse($transaction['timestamp']);
            if ($dataTimeStamp->diffInSeconds() > config('time-limits.statistics.seconds')) {
                $transactionRepository = new TransactionRepository();
                if($transactionRepository->save(['amount'=>$transaction['amount'],'timestamp'=>$transaction['timestamp']])){
                    $transactionRepository->removeFromQueue($transaction['key']);
                }
            }
        });
    }
}
