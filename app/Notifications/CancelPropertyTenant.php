<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class CancelPropertyTenant extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($firstname, $lastname, $created_at, $id, $property)
    {
        //
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->created_at = Carbon::parse($created_at)->format('d-m-Y');
        $this->id = $id;
        $this->property = $property;
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
        $url = env('APP_URL');
        return (new MailMessage)
            ->subject(sprintf("Postulación Rechazada."))
            ->greeting(sprintf("Lo lamentamos " . $this->firstname . " " . $this->lastname ."!"))
            ->line("Tu postulación realizada el " . $this->created_at . " en la propiedad ID " . $this->id . " - " . $this->property . ", ha sido RECHAZADA por el arrendador.")
            ->line("Te invitamos a que sigas buscando tu propiedad ideal en UHomie, que tengas un feliz día.")
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
