<?php

namespace lumenous;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'stellar_public_key', 'password', 'is_verified', 'donation_percentage', 'lmnry_verify_key', 'lmnry_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the email verification code for the user.
     */
    public function verification_code()
    {
        return $this->hasOne('lumenous\Models\EmailVerificationCode');
    }

    /**
     * Get the active account of the user.
     * 
     * @return HasOne
     */
    public function account()
    {
        return $this->hasOne('lumenous\Models\ActiveAccount');
    }

}
