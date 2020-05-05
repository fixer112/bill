<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contact extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $html;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $html)
    {
        $this->subject = $subject;
        $this->html = $html;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.contact')->subject($this->subject);
    }
}