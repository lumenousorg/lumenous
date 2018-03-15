<?php

namespace lumenous\Console\Commands\Account;

use Illuminate\Console\Command;
use lumenous\Services\Stellar\Account as StellarAccountService;
use Exception;

class AddSigner extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:add-signer {signerPublicKey} {signerWeight=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add signer to app account using public key.';

    /**
     * @var Account 
     */
    protected $stellarAccountService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StellarAccountService $stellarAccountService)
    {
        parent::__construct();
        $this->stellarAccountService = $stellarAccountService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $signerPublicKey = $this->argument('signerPublicKey');

        if (empty($signerPublicKey)) {
            throw new Exception('signer public key cannot be empty');
        }

        $signerWeight = $this->argument('signerWeight');

        $appPublicKey = config('lumenous.stellar_public_key');
        $appSecretKey = config('lumenous.stellar_secret_key');

        $this->stellarAccountService->addSigner($appPublicKey, $appSecretKey, $signerPublicKey, $signerWeight);
    }

}
