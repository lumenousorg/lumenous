<?php

namespace lumenous\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable implements ShouldQueue {

    use Queueable,
        SerializesModels;

    /**
     * Array holding form submitted fields
     * 
     * @var Object 
     */
    public $form;

    /**
     * Create a new message instance.
     * 
     * @param [] $form
     * @return void
     */
    public function __construct($form)
    {
        $this->form = (Object) $form;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contact Us Form Submission : ' . $this->form->subject)->view('emails.ContactUs');
    }

}
