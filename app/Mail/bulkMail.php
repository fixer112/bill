<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class bulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $html;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $html)
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
        return $this->view('emails.bulkmail');
    }
}