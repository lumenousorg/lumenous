<?php

namespace lumenous\Http\Controllers\API;

use Illuminate\Http\Request;
use lumenous\Http\Controllers\Controller;
use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\User;
use lumenous\Http\Requests\Payouts\GetUserPayoutsRequest;

class PayoutsController extends Controller {

    /**
     * Payouts Repository.
     * 
     * @var PayoutsRepositoryInterface 
     */
    protected $payoutsRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PayoutsRepositoryInterface $payoutsRepository)
    {
        parent::__construct();
        $this->payoutsRepository = $payoutsRepository;
    }

    /**
     * Get payouts of a specific user.
     * 
     * @param GetUserPayoutsRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPayouts(GetUserPayoutsRequest $request, User $user)
    {
        $payouts = $this->payoutsRepository->getByUser($user);
        return response()->json(compact('payouts'));
    }

}
