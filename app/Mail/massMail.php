<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class massMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $html;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $html)
    {
        $this->user = $user;
        $this->html = $html;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
    }
}