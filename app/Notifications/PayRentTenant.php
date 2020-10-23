<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class PayRentTenant extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $created_at, $id, $property, $payment)
    {
        //
        $this->payment = $payment;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
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
            ->subject(sprintf("Pago realizado con exito"))
            ->greeting(sprintf("Felicitaciones " . $this->firstname . " " . $this->lastname ."!"))
            ->line("Hemo recibido tus pagos correspondientes al monto de garantia y al monto de adelanto, con fecha: " . $this->created_at . ", en la propiedad registrada como: " . $this->property->name . ".")
            ->line("Ahora el siguiente paso es validar tu identidad en tiempo real accediendo al siguiente enlace, recuerda que tanto el Arrendador como tu Aval (si existe) deben validar su identidad en tiempo real en nuestra plataforma. Una vez validados todos, se les enviará un e-mail para iniciar el proceso de firma digital del contrato de arriendo.")
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
