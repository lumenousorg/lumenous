<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\TestAccountsBatchesRepositoryInterface;
use lumenous\Models\TestAccountsBatch;

class EloquentTestAccountsBatchesRepository extends EloquentAbstractRepository implements TestAccountsBatchesRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\Models\TestAccountsBatch';
    }

}
