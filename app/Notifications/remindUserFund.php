<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Traits\BillPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class remindUserFund extends Notification implements ShouldQueue
{
    use Queueable, BillPayment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            ->subject('We Miss You!')
            ->greeting("Hello {$notifiable->full_name}!")
            ->line("We noticed its been a while you funded your wallet")
            ->line("Please fund your wallet by transfering to {$notifiable->account_number}({$notifiable->bank_name}), and your wallet will be funded instantly.")
            ->line("Or you can login and click on fund wallet and make online payment by entering the amount you wish to fund and proceed with online payment.")
            ->action('Fund your Wallet', url("/user/wallet/{$notifiable->id}/fund"))
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
        $message = "Hello {$notifiable->first_name}, We noticed its been a while you funded your wallet.
    Please fund your wallet by transfering to {$notifiable->account_number}({$notifiable->bank_name})";
        //return $this->sms($message, $notifiable->nigeria_number);

    }
}