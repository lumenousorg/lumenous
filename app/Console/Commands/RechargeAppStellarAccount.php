<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use ZuluCrypto\StellarSdk\Model\StellarAmount;

class RechargeAppStellarAccount extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-account:recharge {accounts=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a number of test accounts, fund them with Friend Bot and Send all their native balance to app account.';

    /**
     * Stellar service.
     * 
     * @var StellarService 
     */
    protected $stellarService;

    /**
     * Create a new command instance.
     *
     * @param StellarService $stellarService
     * @return void
     */
    public function __construct(StellarService $stellarService)
    {
        parent::__construct();
        $this->stellarService = $stellarService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $numberOfAccounts = $this->argument('accounts');

        $accounts = $this->stellarService->createAccounts($numberOfAccounts);


        foreach ($accounts as $account) {
            // send all balance but leave 1 lumen behind
            // ((10.000 lumens - 1 lumen) * STROOP_SCALE ) - 100 Stroops TRANSACTION_FEES
            $this->stellarService->sendNativePaymentFrom(config('lumenous.stellar_public_key'), 99989999900, $account['publicKey'], $account['secretKey']);
        }

        $this->info('Funded : ' . ((99989999900 * $numberOfAccounts) / StellarAmount::STROOP_SCALE) . ' lumens');
    }

}
