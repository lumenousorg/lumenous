<?php

namespace lumenous\Services\Stellar;

use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\Repositories\Interfaces\ActiveAccountsRepositoryInterface;
use lumenous\Services\StellarService;
use lumenous\Models\InflationEffect;
use lumenous\Models\ActiveAccount;
use Illuminate\Support\Facades\Log;
use ZuluCrypto\StellarSdk\Model\StellarAmount;

class Payout {

    CONST PAYOUT_TRANSACTION_FEES = 100; // 100 stroops/operation , one payment operation per transaction 

    /**
     * @var PayoutsRepositoryInterface 
     */

    protected $payoutsRepository;

    /**
     * @var ActiveAccountsRepositoryInterface 
     */
    protected $activeAccountsRepository;

    /**
     * @var StellarService 
     */
    protected $stellarService;

    /**
     * Total amount received from inflation in Stroops.
     * 
     * @var Integer 
     */
    protected $amountReceivedFromInflation;

    /**
     * Total amount of lumens available in the pool in Stroops.
     * 
     * @var Integer 
     */
    protected $totalActiveAccountsBalance;

    /**
     * Current inflation effect.
     * 
     * @var InflationEffect 
     */
    protected $inflationEffect;

    /**
     * Default Constructor.
     * 
     * @param PayoutsRepositoryInterface $payoutsRepository  
     * @param ActiveAccountsRepositoryInterface $activeAccountsRepository  
     * @param StellarService $stellarService  
     * @return void
     */
    public function __construct(PayoutsRepositoryInterface $payoutsRepository, ActiveAccountsRepositoryInterface $activeAccountsRepository, StellarService $stellarService)
    {
        $this->payoutsRepository = $payoutsRepository;
        $this->activeAccountsRepository = $activeAccountsRepository;
        $this->stellarService = $stellarService;
    }

    /**
     * init function.
     */
    public function init()
    {
        $this->totalActiveAccountsBalance = $this->getActiveAccountsTotalBalance();
    }

