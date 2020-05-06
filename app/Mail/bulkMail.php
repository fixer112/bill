<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class bulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $html;
    public $footer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $html)
    {
        $this->subject = $subject;
        $this->html = $html;
        $this->footer = "<p>Thank you for choosing MoniWallet</p>";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return;
        return $this->view('mail.bulk')->subject($this->subject);
    }
}