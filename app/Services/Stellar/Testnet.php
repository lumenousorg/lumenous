<?php

namespace lumenous\Services\Stellar;

use Log;
use Cache;
use Config;
use GuzzleHttp\Client;
use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\Server;

class Testnet {

    /**
     * Horizon Base URLS for live & test
     */
    CONST HORIZON_BASE_URL = 'https://horizon.stellar.org/';
    CONST HORIZON_TEST_BASE_URL = 'https://horizon-testnet.stellar.org/';

    /**
     * @var Server
     */
    public $server;

    /**
     * @var bool
     */
    public $useTestnet;

    /**
     * @var Client
     */
    public $guzzleClient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->useTestnet   = (bool) Config::get('lumenous.horizon_test_mode');
        $this->server       = self::usingTestnet() ? Server::testNet() : Server::publicNet();
        $this->guzzleClient = new Client(['base_uri' => self::usingTestnet() ? self::HORIZON_TEST_BASE_URL : self::HORIZON_BASE_URL]);
    }

    /**
     * Check if we're utilizing testnet.
     *
     * @return bool
     */
    public static function usingTestnet()
    {
        return (bool) config('lumenous.horizon_test_mode', true);
    }

    /**
     * Get the recipient public key.
     *
     * @return mixed
     */
    public function getPublicKey()
    {
        if (self::usingTestnet()) {
            $accountInfo = $this->createRecipientAccount();
            return $accountInfo['publicKey'];
        }

        return false;
    }

    /**
     * Handle returning keypair.
     *
     * @return mixed
     */
    public function getKeypair()
    {
        if (self::usingTestnet()) {
            $accountInfo = $this->createRecipientAccount();

            return [
                'public_key' => $accountInfo['publicKey'],
                'private_key' => $accountInfo['secretKey']
            ];
        }

        return false;
    }

    /**
     * Check on the existence of an account.
     *
     * @param   string  $publicKey
     * @return  null|\ZuluCrypto\StellarSdk\Model\Account
     */
    public function checkAccount($publicKey)
    {
        return $this->server->getAccount($publicKey);
    }

    /**
     * Get recipient account info.
     *
     * @return mixed
     */
    public function getRecipientAccount()
    {
        // verify if we have testnet recipient account info to use
        $accountInfo = Cache::get('recipient-account');
        if ($accountInfo) {
            try {

                // verify the testnet account still exists
                $account = $this->checkAccount($accountInfo['publicKey']);

                // return account info as it's still valid
                return $accountInfo;

            } catch (\Exception $e) {
                Log::error('The testnet account no longer exists.', [
                    'msg' => $e->getMessage(),
                    'public_key' => $accountInfo['publicKey']
                ]);
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function createRecipientAccount()
    {
        // verify if we have testnet recipient account info to use
        $accountInfo = $this->getRecipientAccount();
        if (!empty($accountInfo)) {
            return $accountInfo;
        }

        // create and fund a testnet account because we have none existing
        $accountInfo = $this->createAccount();

        Log::debug('Created new testnet recipient account.', [
            'id' => $accountInfo['id'],
            'public_key' => $accountInfo['publicKey']
        ]);

        // store the new account details in the cache
        Cache::forever('recipient-account', $accountInfo);

        return $accountInfo;
    }

    /**
     * Create a stellar testnet account and fund it.
     *
     * @param boolean $fundAccount
     * @return boolean|array array holding account information id/public/secret
     */
    public function createAccount($fundAccount = true)
    {
        if (!self::usingTestnet()) {
            return false;
        }

        $keypair    = Keypair::newFromRandom();
        $id         = $keypair->getAccountId();
        $publicKey  = $keypair->getPublicKey();
        $secretKey  = $keypair->getSecret();

        if ($fundAccount) {
            $accountFunded = $this->fundAccount($publicKey);
            if (!$accountFunded) {
                return $accountFunded;
            }
        }

        return compact('id', 'publicKey', 'secretKey');
    }

    /**
     * Fund a test account by its public key.
     *
     * @param   string $publicKey
     * @return  boolean
     */
    public function fundAccount($publicKey)
    {
        if (empty($publicKey)) {
            return false;
        }

        $response = $this->_sendRequest("friendbot?addr={$publicKey}");
        if (!$response) {
            return $response;
        }

        return true;
    }

    /**
     * send a guzzle HTTP request.
     *
     * @param string $uri
     * @param string $method
     * @param array $options
     * @param boolean $jsonFormat
     * @return mixed
     */
    protected function _sendRequest($uri, $method = 'GET', $options = [], $jsonFormat = true)
    {
        try {

            $response = $this->guzzleClient->request($method, $uri, $options);
            $response = $jsonFormat ? json_decode($response->getBody()->getContents(), true) : $response->getBody();

            return $response;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorCode = $errorResponse->getStatusCode();
                $errorMessage = $errorResponse->getReasonPhrase();

                Log::error('Error getting operation', [
                    'errorCode' => $errorCode,
                    'errorMessage' => $errorMessage
                ]);
            }

            return false;
        }
    }

}
