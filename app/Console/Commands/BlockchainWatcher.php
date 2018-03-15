<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use lumenous\Repositories\Interfaces\UsersRepositoryInterface;
use lumenous\Repositories\Interfaces\ActiveAccountsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BlockchainWatcher extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:update {--fed : Whether the account balance should be acquired from the FED.NETWORK or the API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the active accounts table by associating the local accounts with the inflation pool entries';

    /**
     * Stellar service.
     * 
     * @var StellarService 
     */
    protected $stellarService;

    /**
     * Users Repository.
     * 
     * @var UsersRepositoryInterface 
     */
    protected $usersRepository;

    /**
     * ActiveAccounts Repository.
     * 
     * @var ActiveAccountsRepositoryInterface 
     */
    protected $activeAccountsRepository;

    /**
     * BlockchainWatcher constructor.
     *
     * @param StellarService $stellarService
     * @param UsersRepositoryInterface $usersRepository
     * @param ActiveAccountsRepositoryInterface $activeAccountsRepository
     */
    public function __construct(StellarService $stellarService, UsersRepositoryInterface $usersRepository, ActiveAccountsRepositoryInterface $activeAccountsRepository)
    {
        parent::__construct();

        $this->stellarService = $stellarService;
        $this->usersRepository = $usersRepository;
        $this->activeAccountsRepository = $activeAccountsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $publicKey = config('lumenous.stellar_public_key');

        $entries = $this->stellarService->getInflationPoolEntries($publicKey);

        // Fed Network Accounts with Inflation Pool Dest set to APP
        $entries = collect($entries)->mapWithKeys(function ($entry) {
            return [$entry['account'] => $entry['balance']];
        });

        // Local Users
        $users = $this->usersRepository->all()->mapWithKeys(function ($user) {
            return [$user['stellar_public_key'] => $user['id']];
        });

        $fedNetwork = $this->option('fed') == NULL ? FALSE : TRUE;

        $this->info('Using Fed Network for account balances : ' . ($fedNetwork ? 'Yes' : 'No'));


        DB::transaction(function () use ($entries, $users, $fedNetwork) {

            $newUsersCreated = 0;

            $this->activeAccountsRepository->truncate();

            foreach ($entries as $key => $balance) {

                $user = null;

                // If account is in pool and doesn't exist locally, add it with empty placehlders untill user registers
                if (empty($users[$key])) {
                    $user = $this->usersRepository->create([
                        'stellar_public_key' => $key
                    ]);
                    $newUsersCreated++;
                }

                $this->activeAccountsRepository->create([
                    'balance' => $fedNetwork ? $balance : $this->stellarService->getNativeBalance($key, true),
                    'user_id' => $user == NULL ? $users[$key] : $user->id
                ]);
            }

            $this->info('Newly created users : ' . $newUsersCreated);
        });
    }

}
