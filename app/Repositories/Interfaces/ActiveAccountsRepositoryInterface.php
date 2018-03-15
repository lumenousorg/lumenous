<?php

namespace lumenous\Repositories\Interfaces;

interface ActiveAccountsRepositoryInterface {

    /**
     * Return the total balance. 
     * 
     * @return Integer
     */
    public function getTotalBalance();
}
