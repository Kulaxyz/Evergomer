<?php

namespace App\Notifications;

use App\Invoice;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Msg91\Message\Msg91Message;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;
    private $invoice;

    /**
     * Create a new notification instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];
        if (Setting::get('notify_owner_email')) {
            $channels[] = 'mail';
        }
        if (Setting::get('notify_owner_phone')) {
            $channels[] = 'msg91';
        }
        return $channels;
    }

    public function toMsg91($notifiable)
    {
        return (new Msg91Message)
            ->message("Invoice #".$this->invoice->id." was paid!\n"
                ."Device: ".$this->invoice->device->name .".\n"
                ."Amount: ".$this->invoice->amount." INR\n"
                ."Your balance is: ".$notifiable->amount.' INR')
            ->transactional();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Invoice #'.$this->invoice->id.' was paid!')
                    ->line('Device: '.$this->invoice->device->name)
                    ->line('Amount: '.$this->invoice->amount.' INR')
                    ->line('Your balance: ' .$notifiable->balance.' INR');
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
