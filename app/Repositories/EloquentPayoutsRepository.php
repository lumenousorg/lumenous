<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\PayoutsRepositoryInterface;
use lumenous\Models\Payout;
use lumenous\User;

class EloquentPayoutsRepository extends EloquentAbstractRepository implements PayoutsRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\Payout';
    }

    /**
     * Retrieve all unsigned payouts for the given user.
     *
     * @return mixed
     */
    public function getUnsigned(User $user)
    {
        return Payout
                        ::join()
                        ::where('signed', '=', 0)
                        ->orderBy('created_at', 'ASC')
                        ->get();
    }

    /**
     * Retrieve all payouts for the given user.
     *
     * @return mixed
     */
    public function getByUser(User $user)
    {
        return Payout::where('user_id', $user->id)
                        ->orderBy('created_at', 'DESC')
                        ->get();
    }

    /**
     * Retrieve count of all payouts for the given user.
     *
     * @return mixed
     */
    public function getCountByUser(User $user)
    {
        return Payout::where('user_id', $user->id)->count();
    }

    /**
     * Retrieve total amount donated to charity by a user.
     *
     * @return mixed
     */
    public function getCharityTotalByUser(User $user)
    {
        return Payout::where('user_id', $user->id)->sum('charity_payout_amount');
    }

    /**
     * Retrieve total personal amount received by a user.
     *
     * @return mixed
     */
    public function getAccountTotalByUser(User $user)
    {
        return Payout::where('user_id', $user->id)->sum('account_payout_amount');
    }

}
