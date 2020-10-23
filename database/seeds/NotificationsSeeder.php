<?php

use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            /* tenant */

            'Notificaciones de Nuevas Propiedades',
            'Notificaciones de Servicios que podrían interesarte',  // *2
            'Notificaciones de Proyectos',
            'Noticias de anuncios de uHomie',                       // *4
            'Un Arrendador aceptó tu postulación',                  //  5
            'Uhomie te pide tramitar la firma del contrato digital',// *6
            'Uhomie te pide renovar tu membresia',                  // *7
            'Notificación de vencimiento de membresía.',            // *8
            'Compartir solo tus datos asociados a tu ficha de postulación al arrendador',

            /* Owner */
            'Nuevos Clientes que han dado click a tu propiedad',    // *1
            'Un arrendatario aceptó tu contrato de arriendo',       // *3
            'Compartir solo tus datos asociados a tu ficha de propiedad con arrendatarios', // *9
        ];

        foreach ($data as $item) {
            $notif = new \App\Notification();
            $notif->name = $item;
            $notif->save();
        }
    }
}
