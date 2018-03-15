<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use lumenous\Repositories\Interfaces\TestAccountsBatchesRepositoryInterface;

class TestAccountsCreator extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-accounts:create {accounts=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a number of test accounts, fund them with Friend Bot and set their inflation destination to APP.';

    /**
     * Stellar service.
     * 
     * @var StellarService 
     */
    protected $stellarService;

    /**
     *
     * @var TestAccountsBatchesRepositoryInterface 
     */
    protected $testAccountsBatchesRepository;

    /**
     * Create a new command instance.
     *
     * @param StellarService $stellarService
     * @param TestAccountsBatchesRepositoryInterface $testAccountsBatchesRepository
     * @return void
     */
    public function __construct(StellarService $stellarService, TestAccountsBatchesRepositoryInterface $testAccountsBatchesRepository)
    {
        parent::__construct();
        $this->stellarService = $stellarService;
        $this->testAccountsBatchesRepository = $testAccountsBatchesRepository;
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
            $this->stellarService->SetInflationDestination($account['publicKey'], $account['secretKey'], config('lumenous.stellar_public_key'));
        }

        $this->testAccountsBatchesRepository->create(['accounts' => $accounts]);
    }

}
