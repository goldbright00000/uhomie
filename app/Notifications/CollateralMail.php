<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CollateralMail extends Notification implements ShouldQueue
{
    use Queueable;

    public $activation_token;
    public $tenant_name;
    public $tenant_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($activation_token,$tenant_name,$tenant_id)
    {
        $this->activation_token = $activation_token;
        $this->tenant_name = $tenant_name;
        $this->tenant_id = $tenant_id;
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

        $url = url("users/collateral/activate/$this->activation_token/$this->tenant_id");
        return (new MailMessage)
            ->subject('Confirmación Aval en uHomie :)')
            ->greeting('¡Saludos!')
            ->line("$this->tenant_name Te ha invitado a ser su Aval de arriendo para UHomie, haz click en el boton para Aceptar o Negar esta invitación")
            ->action('¡Entendido!', $url)
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
