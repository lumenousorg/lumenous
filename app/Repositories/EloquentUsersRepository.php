<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\UsersRepositoryInterface;
use lumenous\User;
use Webpatser\Uuid\Uuid;

class EloquentUsersRepository extends EloquentAbstractRepository implements UsersRepositoryInterface {

    public function __construct()
    {
        $this->modelClass = 'lumenous\User';
    }

    /**
     * Create a new user.
     *
     * @param array $fields
     * @return mixed
     */
    public function create(array $fields = null)
    {
        if (!empty($fields['password'])) {
            $fields['password'] = bcrypt($fields['password']);
        }

        $fields['lmnry_verify_key'] = Uuid::generate(4);

        return parent::create($fields);
    }

    /**
     * Check with the Stellar Public Key If a user exists, update the record else create it.
     * 
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateOrCreateByStellarPublicKey($key, $data = [])
    {
        $user = $this->findBy($key, 'stellar_public_key');

        if (!$user) {
            return $this->create($data);
        }
        
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return $this->update($user->id, $data);
    }

}
