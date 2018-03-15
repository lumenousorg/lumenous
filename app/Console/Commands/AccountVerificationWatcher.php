<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use lumenous\Repositories\Interfaces\UsersRepositoryInterface;

class AccountVerificationWatcher extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:verification-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if any unverified accounts have the uniquely generated key set.';

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
     * Create a new command instance.
     * 
     * @param StellarService $stellarService
     * @param UsersRepositoryInterface $usersRepository
     * @return void
     */
    public function __construct(StellarService $stellarService, UsersRepositoryInterface $usersRepository)
    {
        parent::__construct();
        $this->stellarService = $stellarService;
        $this->usersRepository = $usersRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get unverified accounts
        $unverifiedUsers = $this->usersRepository->findAllBy(false, 'lmnry_verified');

        foreach ($unverifiedUsers as $user) {
            $manageDataOperations = $this->stellarService->getAccountOperations($user->stellar_public_key, StellarService::OPERATION_MANAGE_DATA);

            // If account `manage_data` operations are empty, continue
            if (empty($manageDataOperations)) {
                continue;
            }

            $lmnryVerificationKey = null;
            foreach ($manageDataOperations as $operation) {
                if ($operation['name'] == 'lmnry_verify_key') {
                    $lmnryVerificationKey = base64_decode($operation['value']);
                    break;
                }
            }

            // If account `lmnry_verify_key` data name not found, continue
            if (empty($lmnryVerificationKey)) {
                continue;
            }

            // If local key and stellar account key do not match, continue
            if ($user->lmnry_verify_key != $lmnryVerificationKey) {
                continue;
            }

            $this->usersRepository->update($user->id, [
                'lmnry_verify_key' => null,
                'lmnry_verified' => true
            ]);
        }
    }

}
