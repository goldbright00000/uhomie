<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Schedule as ScheduleModel;
use Carbon\Carbon;

class Schedule  extends Notification {
    use Queueable;

    const TO_OWNER   = 0;
    const TO_VISITOR = 1;

    private $schedule;
    private $reciever;

    public function __construct($schedule, $reciever = null ) {
        $this->schedule = ScheduleModel::find($schedule);   

        switch($reciever) {
            case self::TO_VISITOR: {
                $this->reciever = self::TO_VISITOR;
                break;
            }
            case self::TO_OWNER:
            default: {
                $this->reciever = self::TO_OWNER;
                break;
            }
        }
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        switch($this->reciever) {
            case self::TO_VISITOR:  {
                return (new MailMessage)
                    ->subject('Hemos enviado tu solicitud de visita.')
                    ->greeting('Hemos enviado tu solicitud de visita.')
                    ->line('Te enviaremos un correo cuando el propietario confirme o cancele dicha solicitud.');
            }

            case self::TO_OWNER:
            default: {
                $visitor  = $this->schedule->visitor()->first();
                $property = $this->schedule->property()->first(); 

                switch($this->schedule->schedule_range){
                    case '9-12':
                        $schedule_range = '9 a 12';
                        break;

                    case '12-3':
                        $schedule_range = '12 a 15';
                        break;

                    case '3-7':
                        $schedule_range = '15 a 19';
                        break;
                    default:
                        $schedule_range = 'No escogido';
                        break;
                }
                
                return (new MailMessage)
                    ->subject(sprintf("Has recibido una solicitud de visita en una de tus propiedades."))
                    ->greeting(sprintf("%s quiere visitar tu propiedad %s.", $visitor->fullname, $property->name))
                    ->line(sprintf('La visita seria el %s de %s para mas información ingresa al sistema', Carbon::parse($this->schedule->schedule_date)->format('d/m/Y'), $schedule_range))
                    ->action('Confirma la visita', url('login'));
            }
        }
    }



}