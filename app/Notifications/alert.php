<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class alert extends Notification
{
    use Queueable;

    public $desc;
    public $is_tran;
    //public $is_error;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $desc, bool $tran = true)
    {
        $this->desc = $desc;
        $this->is_tran = $tran;
        //$this->is_error = $error;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->greeting("Hello {$notifiable->full_name}!")
            ->line($this->desc);

        if ($this->is_tran) {
            $mail = $mail->subject('Transaction Alert')->action('View History', url("user/wallet/{$notifiable->id}/history"));
        }

        /* if ($this->is_error) {
        $mail->error();
        } */
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}