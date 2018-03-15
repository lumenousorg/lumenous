<?php

namespace lumenous\Repositories;

use lumenous\Models\Signer;
use lumenous\Models\Transaction;
use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\Models\Payout;
use lumenous\User;

class EloquentSignersRepository extends EloquentAbstractRepository implements SignerssRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\Signer';
    }

    /**
     * Find a specific signer of a transaction.
     *
     * @param   User        $user
     * @param   Transaction $transaction
     */
    public function locate(User $user, Transaction $transaction)
    {
        return $transaction
            ->signers()
            ->where('signers.user_id', '=', $user->id)
            ->first();
    }

    /**
     * Return a collection of signers for a given transaction.
     *
     * @param   string  $tag
     * @return  mixed
     */
    public function getByTransaction(Transaction $transaction)
    {
        return $transaction->signers()->get();
    }

    /**
     * Add a signer to the transaction.
     *
     * @param   Transaction $transaction
     * @param   array       $signer
     * @return  mixed
     */
    public function addSigner(Transaction $transaction, $signer = [])
    {
        // ensure a valid user exists tied to the signer
        $user = User::where('id', '=', $signer['user_id'])->first();

        // TODO: ensure the user is an admin on the site

        // TODO: also compare against our whitelist of signers for security

        return Signer::create([
            'transaction_id' => $transaction->id,
            'user_id' => $signer['user_id'],
            'signed' => 0
        ]);
    }

}
