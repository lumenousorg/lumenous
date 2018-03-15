<?php

namespace lumenous\Repositories\Interfaces;

interface InflationEffectsRepositoryInterface {

    /**
     * Return the record for the last created inflation effect.
     * 
     * @return InflationEffect
     */
    public function getLatestRecord();
}
