<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use lumenous\Models\InflationEffect;
use lumenous\Services\Stellar\Payout;
use lumenous\Models\ActiveAccount;
use ZuluCrypto\StellarSdk\Model\StellarAmount;
use lumenous\Models\Payout as PayoutModel;

class PayoutTest extends TestCase {

    use RefreshDatabase;

    /**
     * Payout Service
     * 
     * @var Payout 
     */
    protected $payoutService;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->payoutService = $this->app->make(Payout::class);
    }

    /**
     * Active Account Total Payout Test.
     * 
     * @return void
     */
    public function testActiveAccountTotalPayoutAmount()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);


        $activeAccounts = factory(ActiveAccount::class, 4)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $totalPayoutAmount = $this->payoutService->calculateAcitveAccountTotalPayoutAmount($activeAccounts->first());

        $this->assertEquals('25000000000', $totalPayoutAmount);
    }

    /**
     * Active Account Payout Test.
     * 
     * @return void
     */
    public function testActiveAccountPayoutAmount()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);


        $activeAccounts = factory(ActiveAccount::class, 4)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccounts->first());

        $this->assertEquals('12499999900', $payoutAmount);
    }

    /**
     * Active Account Charity Payout Test.
     * 
     * @return void
     */
    public function testActiveAccountCharityPayoutAmount()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);


        $activeAccounts = factory(ActiveAccount::class, 4)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountCharityPayoutAmount($activeAccounts->first());

        $this->assertEquals('12500000000', $payoutAmount);
    }

    /**
     * Random Active Account Test.
     * Total = Payout + Transaction Fee + Charity Payout
     *
     * @return void
     */
    public function testActiveAccountTotalPayoutAmountIsEqualToActiveAccountPayoutAmountAndCharityPayoutAmount()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create();


        $activeAccounts = factory(ActiveAccount::class, 100)->create();

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $activeAccount = $activeAccounts->random();

        $TotalPayoutAmount = $this->payoutService->calculateAcitveAccountTotalPayoutAmount($activeAccount);
        $CharityPayoutAmount = $this->payoutService->calculateActiveAccountCharityPayoutAmount($activeAccount);
        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccount);

        $this->assertEquals($TotalPayoutAmount, $payoutAmount + Payout::PAYOUT_TRANSACTION_FEES + $CharityPayoutAmount);
    }

    /**
     * Test payouts table has correct number of records from executing active accounts payouts.
     *
     * @return void
     */
    public function testPayoutsTableCount()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '1000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 5)->states('real-stellar-account')->create();

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $this->payoutService->executeActiveAccountsPayout($activeAccounts);

        $payouts = PayoutModel::count();

        $this->assertEquals($activeAccounts->count(), $payouts);
    }

    /**
     * Test Single Active Account Payout when donation percentage is 0%.
     *
     * @return void
     */
    public function testActiveAccountPayoutZeroDonationPercentage()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);


        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE,
            'user_id' => function() {
                return factory(\lumenous\User::class)->create([
                            'donation_percentage' => 0
                ]);
            }
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccount);

        $this->assertEquals(24999999900, $payoutAmount);
    }

    /**
     * Test Single Active Account Charity Payout when donation percentage is 0%.
     *
     * @return void
     */
    public function testActiveAccountCharityPayoutZeroDonationPercentage()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);


        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE,
            'user_id' => function() {
                return factory(\lumenous\User::class)->create([
                            'donation_percentage' => 0
                ]);
            }
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountCharityPayoutAmount($activeAccount);

        $this->assertEquals(0, $payoutAmount);
    }

    /**
     * Test Single Active Account Payout when donation percentage is 100%.
     *
     * @return void
     */
    public function testActiveAccountPayoutFullDonationPercentage()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE,
            'user_id' => function() {
                return factory(\lumenous\User::class)->create([
                            'donation_percentage' => 100
                ]);
            }
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccount);

        $this->assertEquals(0, $payoutAmount);
    }

    /**
     * Test Single Active Account Charity Payout when donation percentage is 100%.
     *
     * @return void
     */
    public function testActiveAccountCharityPayoutFullDonationPercentage()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE,
            'user_id' => function() {
                return factory(\lumenous\User::class)->create([
                            'donation_percentage' => 100
                ]);
            }
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountCharityPayoutAmount($activeAccount);

        $this->assertEquals(25000000000, $payoutAmount);
    }

    /**
     * Test Charity payout with default donation percentage 50%.
     *
     * @return void
     */
    public function testCharityPayout()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 4)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateCharityPayoutAmount($activeAccounts);

        $this->assertEquals(49999999900, $payoutAmount);
    }

    /**
     * Test Active Account Payout with balance of  0.0000001 lumen.
     *
     * @return void
     */
    public function testActiveAccountPayoutWithTinyBalance()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '0.0000001' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccount);
        
        $this->assertEquals(0, $payoutAmount);
    }
    /**
     * Test Active Account Payout with balance of 10000000 lumens.
     *
     * @return void
     */
    public function testActiveAccountPayoutWithHugeBalance()
    {
        $inflationEffect = factory(InflationEffect::class, 1)->create([
            'amount' => '10000'
        ]);

        $activeAccounts = factory(ActiveAccount::class, 3)->create([
            'balance' => '1000' * StellarAmount::STROOP_SCALE
        ]);

        $activeAccount = factory(ActiveAccount::class)->create([
            'balance' => '10000000' * StellarAmount::STROOP_SCALE
        ]);

        $this->payoutService->init();

        $this->payoutService->setInflationEffect($inflationEffect->first());

        $payoutAmount = $this->payoutService->calculateActiveAccountPayoutAmount($activeAccount);
          // problem with rounding up      
//        $this->assertEquals(49985004398.650406, $payoutAmount);
        $this->assertEquals(49985004398.65040487853643906828, $payoutAmount);
    }

}
