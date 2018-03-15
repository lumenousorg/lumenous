<?php

namespace lumenous\Services\Stellar;

use Log;
use lumenous\Repositories\Interfaces\SignersRepositoryInterface;
use lumenous\Repositories\Interfaces\TransactionsRepositoryInterface;
use lumenous\User;

class Transaction {

    public $signerService;

    /**
     * @var TransactionsRepositoryInterface
     */
    public $transactionRepository;

    /**
     * @var SignersRepositoryInterface
     */
    public $signersRepository;

    /**
     * Transaction constructor.
     * @param   TransactionsRepositoryInterface $transactionsRepository
     * @param   SignersRepositoryInterface      $signersRepository
     */
    public function __construct(
        TransactionsRepositoryInterface $transactionsRepository,
        SignersRepositoryInterface $signersRepository
    )
    {
        $this->transactionRepository = $transactionsRepository;
        $this->signersRepository = $signersRepository;
    }

    /**
     * Find transaction by tag.
     *
     * @param   string  $tag
     * @return  mixed
     */
    public function getByTag($tag)
    {
        return $this->transactionRepository->getByTag($tag);
    }

    /**
     * Given a transaction tag, return the signers.
     *
     * @param   string  $tag
     * @return  bool
     */
    public function getSigners($tag)
    {
        $transaction = $this->getByTag($tag);
        if (empty($transaction)) {
            Log::critical('Could not find transaction by tag when attempting to retrieve signers.', [
                'tag' => $tag
            ]);
            return false;
        }

        return $this->getSignersByTransaction($transaction);
    }

    /**
     * Get signers by transaction.
     *
     * @param \lumenous\Models\Transaction $transaction
     * @return mixed
     */
    public function getSignersByTransaction(\lumenous\Models\Transaction $transaction)
    {
        return $this->signersRepository->getByTransaction($transaction);
    }

    /**
     * Save a transaction.
     *
     * @param   User      $user
     * @param   Request   $request
     * @return  mixed
     */
    public function save(User $user, Request $request)
    {
        // check if the transaction already exists
        $transaction = $this->getBy($request->get('tx_tag'));
        if ($transaction) {
            return $transaction;
        }

        return \lumenous\Models\Transaction::create([
            'user_id'   => $user->id,
            'initiator' => $request->get('source_account'),
            'tx_xdr'    => $request->get('tx_xdr'),
            'tx_tag'    => $request->get('tx_tag'),
            'signed'    => 0,
            'submitted' => 0
        ]);
    }

    /**
     * Add signers to a transaction.
     *
     * @param   \lumenous\Models\Transaction $transaction
     * @param   array   $signers
     * @return  mixed
     */
    public function addSigners(\lumenous\Models\Transaction $transaction, $signers = [])
    {
        $models = [];

        foreach ($signers as $signer) {
            // TODO: handle performing the actual signing


            // mark signer as added in the database
            $models[] = $this->signersRepository->addSigner($transaction, $signer);
        }

        return $models;
    }

    /**
     * Sign a transaction.
     *
     * @param   User    $user
     * @param   Request $request
     */
    public function sign(User $user, Request $request)
    {
        // locate our transaction
        $transaction = $this->getById($request->get('transaction_id'));
        if (empty($transaction)) {
            Log::critical('Could not find a transaction by id to sign.', [
                'transaction_id' => $request->get('transaction_id'),
                'user_id' => !empty($user) ? $user->id : null,
            ]);

            return false;
        }

        // find our signer
        $signer = $this->signersRepository->locate($user, $transaction);
        if (empty($signer)) {
            Log::critical('Could not find a matching signer.', [
                'transaction_id' => $transaction->id,
                'user_id' => !empty($user) ? $user->id : null
            ]);

            return false;
        }

        // mark as signed if we haven't already done so
        if (!$signer->signed) {
            $this->signersRepository->sign($signer);
        }

        // TODO: does this change after each signer?
        // update the transaction XDR
        $this->transactionRepository->update([
            'tx_xdr' => $request->get('tx_xdr')
        ]);

        // check if signing has been completed
        if ($this->hasCompletedSigning($transaction)) {
            return $this->transactionRepository->markAsSigned($transaction);
        }

        return $transaction;
    }

    /**
     * Handle marking a signed transaction as submitted.
     *
     * @param   string  $tag
     * @return  mixed
     */
    public function submit($tag)
    {
        $transaction = $this->getByTag($tag);
        if (empty($transaction)) {
            Log::critical('Could not find transaction by tag when attempting to submit transaction.', [
                'tag' => $tag
            ]);

            return false;
        }

        if (!$transaction->signed) {
            Log::critical('Transaction cannot be submitted until it has been fully signed.', [
                'transaction_id' => $transaction->id
            ]);

            return false;
        }

        return $this->transactionRepository->markAsSubmitted($transaction);
    }

    /**
     * Check if we've completed signing a transaction.
     *
     * @param \lumenous\Models\Transaction $transaction
     * @return bool
     */
    public function hasCompletedSigning(\lumenous\Models\Transaction $transaction)
    {
        $signers = $this->getSignersByTransaction($transaction);
        if ($signers->isEmpty()) {
            return false;
        }

        foreach ($signers as $signer) {
            if (!$signer->signed) {
                return false;
            }
        }

        return true;
    }

}