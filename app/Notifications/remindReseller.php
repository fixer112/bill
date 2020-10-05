<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Traits\BillPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class remindReseller extends Notification implements ShouldQueue
{
    use Queueable, BillPayment;

    public $days;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $days)
    {
        $this->days = $days;
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
            ->subject('Awaiting Subscription')
            ->greeting("Hello {$notifiable->full_name}!")
            ->line("We noticed you registered as a reseller {$notifiable->created_at->diffForHumans()}")
            ->line("Please login to subscribe to one of our reseller's package or click on the link to downgrade to the free individual package.")
            ->line("Please note that you will be automatically downgraded to individual package after $this->days days")
            ->action('Downgrade to individual', url("/{$notifiable->id}/subscription/downgrade"))
            ->line('Thank you for choosing us!');
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
        $message = "Hello {$notifiable->first_name},We noticed you registered as a reseller {$notifiable->created_at->diffForHumans()}, Please subscribe to one of our reseller's package or downgrade to the free individual package.";
        //return $this->sms($message, $notifiable->nigeria_number);

    }
    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toApp($notifiable)
    {
        //$message = "We noticed you registered as a reseller {$notifiable->created_at->diffForHumans()}, Please subscribe to one of our reseller's package or downgrade to the free individual package.";

        //return $this->app($notifiable, $this->desc, 'Transaction Alert');
    }
}