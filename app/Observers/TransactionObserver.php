<?php

namespace App\Observers;

use App\Jobs\PersistTransaction;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        PersistTransaction::dispatch();
    }

}
