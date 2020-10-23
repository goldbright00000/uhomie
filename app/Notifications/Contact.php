<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Contact extends Notification implements ShouldQueue
{
    use Queueable;
    
    const TO_ADMIN = 0;
    const TO_USER  = 1;

    private $to;
    private $contact_message;

    public function __construct(\App\ContactMessage $contact_message, $to = null) {
        $this->contact_message = $contact_message;
        
        $this->to = isset($to) ? $to : self::TO_ADMIN;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {

        switch($this->to) {
            case self::TO_USER: {
                return (new MailMessage)
                ->greeting(sprintf("Gracias por contactarnos.", $this->contact_message->name))
                ->line('Hemos recibido tu mensaje, para nosotros es muy 
                    importante resolver cualquier duda que tengas.')
                ->line('Te contactaremos en breve.');
            }
            default: {
                return (new MailMessage)
                    ->greeting(sprintf("%s quiere contactarte.", $this->contact_message->name))
                    ->line(sprintf('Telefono: %s', $this->contact_message->phone))
                    ->line(sprintf('Email: %s', $this->contact_message->email))
                    ->line(sprintf('Motivo: %s', (\App\ContactMessage::getReasonsLabels())[$this->contact_message->reason_contact]))
                    ->line(sprintf('Mensaje: %s', $this->contact_message->message));
            }
        }
        
    }
}
