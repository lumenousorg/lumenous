<?php

namespace lumenous\Services;

use Log;
use Cache;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use lumenous\Services\Stellar\Testnet;
use ZuluCrypto\StellarSdk\Server;
use lumenous\Models\InflationEffect;
use ZuluCrypto\StellarSdk\Horizon\ApiClient;
use ZuluCrypto\StellarSdk\Model\Ledger;
use ZuluCrypto\StellarSdk\Keypair;
use ZuluCrypto\StellarSdk\XdrModel\Operation\SetOptionsOp;
use phpseclib\Math\BigInteger;
use ZuluCrypto\StellarSdk\Horizon\Exception\PostTransactionException;

class StellarService {

    /**
     * Base URL for fetching inflation pool data.
     */
    CONST INFLATION_POOL_URL = 'https://fed.network/inflation/';

    /**
     * Horizon Base URLS for live & test
     */
    CONST HORIZON_BASE_URL = 'https://horizon.stellar.org/';
    CONST HORIZON_TEST_BASE_URL = 'https://horizon-testnet.stellar.org/';

    /**
     * Operation types
     */
    CONST OPERATION_MANAGE_DATA = 10;
    CONST OPERATION_INFLATION = 9;
    CONST OPERATION_PATH_PAYMENT = 2;

    /**
     * Indicates whether to use test Horizon or live.
     * 
     * @var boolean 
     */
    protected $isTestMode;

    /**
     * Client used to send requests.
     * 
     * @var Client 
     */
    protected $guzzleClient;

    /**
     * Stellar SDK server instance
     * 
     * @var Server 
     */
    protected $server;

    /**
     * Array holding public/secret stellar keys.
     * 
     * @var array 
     */
    protected $appCredentials;

    /**
     * Default Constructor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Check if using Horizon testnet.
     *
     * @return bool
     */
    public static function usingTestnet()
    {
        return (bool) Config::get('lumenous.horizon_test_mode', true);
    }

    /**
     * Get the current Stellar Horizon client server instance.
     *
     * @return Server
     */
    public static function getServer()
    {
        return self::usingTestnet() ? Server::testNet() : Server::publicNet();
    }

    /**
     * Init function.
     */
    public function init()
    {
        $this->isTestMode       = self::usingTestnet();
        $this->appCredentials   = $this->getAppCredentials();
        $this->server           = self::getServer();
        $this->guzzleClient     = new Client([
            'base_uri' => self::usingTestnet() ? self::HORIZON_TEST_BASE_URL : self::HORIZON_BASE_URL
        ]);
    }

    /**
     * Handle retrieving app credentials.
     *
     * @return array
     */
    public static function getAppCredentials()
    {
        // obtain keypair via cache lookup or dynamic creation
        if (self::usingTestnet()) {
            $stellarTestnet = new Testnet();

            return $stellarTestnet->getKeypair();
        }

        return [
            'public_key' => config('lumenous.stellar_public_key'),
            'secret_key' => config('lumenous.stellar_secret_key')
        ];
    }

    /**
     * Get the public key.
     *
     * @return mixed
     */
    public static function getPublicKey()
    {
        // obtain public key via cache lookup or dynamic creation
        if (self::usingTestnet()) {
            $stellarTestnet = new Testnet();

            return $stellarTestnet->getPublicKey();
        }

        return config('lumenous.stellar_public_key');
    }

    /**
     * Get the inflation pool data of a specific account.
     * 
     * @param string $publicKey
     * @return mixed
     */
    protected function getInflationData($publicKey)
    {
        if (empty($publicKey)) {
            return false;
        }

        $client = new Client(['base_uri' => self::INFLATION_POOL_URL]);

        try {

            $response = $client->request('GET', $publicKey);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorCode = $errorResponse->getStatusCode(); // 501
                $errorMessage = $errorResponse->getReasonPhrase(); // "Not Implemented"
            }

            return false;
        }

