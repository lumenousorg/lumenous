<?php

namespace lumenous\Services\Stellar;

use \ZuluCrypto\StellarSdk\Keypair;
use \ZuluCrypto\StellarSdk\Server;
use \ZuluCrypto\StellarSdk\XdrModel\Operation\SetOptionsOp;
use \ZuluCrypto\StellarSdk\XdrModel\SignerKey;
use \ZuluCrypto\StellarSdk\XdrModel\Signer;

class Signer {

    /**
     * @var SignersRepositoryInterface
     */
    public $signersRepository;

    /**
     * Transaction constructor.
     * @param   SignersRepositoryInterface      $signersRepository
     */
    public function __construct(SignersRepositoryInterface $signersRepository)
    {
        $this->signersRepository = $signersRepository;
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
            // handle performing the actual signing
            $signer = $this->addSigner($transaction, $signer);

            // mark signer as added in the database
            $models[] = $this->signersRepository->addSigner($transaction, $signer);
        }

        return $models;
    }

    /**
     * Add a signer to a payment transaction.
     *
     * @param   string  $sourceAccountPrivateKey
     * @param   string  $destinationAccountId
     * @param   int     $amount
     */
    public function addSigner($sourceAccountPrivateKey, $destinationAccountId, $amount)
    {
        $server = Server::testNet();

        $sourceKeypair = Keypair::newFromSeed($sourceAccountPrivateKey);

        // ensure the destination account exists
        $destinationAccount = $server->getAccount($destinationAccountId);

        // prepare our payment operation
        $paymentOp = PaymentOp::newNativePayment($sourceKeypair->getPublicKey(), $destinationAccountId, $amount);

        // build the payment transaction
        $transaction = $server
            ->buildTransaction($sourceKeypair->getPublicKey())
            ->addOperation($paymentOp);

        // sign and submit the transaction
        $response = $transaction->submit($sourceKeypair->getSecret());
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}