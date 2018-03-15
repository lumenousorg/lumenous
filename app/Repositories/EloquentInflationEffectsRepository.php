<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\InflationEffectsRepositoryInterface;
use lumenous\Models\InflationEffect;

class EloquentInflationEffectsRepository extends EloquentAbstractRepository implements InflationEffectsRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\InflationEffect';
    }

    /**
     * Return the record for the last created inflation effect.
     * 
     * @return InflationEffect
     */
    public function getLatestRecord()
    {
        return InflationEffect::latest()->first();
    }

}
