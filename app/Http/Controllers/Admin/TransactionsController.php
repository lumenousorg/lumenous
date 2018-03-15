<?php

namespace lumenous\Http\Controllers\Admin;

use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\Server;
use ZuluCrypto\StellarSdk\XdrModel\Operation\PaymentOp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TransactionsController extends Controller
{

    /**
     * List all unsigned transactions.
     *
     * @param   PayoutsRepositoryInterface $payoutsRepository
     * @return  \Illuminate\View\View
     */
    public function index(PayoutsRepositoryInterface $payoutsRepository)
    {
        // batch payouts into groups of 100
        $payouts = $payoutsRepository->getUnsigned();

        return view('admin.transactions.index', ['payouts' => $payouts]);
    }

    /**
     * @param PayoutsRepositoryInterface $payoutsRepository
     */
    public function create(PayoutsRepositoryInterface $payoutsRepository)
    {
        $server = Server::testNet();

        $sourceAccountId = config('lumenous.stellar_public_key');

        // create and store unsigned transactions for any unpaid payouts,
        // then associate signers to each transaction
        $payouts = $payoutsRepository->getUnsigned();
        $batches = $payouts->chunk(100);
        foreach ($batches as $batch) {
            $transaction = $server->buildTransaction($sourceAccountId);

            foreach ($batch as $payout) {
                // verify the destination account exists
                $destinationAccountId = $payout->user()->first()->stellar_public_key;
                $destinationAccount = $server->getAccount($destinationAccountId);

                // TODO: we should probably confirm their inflation destination on the fly

                $transaction->addOperation(
                    PaymentOp::newNativePayment(
                        $sourceAccountId,
                        $destinationAccountId,
                        $payout->account_payout_amount
                    )
                );
            }

            // TODO: handle the unsigned envelope

            // TODO: can you submit an unsigned transaction?
            $result = $transaction->submit();
        }
    }

    /**
     * Display all payout transactions which still need to be signed.
     *
     * @param   PayoutsRepositoryInterface $payoutsRepository
     * @return  \Illuminate\View\View
     */
    public function sign(PayoutsRepositoryInterface $payoutsRepository)
    {
        // batch payouts into groups of 100
        $payouts = $payoutsRepository->getUnsigned();

        return view('admin.transactions.sign', ['payouts' => $payouts]);
    }

    /**
     * Handle submitting a signature for the outstanding payout transactions.
     *
     * @param PayoutsRepositoryInterface $payoutsRepository
     */
    public function postSign(PayoutsRepositoryInterface $payoutsRepository)
    {
        $server = Server::testNet();

        $sourceAccountId = config('lumenous.stellar_public_key');

        // TODO: work towards our envelope (need all 3 signatures)


        // batch unsigned payout transactions into groups of 100
        $payouts = $payoutsRepository->getUnsigned();
        $batches = $payouts->chunk(100);
        foreach ($batches as $batch) {
            $transaction = $server->buildTransaction($sourceAccountId);

            foreach ($batch as $payout) {
                // verify the destination account exists
                $destinationAccountId = $payout->user()->first()->stellar_public_key;
                $destinationAccount = $server->getAccount($destinationAccountId);

                // TODO: we should probably confirm their inflation destination on the fly

                $transaction->addOperation(
                    PaymentOp::newNativePayment(
                        $sourceAccountId,
                        $destinationAccountId,
                        $payout->account_payout_amount
                    )
                );
            }

            // TODO: sign the transaction with our envelope
            foreach ($signatures as $signature) {

            }

            // TODO:
        }
    }
}
