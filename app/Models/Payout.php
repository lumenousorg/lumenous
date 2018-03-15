<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payouts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_payout_amount', 'transaction_fee', 'account_payout_amount', 'charity_payout_amount',
        'transaction_hash', 'submitted', 'signed', 'donation_percentage', 'user_id', 'inflation_effect_id'
    ];

}
