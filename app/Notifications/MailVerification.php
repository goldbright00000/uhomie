<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailVerification extends Notification implements ShouldQueue
{
    use Queueable;

    public $activation_token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($activation_token)
    {
        $this->activation_token = $activation_token;
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
        $url = url('/users/mail-verification/'.$this->activation_token);
        return (new MailMessage)
                    ->subject('Verificación de Mail - uHomie')
                    ->greeting('¡Hola!')
                    ->line('Bienvenido a uhomie. Haz click en el botón para continuar el proceso de registro')
                    ->action('Confirmar Email', $url)
                    ->line('Gracias por preferirnos!');
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
