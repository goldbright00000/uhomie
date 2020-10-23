<?php

use Illuminate\Database\Seeder;

class ServicesListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services_list = array(
          array('name' => 'Carpinteria' , 'service_type_id' => 1),
          array('name' => 'Contratista Maestro Obra Reparaciones Generales' , 'service_type_id' => 1),
          array('name' => 'Electricista' , 'service_type_id' => 1),
          array('name' => 'Excavaciones y movimiento de tierra' , 'service_type_id' => 1),
          array('name' => 'Contratista de canaletas/ Reparaciones Techos' , 'service_type_id' => 1),
          array('name' => 'Jardineria' , 'service_type_id' => 1),
          array('name' => 'Contratista de pisos / Ceramica' , 'service_type_id' => 1),
          array('name' => 'Climatización' , 'service_type_id' => 1),
          array('name' => 'Impermebealizacion' , 'service_type_id' => 1),
          array('name' => 'Pintura de Paredes y Techos' , 'service_type_id' => 1),
          array('name' => 'Remodelador de Cocina y Baños' , 'service_type_id' => 1),
          array('name' => 'Construcción de Piscinas & Mantenimiento Piscinas' , 'service_type_id' => 1),
          array('name' => 'Construcción de Obras' , 'service_type_id' => 1),
          array('name' => 'Instalación Puertas y Ventanas' , 'service_type_id' => 1),
          array('name' => 'Instalación y Reparación de Gabinetes y closets' , 'service_type_id' => 1),
          array('name' => 'Paisajistas' , 'service_type_id' => 1),
          array('name' => 'Instalación de Pisos Flotantes' , 'service_type_id' => 1),
          array('name' => 'Experto Instalación de Malla de Seguridad' , 'service_type_id' => 1),
          array('name' => 'Limpieza de Alfombras' , 'service_type_id' => 1),
          array('name' => 'Instalación de Lamparas' , 'service_type_id' => 1),
          array('name' => 'Gasfisteria' , 'service_type_id' => 1),
          array('name' => 'Instalacion de alarmas y sistema de seguridad perimetral' , 'service_type_id' => 1),
          array('name' => 'Tapiceria y reparación de Muebles' , 'service_type_id' => 1),
          array('name' => 'Otras reparaciones' , 'service_type_id' => 1),
          array('name' => 'Asesora de Hogar Puertas Afuera', 'service_type_id' => 2 ),
          array('name' => 'Asesora Hogar Puertas Adentro', 'service_type_id' => 2 ),
          array('name' => 'Control de Plagas', 'service_type_id' => 2 ),
          array('name' => 'Confeccionista Cortinas' , 'service_type_id' => 2 ),
          array('name' => 'Vigilantes y Guardia de Seguridad', 'service_type_id' => 2 ),
          array('name' => 'Decorador de Interiores', 'service_type_id' => 2 ),
          array('name' => 'Servicio de mudanzas', 'service_type_id' => 2 ),
          array('name' => 'Otros Servicios en General', 'service_type_id' => 2 ),
          array('name' => 'Profesores Particulares Educación General', 'service_type_id' => 3 ),
          array('name' => 'Nutricionistas', 'service_type_id' => 3 ),
          array('name' => 'Cuidador de Adulto Mayor', 'service_type_id' => 3 ),
          array('name' => 'Arquitectos', 'service_type_id' => 3 ),
          array('name' => 'Profesionales expertos en Computación y Electronica Menor', 'service_type_id' => 3 ),
          array('name' => 'Diseñador de interiores', 'service_type_id' => 3 ),
          array('name' => 'Profesor de Gym', 'service_type_id' => 3 ),
          array('name' => 'Profesor de Yoga', 'service_type_id' => 3 ),
          array('name' => 'Profesor de pilates', 'service_type_id' => 3 ),
          array('name' => 'Kinesiologo', 'service_type_id' => 3 ),
          array('name' => 'Cocinero Chef', 'service_type_id' => 3 ),
          array('name' => 'Enfermeras', 'service_type_id' => 3 ),
          array('name' => 'Fonoaudiolo', 'service_type_id' => 3 ),
          array('name' => 'Profesor Particular Clases Ingles', 'service_type_id' => 3 ),
          array('name' => 'Profesor Particular Clases Matematica', 'service_type_id' => 3 ),
          array('name' => 'Profesor Particular clases Lenguaje', 'service_type_id' => 3 ),
          array('name' => 'Reposteria & Preparación de Tortas', 'service_type_id' => 3 ),
          array('name' => 'Veterinarios', 'service_type_id' => 3 ),
          array('name' => 'Otros Profesionales', 'service_type_id' => 3 )
        );
        DB::table('services_list')->insert($services_list);
    }
}
