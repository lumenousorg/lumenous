<?php

namespace lumenous\Console\Commands;

use Illuminate\Console\Command;
use lumenous\Services\StellarService;
use lumenous\Repositories\Interfaces\LedgersRepositoryInterface;
use Illuminate\Support\Facades\Log;
use ZuluCrypto\StellarSdk\Model\Ledger;

class LedgersWatcher extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ledgers:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch newly created ledgers and add them to DB';

    /**
     * Stellar service.
     * 
     * @var StellarService 
     */
    protected $stellarService;

    /**
     * Ledgers Repository.
     * 
     * @var LedgersRepositoryInterface 
     */
    protected $ledgersRepository;

    /**
     * Create a new command instance.
     * 
     * @param StellarService $stellarService
     * @param LedgersRepositoryInterface $ledgersRepository
     */
    public function __construct(StellarService $stellarService, LedgersRepositoryInterface $ledgersRepository)
    {
        parent::__construct();
        $this->stellarService = $stellarService;
        $this->ledgersRepository = $ledgersRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->stellarService->streamLedgers('now',
                                             function(Ledger $ledger) {
            Log::info('New Ledger Detected. Adding to DB.', ['ledger' => $ledger]);
            $this->ledgersRepository->create([
                'sequence' => $ledger->getSequence(),
                'total_coins' => $ledger->getTotalCoins(),
                'data' => $ledger->getRawData()
            ]);
        });
    }

}
