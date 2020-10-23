<?php

use Illuminate\Database\Seeder;

use App\Message;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::create([
        	'from_id' => 1,
            'to_id' => 2,
            'content' => 'Hola, cómo estás? Me gusta la propiedad publicada.' 
        ]);
        Message::create([
        	'from_id' => 2,
            'to_id' => 1,
            'content' => 'Bien, gracias. Y tu? Ok, hablemos de ello.' 
        ]);

        Message::create([
            'from_id' => 1,
            'to_id' => 3,
            'content' => 'Saludos, feliz sábado. Pudiste subir las nuevas fotos que te pedí?' 
        ]);
        Message::create([
            'from_id' => 3,
            'to_id' => 1,
            'content' => 'igual para tí. Que tal? Si, revisa la publicación, ya actualicé las fotos con la vista pedida, para que puedas verlas.' 
        ]);
    }
}
