<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveAccount extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'active_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['balance', 'user_id'];

    /**
     * An active account belongs to a user.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('lumenous\User');
    }

}
