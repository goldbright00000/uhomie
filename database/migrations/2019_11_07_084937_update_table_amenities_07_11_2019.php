<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAmenities07112019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $amenities = array(
            array(
                'name' => 'Llave con clave de seguridad',
                'image' => null,
                'type' => false
            ),
            array(
                'name' => 'Caja de seguridad',
                'image' => null,
                'type' => false
            ),
            //Servicios Basicos
            array(
                'name' => 'Cubiertos',
                'image' => null,
                'type' => 2,
            ),
            array(
                'name' => 'Frazadas y Cobijas',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Kit de limpieza',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Kit de limpieza personal',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Kit de primeros auxilios',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Platos',
                'image' => null,
                'type' => 2,
            ),
            array(
                'name' => 'Toallas',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Utencilios de cocina',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'WIFI',
                'image' => null,
                'type' => 2
            ),
            array(
                'name' => 'Sala de entretenimiento',
                'image' => null,
                'type' => 2
            ),
            //Reglas
            array(
                'name' => 'Está prohibido el ruido en exceso',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'Está prohibido hacer fiestas o eventos',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'No es apto para bebés (menores de 2 años)',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'Está prohibido cocinar',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'Está prohibido usar los muebles',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'Está prohibido usar los equipos electrónicos de la propiedad',
                'image' => null,
                'type' => 3
            ),
            array(
                'name' => 'Está prohibido cocinar',
                'image' => null,
                'type' => 3
            ),
            //Detalles
            array(
                'name' => 'Es necesario utilizar escaleras',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Puede haber ruido',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Hay zonas comunes que se comparten con otros huéspedes',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Limitaciones de servicios',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Dispositivos de vigilancia o de grabación en la vivienda',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Armas en la vivienda',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Hay mascotas en la propiedad',
                'image' => null,
                'type' => 4
            ),
            array(
                'name' => 'Animales peligrosos en la vivienda',
                'image' => null,
                'type' => 4
            ),
            //Posesiones de terreno
            array(
                'name' => 'Conexión a linea eléctrica',
                'image' => null,
                'type' => 5
            ),
            array(
                'name' => 'Conexión a aguas blancas',
                'image' => null,
                'type' => 5
            ),
            array(
                'name' => 'Conexión a aguas negras',
                'image' => null,
                'type' => 5
            ),
            array(
                'name' => 'Permiso de construcción',
                'image' => null,
                'type' => 5
            )

        );
        DB::table('amenities')->insert($amenities);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
