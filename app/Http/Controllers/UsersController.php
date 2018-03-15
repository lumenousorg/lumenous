<?php

namespace lumenous\Http\Controllers;

use Illuminate\Http\Request;
use lumenous\Models\EmailVerificationCode;
use lumenous\User;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller {

    /**
     * Verify a newly registered user by his verification code.
     * 
     * @param string $code
     * @return \Illuminate\Routing\Redirector
     */
    public function verify($code)
    {
        $code = EmailVerificationCode::where('code', $code)->first();

        if (!$code) {
            abort(404);
        }

        $user = $code->user;
        $user->is_verified = true;
        $user->save();

        $code->delete();

        Session::flash('success-message', 'You Successfully Verified your Email Address.');
        return redirect('/login');
    }

}
