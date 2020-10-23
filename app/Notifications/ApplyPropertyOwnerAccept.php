<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\ApplyProperty as ApplyPropertyModel;

class ApplyPropertyOwnerAccept extends Notification
{
    use Queueable;
    
    public $id;
    public $property;
    public $firstname;
    public $lastname;
    public $tenant;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    //public function __construct(ApplyPropertyModel $model)
    public function __construct($id,$property,$firstname,$lastname, $tenant)
    {
        $this->id = $id;
        $this->property = $property;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->tenant = $tenant;
        //$this->model = $model;
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
        //$owner = $this->model->postulant()->first();
        //$property   = $this->model->property()->first(); 
        return (new MailMessage)
            ->subject(sprintf("Has aceptado a {$this->tenant->firstname} {$this->tenant->lastname} para tu propiedad."))
            ->greeting(sprintf("Felicitaciones " . $this->firstname . " " . $this->lastname ."!"))
            ->line("Has aceptado la postulación a tu propiedad ID:  " . $this->id . ", Nombre: " . $this->property->name . ", para ver el detalle de esta nueva postulación debes acceder directamente a tu perfil.")
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
