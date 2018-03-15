<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\LedgersRepositoryInterface;
use lumenous\Models\Ledger;

class EloquentLedgersRepository extends EloquentAbstractRepository implements LedgersRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\Ledger';
    }

    /**
     * Return the record for the last created inflation effect.
     * 
     * @return InflationEffect
     */
    public function getLatestRecord()
    {
        return Ledger::latest()->first();
    }

}
