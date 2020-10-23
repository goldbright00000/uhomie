<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TerceraClave extends Mailable
{
    use Queueable, SerializesModels;
    
    public $mensaje;
    
    public function __construct($m)
    {
        $this->mensaje = $m;
    }
    
    public function build()
    {
        return $this->view('emails.tercera_clave')->from('info@uhomie.cl');
    }
}