<?php

namespace lumenous\Services\Stellar;

use Exception;
use Illuminate\Support\Facades\Log;
use \ZuluCrypto\StellarSdk\Keypair;
use \ZuluCrypto\StellarSdk\Server;
use \ZuluCrypto\StellarSdk\XdrModel\Operation\SetOptionsOp;
use \ZuluCrypto\StellarSdk\XdrModel\SignerKey;
use \ZuluCrypto\StellarSdk\XdrModel\Signer;
use ZuluCrypto\StellarSdk\Horizon\Exception\PostTransactionException;

class Account {

    /**
     * Indicates whether to use test Horizon or live.
     * 
     * @var boolean 
     */
    protected $isTestMode;

    /**
     * Stellar SDK server instance
     * 
     * @var Server 
     */
    protected $server;

    /**
     * Default Constructor
     */
    public function __construct()
    {
        $this->isTestMode = config('lumenous.horizon_test_mode');

        if ($this->isTestMode) {
            $this->server = Server::testNet();
        } else {
            $this->server = Server::publicNet();
        }
    }

    /**
     * Handle adding a signer to an existing account
     * 
     * @param string $masterAccountPublicKey
     * @param string $masterAccountPrivateKey
     * @param string $signerPublicKey
     * @param string $signerWeight
     * @return boolean
     * @throws Exception
     */
    public function addSigner($masterAccountPublicKey, $masterAccountPrivateKey, $signerPublicKey, $signerWeight = 1)
    {
        if (empty($masterAccountPrivateKey) || empty($masterAccountPrivateKey) || empty($signerPublicKey)) {
            throw new Exception('A key is missing');
        }

        if ($signerWeight < 0 || $signerWeight > 255) {
            throw new Exception('Wrong Weight');
        }

        $newSigner = Keypair::newFromPublicKey($signerPublicKey);
        $signerKey = SignerKey::fromKeypair($newSigner);
        $newAccountSigner = new Signer($signerKey, $signerWeight);

        $optionsOperation = new SetOptionsOp();
        $optionsOperation->updateSigner($newAccountSigner);

        $transaction = $this->server
                ->buildTransaction($masterAccountPublicKey)
                ->addOperation($optionsOperation);

        try {
            $response = $transaction->submit($masterAccountPrivateKey);
        } catch (PostTransactionException $e) {
            Log::error('Unable to add signer to account',
                       [
                'message' => $e->getMessage(),
                'account_public_key' => $masterAccountPublicKey,
                'signer_public_key' => $signerPublicKey,
                'signer_weight' => $signerWeight
            ]);
            return false;
        } catch (Exception $e) {
            Log::error('Unable to add signer to account',
                       [
                'message' => $e->getMessage(),
                'account_public_key' => $masterAccountPublicKey,
                'signer_public_key' => $signerPublicKey,
                'signer_weight' => $signerWeight
            ]);
            return false;
        }

        return true;
    }

}
