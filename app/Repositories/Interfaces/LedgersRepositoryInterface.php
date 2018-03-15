<?php

namespace lumenous\Repositories\Interfaces;

interface LedgersRepositoryInterface {

    /**
     * Return the record for the last created ledger.
     * 
     * @return Ledger
     */
    public function getLatestRecord();
}
