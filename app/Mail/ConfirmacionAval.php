<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionAval extends Mailable
{
    use Queueable, SerializesModels;
    
    public $mensaje;
    
    public function __construct($m)
    {
        $this->mensaje = $m;
    }
    
    public function build()
    {
        return $this->view('emails.confirmacion_aval')->from('info@uhomie.cl');
    }
}