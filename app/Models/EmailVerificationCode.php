<?php

namespace lumenous\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_verifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code'];

    /**
     * A verification code belongs to a user.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('lumenous\User');
    }

}
