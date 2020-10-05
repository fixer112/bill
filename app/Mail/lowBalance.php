<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class lowBalance extends Mailable
{
    use Queueable, SerializesModels;

    public $balance;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(float $balance)
    {
        $this->balance = $balance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.balance.low');
    }
}