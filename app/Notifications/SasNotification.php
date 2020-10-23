<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SasNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $razon;
    protected $success;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($razon, $success)
    {
        $this->razon = $razon;
        $this->success = $success;
    }

    /**
     * Get the notification"s delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("");
        //dd($notifiable);
        if( $this->success ){
            return (new MailMessage)
            ->subject(sprintf("Resultado verificación de identidad"))
            ->greeting("Buenas {$notifiable->full_name}!")
            ->line("Te informamos que la verificación de identidad ha resultado exitosa :). Te invitamos a seguir navegando en uHomie.cl ")
            ->action("Acceder", $url);
        } else{
            if( $this->razon ){
                return (new MailMessage)
                    ->subject(sprintf("Resultado verificación de identidad"))
                    ->greeting("Buenas {$notifiable->full_name}! ")
                    ->line("Te informamos que la verificación de identidad en uHomie lamentablemente ha fracasado. Razón: ".$this->razon.'. De todas formas te invitamos a seguir intentándolo en uHomie.cl. :)')
                    ->action("Acceder", $url);
            }else{
                return (new MailMessage)
                    ->subject(sprintf("Resultado verificación de identidad"))
                    ->greeting("Buenas {$notifiable->full_name}!")
                    ->line("Te informamos que la verificación de identidad en uHomie lamentablemente ha fracasado. Razón: la imagen no es claramente analizable o no coincide con tu documento de identidad. De todas formas te invitamos a seguir intentándolo en uHomie.cl. :)")
                    ->action("Acceder", $url);
            }
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
