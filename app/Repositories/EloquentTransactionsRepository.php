<?php

namespace lumenous\Repositories;

use Carbon\Carbon;
use lumenous\Models\Transaction;
use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\Models\Payout;

class EloquentTransactionsRepository extends EloquentAbstractRepository implements TransactionsRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\Transaction';
    }

    /**
     * Return a transaction by it's tag.
     *
     * @param   string  $tag
     * @return  mixed
     */
    public function getByTag($tag)
    {
        return Transaction::where('tx_tag', '=', $tag)->first();
    }

    /**
     * Handle marking the transaction as signed.
     *
     * @param   Transaction $transaction
     * @return  mixed
     */
    public function markAsSigned(Transaction $transaction)
    {
        $transaction->fill([
            'signed' => true,
            'signed_at' => Carbon::now()
        ]);

        return $transaction->save();
    }

    /**
     * Handle marking the transaction as submitted.
     *
     * @param   Transaction $transaction
     * @return  mixed
     */
    public function markAsSubmitted(Transaction $transaction)
    {
        $transaction->fill([
            'submitted' => true,
            'submitted_at' => Carbon::now()
        ]);

        return $transaction->save();
    }

}
