<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class ExpiresMembership extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($membresia, $fecha_expiracion)
    {
        //
        $this->membresia = $membresia;
        $this->fecha_expiracion = $fecha_expiracion;
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
        $hoy = Carbon::now();
        $url = env('APP_URL');
        return (new MailMessage)
            ->subject(sprintf("Tu membresía en Uhomie esta por expirar"))
            ->greeting(sprintf("Buenas " . $notifiable->firstname . " " . $notifiable->lastname))
            ->line("Te informamos que tu membresía ".$this->membresia->name." expirará en menos de ".$hoy->diffInDays(new Carbon($this->fecha_expiracion))." dias.")
            ->line("Te invitamos a renovar tu membresía accediendo a uHomie.cl")
            ->action('ACCEDER', $url);
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
