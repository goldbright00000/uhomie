<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\{Property, User};
use Illuminate\Support\Carbon;

class ScheduleState extends Notification
{
    use Queueable;

    private $model;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($visitor, $owner, $property, $date, $range, $reciever)
    {
        $this->property = Property::find($property);
        $this->owner = User::find($owner);
        $this->visitor = User::find($visitor);
        $this->date = Carbon::parse($date)->format('d-m-Y');
        $this->range = $range;
        $this->reciever = $reciever;
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
        $url = url('/explorar');
        $link_property = url('/explorar/'.$this->property->id.'/'.$this->property->getSlugAttribute());
        switch ($this->range) {
            case '9-12':
                $rangename = "mañana";
                break;
            case '12-15':
                $rangename = "mediodia";
                break;
            case '15-19':
                $rangename = "tarde";
                break;
            
            default:
                # code...
                break;
        }

        if ($this->reciever == 1) {
            return (new MailMessage)
                ->subject("Tu visita ha sido aceptada")
                ->greeting(sprintf("Felicitaciones %s !", $this->visitor->firstname . ' ' . $this->visitor->lastname))
                ->line(sprintf("Tu visita a la propiedad %s fue aprobada el dia %s.", $this->property->name, $this->date))
                ->line(sprintf("Para mas información comunicate con %s a tráves del numero +%s-%s",$this->owner->firstname . ' ' . $this->owner->lastname, $this->owner->phone_code, $this->owner->phone))
                ->action('VER PROPIEDAD', $link_property);
        }
        else{
            return (new MailMessage)
                ->subject("Tu visita ha sido rechazada")
                ->greeting("Lo lamentamos el propietario ha rechazado tu visita")
                ->line("Te invitamos a que sigas buscando tu propiedad ideal")
                ->action('EXPLORAR', $url);
        }
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
