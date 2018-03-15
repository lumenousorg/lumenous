<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'initiator', 'tx_xdr', 'tx_tag', 'signed', 'submitted'];

    /**
     * @return mixed
     */
    public function signers()
    {
        return $this->hasMany('lumenous\Models\Signer', 'transaction_id');
    }

}
