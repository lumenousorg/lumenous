<?php

namespace lumenous\Jobs;

use lumenous\Models\InflationEffect;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lumenous\Services\Stellar\Payout;
use lumenous\Repositories\Interfaces\ActiveAccountsRepositoryInterface;

class ExecutePayoutsJob implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * @var InflationEffect 
     */
    protected $inflationEffect;

    /**
     * @var ActiveAccountsRepositoryInterface 
     */
    protected $activeAccountsRepository;

    /**
     * Create a new job instance.
     *
     * @param InflationEffect $inflationEffect
     * @param ActiveAccountsRepositoryInterface $activeAccountsRepository
     * @return void
     */
    public function __construct(InflationEffect $inflationEffect, ActiveAccountsRepositoryInterface $activeAccountsRepository)
    {
        $this->inflationEffect = $inflationEffect;
        $this->activeAccountsRepository = $activeAccountsRepository;
    }

    /**
     * Execute the job.
     * 
     * @param Payout $payoutService  
     * @return void
     */
    public function handle(Payout $payoutService)
    {
        $activeAccounts = $this->getAllActiveAccounts();
        
        $payoutService->init();

        $payoutService->setInflationEffect($this->inflationEffect);

        $payoutService->executeActiveAccountsPayout($activeAccounts);

        $payoutService->executeCharityPayout($activeAccounts);
    }

    /**
     * Get all active account records in the pool.
     * 
     * @return \Illuminate\Support\Collection
     */
    protected function getAllActiveAccounts()
    {
        return $this->activeAccountsRepository->all();
    }

}