    /**
     * Execute charity payout from active accounts.
     * 
     * @param array $activeAccounts
     * @return boolean
     */
    public function executeCharityPayout($activeAccounts)
    {
        if (empty($activeAccounts) || empty($this->inflationEffect)) {
            return FALSE;
        }

        $amount = $this->calculateCharityPayoutAmount($activeAccounts);
        if (empty($amount)) {
            return TRUE;
        }
        // TODO : save transaction hashes for charity payments
        $charityPublicKey = config('lumenous.charity_public_key');
        try {
            //TODO : Make sure amount is bigger than specific threshold
            //TODO : add multi-sig signing
            $result = $this->sendPayment($charityPublicKey, $amount);
        } catch (\InvalidArgumentException $exc) {
            Log::error('Error While running charity payout',
                       [
                'message' => $exc->getMessage(),
                'charity_total_amount' => $amount,
                'inflation_effect_id' => $this->inflationEffect->id
            ]);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Execute payouts for all active accounts.
     * 
     * @param array $activeAccounts
     * @return array|boolean
     */
    public function executeActiveAccountsPayout($activeAccounts)
    {
        if (empty($activeAccounts) || empty($this->inflationEffect)) {
            return FALSE;
        }

        $payouts = [];

        foreach ($activeAccounts as $activeAccount) {
            $payout = $this->executeSingleActiveAccountPayout($activeAccount);
            if (!$payout) {
                continue;
            }
            $payouts[] = $payout;
        }

        return $payouts;
    }

    /**
     * Execute payout of a single active account.
     * 
     * @param ActiveAccount $activeAccount
     * @return mixed
     */
    protected function executeSingleActiveAccountPayout($activeAccount)
    {
        $totalPayoutAmount = $this->calculateAcitveAccountTotalPayoutAmount($activeAccount);
        $accountPayoutAmount = $this->calculateActiveAccountPayoutAmount($activeAccount);
        $charityPayoutAmount = $this->calculateActiveAccountCharityPayoutAmount($activeAccount);

        $payout = $this->payoutsRepository->create([
            'total_payout_amount' => $totalPayoutAmount,
            'transaction_fee' => self::PAYOUT_TRANSACTION_FEES,
            'account_payout_amount' => $accountPayoutAmount,
            'charity_payout_amount' => $charityPayoutAmount,
//                TODO : ADD signed and submitted
//                'signed' => false,
            'donation_percentage'=>$activeAccount->user->donation_percentage,
            'user_id' => $activeAccount->user->id,
            'inflation_effect_id' => $this->inflationEffect->id
        ]);

        if (empty($accountPayoutAmount)) {
            return $payout;
        }

        // TODO : Mark payout as failed in case an error occurs to easily retry it later
        try {
            $result = $this->sendPayment($activeAccount->user->stellar_public_key, $accountPayoutAmount);

            if (!$result) {
                return FALSE;
            }
            
            $this->payoutsRepository->update($payout->id, [
                'transaction_hash' => $result->getField('hash'),
                'submitted' => true
            ]);
            //TODO : add multi-sig signing
        } catch (\InvalidArgumentException $exc) {
            Log::error('Error While running user payouts',
                       [
                'message' => $exc->getMessage(),
                'total_payout_amount' => $totalPayoutAmount,
                'transaction_fee' => self::PAYOUT_TRANSACTION_FEES,
                'account_payout_amount' => $accountPayoutAmount,
                'charity_payout_amount' => $charityPayoutAmount,
                'user_id' => $activeAccount->user->id,
                'inflation_effect_id' => $this->inflationEffect->id
            ]);
            return FALSE;
        }

        return $payout;
    }

    /**
     * Send lumens to an account.
     * 
     * @param string $publicKey
     * @param integer $amount
     * @return mixed
     */
    protected function sendPayment($publicKey, $amount)
    {
        return $this->stellarService->sendNativePayment($publicKey, $amount);
    }

    /**
     * Using array of active accounts, get the total amount to be paid to charity.
     * 
     * @param array $activeAccounts
     * @return Integer
     */
    public function calculateCharityPayoutAmount($activeAccounts)
    {
        $amount = 0;
        foreach ($activeAccounts as $activeAccount) {
            $amount += $this->calculateActiveAccountCharityPayoutAmount($activeAccount);
        }

        return empty($amount) ? 0 : $amount - self::PAYOUT_TRANSACTION_FEES;
    }

    /**
     * Calculate amount of a specific active account to be payed to charity. 
     * 
     * @param ActiveAccount $activeAccount
     * @return Integer
     */
    public function calculateActiveAccountCharityPayoutAmount($activeAccount)
    {
        $charityPayoutRatio = $this->calculateActiveAccountCharityPayoutRatio($activeAccount->balance, $activeAccount->user->donation_percentage);
        return $this->amountReceivedFromInflation * $charityPayoutRatio;
    }

    /**
     * Calculate total amount in Stroops to be payed to an active account holder while factoring in charity donation and transaction fees.
     * 
     * @param ActiveAccount $activeAccount
     * @return Integer
     */
    public function calculateActiveAccountPayoutAmount($activeAccount)
    {
        $payoutRatio = $this->calculateActiveAccountPayoutRatio($activeAccount->balance, $activeAccount->user->donation_percentage);

        if (empty($payoutRatio)) {
            return 0;
        }

        $amount = ($payoutRatio * $this->amountReceivedFromInflation) - self::PAYOUT_TRANSACTION_FEES;

        // make sure transaction fees can be covered
        return ($amount <= 0) ? 0 : $amount;
    }

    /**
     * Calculate total amount in stroops to be payed to an active account holder without factoring in charity donation nor transaction fees.
     * 
     * @param ActiveAccount $activeAccount
     * @return Integer
     */
    public function calculateAcitveAccountTotalPayoutAmount($activeAccount)
    {
        $totalPayoutRatio = $this->calculateActiveAccountTotalPayoutRatio($activeAccount->balance);
        return $totalPayoutRatio * $this->amountReceivedFromInflation;
    }

    /**
     * Calculate the ratio to be payed to charity of a specific active account. 
     * 
     * @param Integer $activeAccountBalance
     * @return float
     */
    protected function calculateActiveAccountCharityPayoutRatio($activeAccountBalance, $donationPercentage)
    {
        return $this->calculateActiveAccountTotalPayoutRatio($activeAccountBalance) * ( $donationPercentage / 100);
    }

    /**
     * Calculate the ratio to be payed to an active account holder while factoring in charity donation. 
     * 
     * @param Integer $activeAccountBalance
     * @return float
     */
    protected function calculateActiveAccountPayoutRatio($activeAccountBalance, $donationPercentage)
    {
        return $this->calculateActiveAccountTotalPayoutRatio($activeAccountBalance) * (1 - ($donationPercentage / 100));
    }

    /**
     * Calculate the total ratio to be payed to an active account holder without factoring in charity donation.  
     * 
     * @param Integer $activeAccountBalance
     * @return float
     */
    protected function calculateActiveAccountTotalPayoutRatio($activeAccountBalance)
    {
        return ($activeAccountBalance / $this->totalActiveAccountsBalance);
    }

    /**
     * Get total balance of all active accounts in the pool.
     * 
     * @return Integer
     */
    protected function getActiveAccountsTotalBalance()
    {
        return $this->activeAccountsRepository->getTotalBalance();
    }

    /**
     * Convert Lumens to Stroops.
     * 
     * @param Integer $amount
     * @return Integer
     */
    protected function convertToStroops($amount = 0)
    {
        return $amount * StellarAmount::STROOP_SCALE;
    }

    /**
     * Inflation effect getter.
     * 
     * @return InflationEffect
     */
    function getInflationEffect()
    {
        return $this->inflationEffect;
    }

    /**
     * Inflation effect setter.
     * 
     * @param InflationEffect $inflationEffect
     */
    function setInflationEffect(InflationEffect $inflationEffect)
    {
        $this->inflationEffect = $inflationEffect;
        $this->setAmountReceivedFromInflation($inflationEffect->amount);
    }

    /**
     * amountReceivedFromInflation getter.
     * 
     * @return Integer
     */
    function getAmountReceivedFromInflation()
    {
        return $this->amountReceivedFromInflation;
    }

    /**
     * amountReceivedFromInflation setter.
     * 
     * @param Integer $amountReceivedFromInflation
     */
    function setAmountReceivedFromInflation($amountReceivedFromInflation)
    {
        $this->amountReceivedFromInflation = $this->convertToStroops($amountReceivedFromInflation);
    }

}
