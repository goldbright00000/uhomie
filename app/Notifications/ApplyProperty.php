<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
//use App\ApplyProperty as ApplyPropertyModel;

class ApplyProperty extends Notification {
    use Queueable;

    private $model;

    //public function __construct(ApplyPropertyModel $model ) {
    public function __construct($id,$property,$firstname,$lastname) {
        $this->id = $id;
        $this->property = $property;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        //$this->model = $model;   
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        //$postulant  = $this->model->postulant()->first();
        //$property   = $this->model->property()->first(); 

        //Quintin que url podemos darle
        $url = env('APP_URL');

        return (new MailMessage)
            ->subject(sprintf("Has postulado a una propiedad en Uhomie"))
            ->greeting(sprintf("Felicitaciones " . $this->firstname . " " . $this->lastname ."!"))
            ->line("Has postulado a la propiedad ID" . $this->id . " - " . $this->property .", puedes ver el estatus de tu postulación directamente accediendo a tu perfil.")
            ->action('Ver detalles', $url);
    }
}