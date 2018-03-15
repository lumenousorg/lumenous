<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ledgers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sequence', 'total_coins', 'data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

}
