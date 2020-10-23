<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;


class TwilioPush extends Notification
{
    use Queueable;

    
    public $m;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $m )
    {
        $this->m = $m;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content($this->m);
                    
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
    /*
    public function routeNotificationForTwilio()
    {
        return '+'.$notifiable->phone_code.$notifiable->phone;
    }
    */
    protected function getTo($notifiable){
        return '+'.$notifiable->phone_code.$notifiable->phone;
    }
}
