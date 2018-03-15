<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\ActiveAccountsRepositoryInterface;
use lumenous\Models\ActiveAccount;

class EloquentActiveAccountsRepository extends EloquentAbstractRepository implements ActiveAccountsRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\ActiveAccount';
    }

    /**
     * Return the total balance. 
     * 
     * @return Integer
     */
    public function getTotalBalance()
    {
        return ActiveAccount::sum('balance');
    }

}
