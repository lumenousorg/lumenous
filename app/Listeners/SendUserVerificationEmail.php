<?php

namespace lumenous\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use lumenous\Mail\VerifyUser;
use Illuminate\Support\Facades\Mail;

class SendUserVerificationEmail implements ShouldQueue {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        Mail::to($user)->send(new VerifyUser($user));
    }

}
