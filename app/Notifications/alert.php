<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Traits\BillPayment;
use App\Traits\Notify;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class alert extends Notification implements ShouldQueue
{
    use Queueable, BillPayment, Notify;

    public $desc;
    public $tran;
    //public $is_error;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $desc, Transaction $tran = null)
    {
        $this->desc = $desc;
        $this->tran = $tran;
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
        $mail = (new MailMessage)
            ->greeting("Hello {$notifiable->full_name}!")
            ->line('You have a transaction notification with description:')
            ->line($this->desc);

        if ($this->tran) {
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

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toSMS($notifiable)
    {
        $desc = str_replace('₦', 'NGN', $this->desc);
        $message = "Hello {$notifiable->first_name},
You have a transaction notification with description: {$desc}";
        if ($notifiable->sms_notify && $this->tran) {
            $this->chargeSms($this->tran, ($message));

            return $sms;
        }

    }
}