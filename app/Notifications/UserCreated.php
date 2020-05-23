<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Traits\BillPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreated extends Notification implements ShouldQueue
{

    use Queueable, BillPayment;

    //public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to ' . env("APP_NAME"))
            ->greeting("Hello {$notifiable->full_name}!")
            ->line('You have successfully registered on ' . url('/') . '.')
            ->line('Thank you for choosing ' . env("APP_NAME") . '!')
            ->line("Go to your profile, and fund your wallet to start enjoying our amazing cheap services.")
            ->action('Go to Profile', url($notifiable->routePath()));
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

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toSMS($notifiable)
    {
        $message = "Welcome to " . env("APP_NAME") . " {$notifiable->first_name}, Please fund your wallet to start enjoying our amazing cheap services.";
        //return $this->sms($message, $notifiable->nigeria_number);

    }
}