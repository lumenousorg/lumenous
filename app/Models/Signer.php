<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class Signer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'signers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'transaction_id', 'signed'];

    /**
     * @return mixed
     */
    public function transaction()
    {
        return $this->hasOne('lumenous\Models\Transaction', 'transaction_id');
    }

}
