<?php

use Illuminate\Database\Seeder;

class PrivaciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // tenant
            'Uhomie solo podrá compartir tus datos asociados a tu  ficha de postulación a los dueños.',
            'Uhomie no podrá compartir tus datos asociados a tu  ficha de postulación a arrendadores que no hayas postulado.',
            'Uhomie mantendrá hasta por 12 meses tus datos de  postulación a las propiedades en las que fuiste aceptado y tramitaste tu contrato de forma exitosa.             Solo podrá ser consultado por tu arrendador vigente.',
            'Uhomie eliminará toda postulación a aquellas propiedades donde no fuiste aceptado, arrendadores no podrán ver tus datos.',

            // owner
            'Uhomie solo podrá compartir tus datos asociados a tu ficha de propiedad con arrendatarios que estén  interesados en tu propiedad.',
            'Uhomie no podrá compartir tus datos asociados a tu ficha de propiedad a arrendatarios que no hayan postulado.',
            'Sobre tu propiedad, una vez que hayas aceptado a otros arrendatarios, no podrás ver datos de los postulantes que hayan quedado fuera del proceso.1',
        ];

        foreach ($data as $item) {
            $priv = new \App\Privacy();
            $priv->name = $item;
            $priv->save();
        }
    }
}
