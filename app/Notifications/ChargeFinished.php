<?php

namespace App\Notifications;

use App\Charge;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Msg91\Message\Msg91Message;

class ChargeFinished extends Notification implements ShouldQueue
{
    use Queueable;

    private $charge;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Charge $charge)
    {
        $this->charge = $charge;
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
        if (Setting::get('notify_user_session_email')) {
            $channels[] = 'mail';
        }
        if (Setting::get('notify_user_session_phone')) {
            $channels[] = 'msg91';
        }
        return $channels;
    }

    public function toMsg91($notifiable)
    {
        return (new Msg91Message)
            ->message("Charge Session #".$this->charge->id ." Finished!\n"
                ."Power Consumed: ".$this->charge->power
                ." kWh.\n"
                ."Charged amount: ".$this->charge->amount.' INR')
            ->transactional(); // or promotional() [Optional] - Will pick default route from MSG91_DEFAULT_ROUTE or config
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
                    ->greeting('Charge Session Finished!')
                    ->line('The session #'.$this->charge->id.' is finished.')
                    ->line('Power Consumed: '.$this->charge->power.'kWh.')
                    ->line('Charged amount: '.$this->charge->amount.' INR.');
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
