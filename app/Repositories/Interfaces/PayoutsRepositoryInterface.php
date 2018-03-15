<?php

namespace lumenous\Repositories\Interfaces;

use lumenous\User;

interface PayoutsRepositoryInterface {

    /**
     * Retrieve all payouts for the given user.
     *
     * @return mixed
     */
    public function getByUser(User $user);

    /**
     * Retrieve count of all payouts for the given user.
     *
     * @return mixed
     */
    public function getCountByUser(User $user);

    /**
     * Retrieve total amount donated to charity by a user.
     *
     * @return mixed
     */
    public function getCharityTotalByUser(User $user);

    /**
     * Retrieve total personal amount received by a user.
     *
     * @return mixed
     */
    public function getAccountTotalByUser(User $user);
}
