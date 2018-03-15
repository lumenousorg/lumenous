<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class TestAccountsBatch extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_accounts_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['accounts'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'accounts' => 'json'
    ];

}
