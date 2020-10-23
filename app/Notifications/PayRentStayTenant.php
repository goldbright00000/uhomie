<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class PayRentStayTenant extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tenant, $owner, $created_at, $id, $property, $payment, $fechaEmision, $fechaExpiracion)
    {
        //
        $this->payment = $payment;
        $this->firstname = $tenant->firstname;
        $this->lastname = $tenant->lastname;
        $this->owner = $owner;
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
            ->subject(sprintf("Pago realizado con exito"))
            ->greeting(sprintf("Felicitaciones " . $this->firstname . " " . $this->lastname ."!"))
            ->line("Hemos recibido tu pago correspondiente, con fecha: " . $this->created_at . ", en la propiedad registrada como: " . $this->property->name . " para estar hospedado entre las fechas.")
            ->line("ENTRADA: ".$this->fechaEmision)
            ->line("SALIDA: ".$this->fechaExpiracion)
            ->line("Ahora el siguiente paso es contactarte con el DUEÑO para ocupar la propiedad para ello te suministraremos información adicional para q puedas contactarlo.")
            ->line("NOMBRE: ".$this->owner->firstname ." ". $this->owner->lastname)
            ->line("TELEFONO: +". $this->owner->phone_code."-".$this->owner->phone)
            ->line("\n EMAIL: ".$this->owner->email)
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