        return $response->getBody()->getContents();
    }

    /**
     * Get the inflation pool entries for a specific account.
     * 
     * @param string $publicKey
     * @return mixed
     */
    public function getInflationPoolEntries($publicKey)
    {
        $inflationData = $this->getInflationData($publicKey);

        if (!$inflationData) {
            return $inflationData;
        }

        $inflationData = json_decode($inflationData, true);

        if (empty($inflationData['entries'])) {
            return false;
        }

        return $inflationData['entries'];
    }

    /**
     * Get effects of an account. Supports streaming efects with callback.
     * 
     * @param string $publicKey
     * @param string $type
     * @param string $cursor
     * @param integer $limit
     * @param string $order
     * @param boolean $stream
     * @param function $streamCallback
     * @return mixed
     */
    public function getAccountEffects(
    $publicKey,
    $type = null,
    $cursor = null,
    $limit = null,
    $order = 'desc',
    $stream = false,
    $streamCallback = null
    )
    {
        $options = [
            'query' => compact('cursor', 'limit', 'order')
        ];

        // Set appropriate headers/request options for streaming
        if ($stream) {
            // If no callback and stream is true, return false
            if (empty($streamCallback)) {
                return false;
            }
            $options = $this->_prepareStreamRequestOptions($options);
        }

        $body = $this->_sendRequest("accounts/{$publicKey}/effects", 'GET', $options, false);

        return $this->_parseGetAndStreamResponse($body, $stream, $type, $streamCallback);
    }

    /**
     * Get operations of an account. Supports streaming operations with callback.
     * 
     * @param string $publicKey
     * @param string $type
     * @param string $cursor
     * @param integer $limit
     * @param string $order
     * @param boolean $stream
     * @param function $streamCallback
     * @return mixed
     */
    public function getAccountOperations(
            $publicKey, 
            $type = null, 
            $cursor = null, 
            $limit = null, 
            $order = 'desc', 
            $stream = false, 
            $streamCallback = null)
    {

        $options = [
            'query' => compact('cursor', 'limit', 'order')
        ];

        // Set appropriate headers/reuqest options for streaming
        if ($stream) {
            // If no callback and stream is true, return false
            if (empty($streamCallback)) {
                return false;
            }
            $options = $this->_prepareStreamRequestOptions($options);
        }

        $body = $this->_sendRequest("accounts/{$publicKey}/operations", 'GET', $options, false);

        return $this->_parseGetAndStreamResponse($body, $stream, $type, $streamCallback);
    }
    
    /**
     * Prepare request options for streaming request.
     * 
     * @param type $options
     * @return string
     */
    protected function _prepareStreamRequestOptions($options)
    {
        $options['stream'] = true;
        $options['read_timeout'] = null;
        $options['headers'] = [
            'Accept' => 'text/event-stream',
        ];
        return $options;
    }

    /**
     * Parses the response back from an endpoint which can fetch/stream data.
     * 
     * @param String $body
     * @param Boolean $stream
     * @param String $type
     * @param function $streamCallback
     * @return mixed
     */
    protected function _parseGetAndStreamResponse($body, $stream, $type, $streamCallback)
    {
        // If no stream, return results
        if (!$stream) {

            $contents = json_decode($body, true);
            $contents = collect($contents['_embedded']['records']);

            // Filter specific operation type
            if (!empty($type)) {
                $contents = $contents->filter(function($operation) use($type) {
                    return $operation['type_i'] == $type;
                });
            }

            return $contents->all();
        }

        // If stream, parse results and return one by one in callback 
        while (!$body->eof()) {
            $line = '';

            $char = null;
            while ($char != "\n") {
                $line .= $char;
                $char = $body->read(1);
            }

            // Ignore empty lines
            if (!$line)
                continue;

            // Ignore "data: hello" handshake
            if (strpos($line, 'data: "hello"') === 0)
                continue;

            // Ignore lines that don't start with "data: "
            $sentinel = 'data: ';
            if (strpos($line, $sentinel) !== 0)
                continue;

            // Remove sentinel prefix
            $json = substr($line, strlen($sentinel));

            $decoded = json_decode($json, true);

            if ($decoded) {
                // Filter specific operation type
                if (!empty($type)) {
                    if ($decoded['type_i'] != $type) {
                        continue;
                    }
                }
                $streamCallback($decoded);
            }
        }
    }

    /**
     * Stream ledgers with callback function.
     * 
     * @param string $cursor
     * @param callable $callback
     * @return void
     */
    public function streamLedgers($cursor = 'now', $callback)
    {
        if (empty($callback)) {
            return false;
        }

        if ($this->isTestMode) {
            $client = ApiClient::newTestnetClient();
        } else {
            $client = ApiClient::newPublicClient();
        }

        $client->streamLedgers($cursor, $callback);
    }

    /**
     * Send lumens from app account to any other account.
     * 
     * @param string $sendToKey
     * @param integer $amount
     * @return mixed
     */
    public function sendNativePayment($sendToKey, $amount)
    {
        return $this->sendNativePaymentFrom($sendToKey, $amount, $this->appCredentials['public_key'], $this->appCredentials['secret_key']);
    }
    
    /**
     * Send lumens from any account to any other account.
     * 
     * @param string $toPublicKey
     * @param integer $amount
     * @param string $fromPublicKey
     * @param string $fromSecretKey
     * @return mixed
     */
    public function sendNativePaymentFrom($toPublicKey, $amount, $fromPublicKey, $fromSecretKey)
    {
        if (empty($toPublicKey) || empty($amount) || empty($fromPublicKey) || empty($fromSecretKey)) {
            return false;
        }

        try {
            // using Big Integer to make sure that passed value is in STROOPS
            $result = $this->server->getAccount($fromPublicKey)->sendNativeAsset($toPublicKey, new BigInteger($amount), $fromSecretKey);
            
            } catch (PostTransactionException $e) {
            Log::error('Unable to send payment',
                       [
                'message' => $e->getMessage(),
                'send_to_key' => $toPublicKey,
                'send_from_key' => $fromPublicKey,
                'amount' => $amount
            ]);
            return FALSE;
        } catch (\Exception $e) {
            Log::error('Unable to send payment',
                       [
                'message' => $e->getMessage(),
                'send_to_key' => $toPublicKey,
                'send_from_key' => $fromPublicKey,
                'amount' => $amount
            ]);
            return FALSE;
        }

        return $result;
    }

    /**
     * Get account native balance.
     * 
     * @param string $publicKey
     * @param boolean $inStroops
     * @return mixed
     */
    public function getNativeBalance($publicKey, $inStroops = false)
    {
        if (empty($publicKey)) {
            return false;
        }

        $account = $this->server->getAccount($this->appCredentials['public_key']);

        return $inStroops ? $account->getNativeBalanceStroops() : $account->getNativeBalance();
    }

    /**
     * Using a local inflation object, return the sequence number of the associated ledger.
     * 
     * @param InflationEffect $effect
     * @return mixed
     */
    public function getLedgerSequenceFromInflationEffect(InflationEffect $effect)
    {
        $operationLink = $effect->data['_links']['operation']['href'];
        $operationID = explode('/operations/', $operationLink)[1];

        $operation = $this->getOperation($operationID);

        if (!$operation) {
            return $operation;
        }

        $transactionHash = $operation['transaction_hash'];

        $transaction = $this->getTransaction($transactionHash);

        if (!$transaction) {
            return $transaction;
        }

        return $transaction['ledger'];
    }

    /**
     * Get a specific operation using its ID. 
     * 
     * @param string $id
     * @return mixed
     */
    public function getOperation($id)
    {
        if (empty($id)) {
            return false;
        }

        return $this->_sendRequest("operations/{$id}");
    }

    /**
     * Get a specific transaction using its hash. 
     * 
     * @param string $hash
     * @return mixed
     */
    public function getTransaction($hash)
    {
        if (empty($hash)) {
            return false;
        }

        return $this->_sendRequest("transactions/{$hash}");
    }

    /**
     * Get a specific ledger using its sequence number. 
     * 
     * @param string $sequence
     * @return mixed
     */
    public function getLedger($sequence)
    {
        if (empty($sequence)) {
            return false;
        }

        return $this->_sendRequest("ledgers/{$sequence}");
    }

    /**
     * Set the Inflation destination of an account.
     * 
     * @param String $publicKey
     * @param String $secretKey
     * @param String $inflationDestination
     * @return mixed
     */
    public function SetInflationDestination($publicKey, $secretKey, $inflationDestination)
    {
        if (empty($publicKey) || empty($secretKey) || empty($inflationDestination)) {
            return FALSE;
        }

        $optionsOperation = new SetOptionsOp();
        $optionsOperation->setInflationDestination($inflationDestination);

        return $this->server->buildTransaction($publicKey)
                        ->addOperation($optionsOperation)
                        ->submit($secretKey);
    }

    /**
     * Create a number of test accounts.
     * 
     * @param Integer $numberOfAccounts
     * @return boolean|array
     */
    public function createAccounts($numberOfAccounts = 10)
    {
        if (!$this->isTestMode) {
            return false;
        }

        $accounts = [];
        for ($i = 0; $i < $numberOfAccounts; $i++) {
            $accounts[] = $this->createAccount();
        }

        return $accounts;
    }

    /**
     * Create a stellar test account and fund it.
     * 
     * @param boolean $fundAccount
     * @return boolean|array array holding account information id/public/secret
     */
    public function createAccount($fundAccount = true)
    {
        if (!$this->isTestMode) {
            return false;
        }

        $keypair = Keypair::newFromRandom();

        $id = $keypair->getAccountId();
        $publicKey = $keypair->getPublicKey();
        $secretKey = $keypair->getSecret();

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
     * @param String $publicKey
     * @return boolean
     */
    public function fundAccount($publicKey)
    {
        if (empty($publicKey)) {
            return FALSE;
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

        $response = $jsonFormat ? json_decode($response->getBody()->getContents(), true) : $response->getBody();

        return $response;
    }

//    public function getPayments($publicKey, $type = null, $limit = 10, $order = 'desc')
//    {
//        if (empty($publicKey)) {
//            return false;
//        }
//
//        $client = new Client(['base_uri' => $this->isTestMode ? self::HORIZON_TEST_BASE_URL : self::HORIZON_BASE_URL]);
//
//        try {
//            $response = $client->request('GET', "accounts/{$publicKey}/payments", [
//                'query' => compact('limit', 'order')
//            ]);
//        } catch (RequestException $e) {
//            if ($e->hasResponse()) {
//                $errorResponse = $e->getResponse();
//                $errorCode = $errorResponse->getStatusCode();
//                $errorMessage = $errorResponse->getReasonPhrase();
//            }
//            return false;
//        }
//
//        $contents = json_decode($response->getBody()->getContents(), true);
//        $contents = collect($contents['_embedded']['records']);
//
//        if (!empty($type)) {
//            $contents = $contents->filter(function($payment) use($type) {
//                return $payment['type_i'] == $type;
//            });
//        }
//
//        return $contents->all();
//    }

    /**
     * Get dest key set at the inflation pool details of an account.
     * 
     * @param string $publicKey
     * @return mixed
     */
//    public function getInflationDestinationKey($publicKey)
//    {
//        $inflationData = $this->getInflationData($publicKey);
//
//        if (!$inflationData) {
//            return $inflationData;
//        }
//
//        $inflationData = json_decode($inflationData, true);
//
//        if (empty($inflationData['inflationdest'])) {
//            return false;
//        }
//
//        return $inflationData['inflationdest'];
//    }
}
