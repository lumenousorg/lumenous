<?php

namespace lumenous\Http\Controllers\API;

use Illuminate\Http\Request;
use lumenous\Http\Controllers\Controller;
use lumenous\Repositories\Interfaces\ActiveAccountsRepositoryInterface;
use lumenous\Repositories\Interfaces\LedgersRepositoryInterface;
use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\User;
use lumenous\Http\Requests\Dashboard\GetStatsRequest;

class DashboardController extends Controller {

    /**
     * ActiveAccounts Repository.
     * 
     * @var ActiveAccountsRepositoryInterface 
     */
    protected $activeAccountsRepository;

    /**
     * Ledgers Repository.
     * 
     * @var LedgersRepositoryInterface 
     */
    protected $ledgersRepository;

    /**
     * PayoutsRepositoryInterface Repository.
     * 
     * @var PayoutsRepositoryInterface 
     */
    protected $payoutsRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LedgersRepositoryInterface $ledgersRepository, ActiveAccountsRepositoryInterface $activeAccountsRepository, PayoutsRepositoryInterface $payoutsRepository)
    {
        parent::__construct();
        $this->ledgersRepository = $ledgersRepository;
        $this->activeAccountsRepository = $activeAccountsRepository;
        $this->payoutsRepository = $payoutsRepository;
    }

    /**
     * Get the vote percentage for the inflation pool.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPoolVotePercentage()
    {
        $totalAppBalance = $this->activeAccountsRepository->getTotalBalance();
        $latestLumensBalance = $this->ledgersRepository->getLatestRecord()->total_coins * 10000000;

        if (empty($latestLumensBalance)) {
            return response()->json(['message' => 'total lumens cannot be 0'], 500);
        }

        $votePercentage = ($totalAppBalance / $latestLumensBalance) * 100;

        return response()->json(['vote' => $votePercentage]);
    }

    /**
     * Get statistics specific to a user.
     * 
     * @param GetStatsRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getStats(GetStatsRequest $request, User $user)
    {
        $numberOfPayouts = $this->payoutsRepository->getCountByUser($user);
        $totalAccountPayouts = $this->payoutsRepository->getAccountTotalByUser($user);
        $totalCharityPayouts = $this->payoutsRepository->getCharityTotalByUser($user);

        return response()->json(compact('numberOfPayouts', 'totalAccountPayouts', 'totalCharityPayouts'));
    }

}
