<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use lumenous\Repositories\Interfaces\InflationEffectsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use lumenous\Jobs\ExecutePayoutsJob;

class InflationEffectsWatcher extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inflation:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if application\'s stellar account received any lumens from last inflation operation';

    /**
     * Stellar service.
     * 
     * @var StellarService 
     */
    protected $stellarService;

    /**
     * InflationEffects Repository.
     * 
     * @var InflationEffectsRepositoryInterface 
     */
    protected $inflationEffectsRepository;

    /**
     * InflationEffectsWatcher constructor.
     *
     * @param StellarService $stellarService
     * @param InflationEffectsRepositoryInterface $inflationEffectsRepository
     */
    public function __construct(StellarService $stellarService, InflationEffectsRepositoryInterface $inflationEffectsRepository)
    {
        parent::__construct();
        $this->stellarService = $stellarService;
        $this->inflationEffectsRepository = $inflationEffectsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $publicKey = config('lumenous.stellar_public_key');
        $effects = $this->stellarService->getAccountEffects($publicKey, $this->stellarService::OPERATION_PATH_PAYMENT, 'now', null, 'asc', true,
                                                            function($effect) {
            $effect = (object) $effect;
            if ($effect->type == 'account_credited') {
                $effect = $this->inflationEffectsRepository->create([
                    'effect_id' => $effect->id,
                    'amount' => $effect->amount,
                    'data' => $effect
                ]);
                
                Log::debug('New Inflation Effect Detected. Dispatching Payouts Job.', ['effect' => $effect]);
                ExecutePayoutsJob::dispatch($effect);
            }
        });
    }

}
