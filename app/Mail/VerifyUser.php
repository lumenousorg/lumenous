<?php

namespace lumenous\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use lumenous\User;

class VerifyUser extends Mailable implements ShouldQueue {

    use Queueable,
        SerializesModels;

    /**
     * User that just registered
     * 
     * @var User 
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirm registration')->markdown('emails.VerifyUser');
    }

}
