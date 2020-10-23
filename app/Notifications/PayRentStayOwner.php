<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class PayRentStayOwner extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($owner, $tenant, $created_at, $id, $property, $payment, $fechaEmision, $fechaExpiracion)
    {
        $this->payment = $payment;
        $this->firstname = $owner->firstname;
        $this->lastname = $owner->lastname;
        $this->tenant = $tenant;
        $this->created_at = Carbon::parse($created_at)->format('d-m-Y');
        $this->id = $id;
        $this->property = $property;
        $this->fechaEmision = Carbon::parse($fechaEmision)->format('d-m-Y');
        $this->fechaExpiracion = Carbon::parse($fechaExpiracion)->format('d-m-Y');
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
            ->subject(sprintf("El postulante a tu propiedad ya realizó el pago"))
            ->greeting(sprintf("Felicitaciones " . $this->firstname . " " . $this->lastname ."!"))
            ->line("El postulante realizó los pagos correspondientes al monto de la estadia de temporada corta, con fecha de inicio el: " . $this->fechaEmision . " hasta el: ". $this->fechaExpiracion .", en la propiedad registrada como: " . $this->property->name . ".")
            ->line("Ahora el siguiente paso es contactarte con el arrendatario para ocupar la propiedad para ello te suministraremos información del arrendatario.")
            ->line("NOMBRE: ".$this->tenant->firstname ." ". $this->tenant->lastname)
            ->line("TELEFONO: +". $this->tenant->phone_code."-".$this->tenant->phone)
            ->line("EMAIL: ".$this->tenant->email)
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
