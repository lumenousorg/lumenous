<?php

namespace lumenous\Repositories\Interfaces;

interface UsersRepositoryInterface {

    /**
     * Check with the Stellar Public Key If a user exists, update the record else create it.
     * 
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateOrCreateByStellarPublicKey($key, $data = []);
}
