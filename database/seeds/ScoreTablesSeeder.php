<?php

use Illuminate\Database\Seeder;

class ScoreTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scoring = array(
            array(
                'name' => 'Usuario',
                'description' => 'Scoring de Arrendatario'
            ),
            array(
                'name' => 'Scoring de Propiedad',
                'description' => 'Scoring de propiedad'
            )
        );
        $cat_scoring = array(
            array(
                'scoring_id' => 1,
                'name' => 'Identidad Digital',
                'description' => 'Scoring Validación de Identidad Digital',
                'feed_back' => 'Retroalimentación para Identidad Digital'

            ),
            array(
                'scoring_id' =>1,
                'name' => 'Codeudor ',
                'description' => 'Scoring Validación de Codeudor ',
                'feed_back' => 'Retroalimentación para Validación de Codeudor '
            ),
            array(
                'scoring_id' =>1,
                'name' => 'Estabilidad Socio-Económica',
                'description' => 'Scoring de Estabilidad Socio-Económica',
                'feed_back' => 'Retroalimentación para Estabilidad Socio-Económica '
            ),
            array(
                'scoring_id' =>1,
                'name' => 'Preferencias y Condiciones para arrendar',
                'description' => 'Scoring de Preferencias y Condiciones para arrendar',
                'feed_back' => 'Retroalimentación para Preferencias y Condiciones para arrendar'
            ),
            array(
                'scoring_id' =>1,
                'name' => 'Membresías',
                'description' => 'Scoring de Membresías',
                'feed_back' => 'Retroalimentación para Membresías'
            ),
            array(
                'scoring_id' =>1,
                'name' => 'Estabilidad Financiera',
                'description' => 'Scoring de Estabilidad Financiera',
                'feed_back' => 'Retroalimentación para Estabilidad Financiera'
            ),

            array(
                'scoring_id' => 2,
                'name' => 'Demanda',
                'description' => 'Scoring Validación de Demanda',
                'feed_back' => 'Retroalimentación para Demanda'

            ),

        );

        $det_scoring = array(
            array(
                'cat_scoring_id' => 1,
                'name' => 'Teléfono',
                'description' => 'Scoring Validación de Teléfono',
                'feed_back' => 'Retroalimentación para Teléfono',
                'points' => 25,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Correo ',
                'description' => 'Scoring Validación de Validación de Correo',
                'feed_back' => 'Retroalimentación para Validación de Correo',
                'points' => 25,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Identidad DNI Frontal',
                'description' => 'Scoring de Identidad DNI Frontal',
                'feed_back' => 'Retroalimentación para Identidad DNI Frontal',
                'points' => 15,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Identidad DNI Lateral',
                'description' => 'Scoring de Identidad DNI Lateral',
                'feed_back' => 'Retroalimentación para Identidad DNI Lateral',
                'points' => 15,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Nacional',
                'description' => 'Scoring de Nacional',
                'feed_back' => 'Retroalimentación para Nacional',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Nacional con RUT',
                'description' => 'Scoring de Nacional con RUT',
                'feed_back' => 'Retroalimentación para Nacional con RUT',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Nacional con RUT Provisional',
                'description' => 'Scoring de Nacional con RUT Provisional',
                'feed_back' => 'Retroalimentación para Nacional con RUT Provisional',
                'points' => 30,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>1,
                'name' => 'Pasaporte',
                'description' => 'Scoring de Pasaporte',
                'feed_back' => 'Retroalimentación para Pasaporte',
                'points' => 20,
                'method' => 0,
            ),


            array(
                'cat_scoring_id' => 2,
                'name' => 'Confirmación  de Aval',
                'description' => 'Scoring Confirmación de Aval',
                'feed_back' => 'Retroalimentación para  Confirmación de Aval',
                'points' => 100,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 2,
                'name' => ' Sin Confirmación  de Aval',
                'description' => 'Scoring Sin Confirmación de Aval',
                'feed_back' => 'Retroalimentación para  Sin Confirmación de Aval',
                'points' => 50,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' =>2,
                'name' => 'Seguro de Arriendo ',
                'description' => 'Scoring de Seguro de Arriendo',
                'feed_back' => 'Retroalimentación para Seguro de Arriendo',
                'points' => 100,
                'method' => 0,
            ),            
            array(
                'cat_scoring_id' => 2,
                'name' => 'Teléfono Aval',
                'description' => 'Scoring Validación de Teléfono Aval',
                'feed_back' => 'Retroalimentación para Teléfono Aval',
                'points' => 25,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Correo Aval',
                'description' => 'Scoring  Validación de Correo Aval',
                'feed_back' => 'Retroalimentación para Validación de Correo Aval',
                'points' => 25,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' =>2,
                'name' => 'Identidad DNI Frontal -Aval',
                'description' => 'Scoring de Identidad DNI Frontal-Aval',
                'feed_back' => 'Retroalimentación para Identidad DNI Frontal-Aval',
                'points' => 15,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Identidad DNI Lateral -Aval',
                'description' => 'Scoring de Identidad DNI Lateral-Aval',
                'feed_back' => 'Retroalimentación para Identidad DNI Lateral-Aval',
                'points' => 15,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Nacional-Aval',
                'feed_back' => 'Retroalimentación para Nacional',
                'description' => 'Scoring de Nacional-Aval',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Nacional con RUT-Aval',
                'description' => 'Scoring de Nacional con RUT-Aval',
                'feed_back' => 'Retroalimentación para Nacional con RUT-Aval',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Nacional con RUT Provisional-Aval',
                'description' => 'Scoring de Nacional con RUT Provisional-Aval',
                'feed_back' => 'Retroalimentación para Nacional con RUT Provisional-Aval',
                'points' => 30,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>2,
                'name' => 'Pasaporte ',
                'description' => 'Scoring de Pasaporte-Aval',
                'feed_back' => 'Retroalimentación para Pasaporte-Aval',
                'points' => 20,
                'method' => 0,
            ),






            array(
                'cat_scoring_id' => 3,
                'name' => 'Empleado',
                'description' => 'Scoring Empleado',
                'feed_back' => 'Retroalimentación para Empleado',
                'points' => 200,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'AFP Empleado',
                'description' => 'Scoring AFP Empleado',
                'feed_back' => 'Retroalimentación para AFP Empleado',
                'points' => 20,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'DICOM Empleado',
                'description' => 'Scoring DICOM Empleado',
                'feed_back' => 'Retroalimentación para DICOM Empleado',
                'points' => 0,
                'method' => 1,
            ),

            array(
                'cat_scoring_id' => 3,
                'name' => 'Liquidación 1 Empleado',
                'description' => 'Scoring Liquidación 1 Empleado',
                'feed_back' => 'Retroalimentación para Liquidación 1 Empleado',
                'points' => 5,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'Liquidación 2 Empleado',
                'description' => 'Scoring Liquidación 2 Empleado',
                'feed_back' => 'Retroalimentación para Liquidación 2 Empleado',
                'points' => 5,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'Liquidación 3 Empleado',
                'description' => 'Scoring Liquidación 3 Empleado',
                'feed_back' => 'Retroalimentación para Liquidación 3 Empleado',
                'points' => 5,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'Certificado de Empleo',
                'description' => 'Scoring Certificado de Empleo',
                'feed_back' => 'Retroalimentación para Certificado de Empleo',
                'points' => 15,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' => 3,
                'name' => 'Freelancer',
                'description' => 'Scoring Freelancer',
                'feed_back' => 'Retroalimentación para Freelancer',
                'points' => 170,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' => 3,
                'name' => 'AFP Freelancer',
                'description' => 'Scoring Validación de AFP Freelancer',
                'feed_back' => 'Retroalimentación para Validación AFP Freelancer',
                'points' => 20,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'DICOM Freelancer',
                'description' => 'Scoring de DICOM Freelancer',
                'feed_back' => 'Retroalimentación para DICOM Freelancer',
                'points' => 0,
                'method' => 1,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'Respaldo Otros Ingresos Freelancer',
                'description' => 'Scoring de Respaldo Otros Ingresos Freelancer',
                'feed_back' => 'Retroalimentación para  Respaldo Otros Ingresos Freelancer',
                'points' => 10,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' =>3,
                'name' => 'Cuenta Bancaria / Ahorro Freelancer',
                'feed_back' => 'Retroalimentación para Cuenta Bancaria / Ahorro Freelancer' ,
                'description' => 'Scoring de Cuenta Bancaria / Ahorro Freelancer',
                'points' => 10,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'Boleta de Honorarios Freelancer',
                'description' => 'Scoring de Boleta de Honorarios Freelancer',
                'feed_back' => 'Retroalimentación para Boleta de Honorarios Freelancer',
                'points' => 10,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'Desempleado',
                'description' => 'Scoring de Desempleado',
                'feed_back' => 'Retroalimentación para  Desempleado',
                'points' => 100,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'AFP Desempleado',
                'description' => 'Scoring de AFP Desempleado',
                'feed_back' => 'Retroalimentación para AFP Desempleado',
                'points' => 20,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'DICOM Desempleado',
                'description' => 'Scoring de DICOM Desempleado',
                'feed_back' => 'Retroalimentación para  DICOM Desempleado',
                'points' => 0,
                'method' => 1,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'Respaldo Otros Ingresos Desempleado',
                'description' => 'Scoring de Respaldo Otros Ingresos Desempleado',
                'feed_back' => 'Retroalimentación para  Respaldo Otros Ingresos Desempleado',
                'points' => 10,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>3,
                'name' => 'Cuenta Bancaria / Ahorro Desempleado',
                'description' => 'Scoring de Cuenta Bancaria / Ahorro Desempleado',
                'feed_back' => 'Retroalimentación para Cuenta Bancaria / Ahorro  Desempleado',
                'points' => 20,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Tipo de Propiedad',
                'description' => 'Scoring de Tipo de Propiedad',
                'feed_back' => 'Retroalimentación para  Tipo de Propiedad',
                'points' => 1,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' =>4,
                'name' => 'Tipo de Residente',
                'description' => 'Scoring de Tipo de Residente (Solteros, casados ...)',
                'feed_back' => 'Retroalimentación para  Tipo de Residente ',
                'points' => 1,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Fecha de Mudanza',
                'description' => 'Scoring de Fecha de Mudanza',
                'feed_back' => 'Retroalimentación para  Fecha de Mudanza',
                'points' => 1,
                'method' => 0,

            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Mascota',
                'description' => 'Scoring de  Mascota',
                'feed_back' => 'Retroalimentación para Mascota',
                'points' => 1,
                'method' => 0,

            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Fumador',
                'description' => 'Scoring de Fumador',
                'feed_back' => 'Retroalimentación para Fumador',
                'points' => 1,
                'method' => 0,
            ),

            array(
                'cat_scoring_id' =>4,
                'name' => ' 1 Mes de garantia',
                'description' => 'Scoring de 1 Mes de garantia',
                'feed_back' => 'Retroalimentación para  1 Mes de garantia',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => '2 Meses de garantia',
                'description' => 'Scoring de 2 Meses de garantia',
                'feed_back' => 'Retroalimentación para  2 Meses de garantia',
                'points' => 60,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Mas de 2 Meses de garantia',
                'description' => 'Scoring de Mas de 2 Meses de garantia',
                'feed_back' => 'Retroalimentación para Mas de 2 Meses de garantia',
                'points' => 70,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => '1 Mes de Adelanto',
                'description' => 'Scoring de 1 Mes de Adelanto',
                'feed_back' => 'Retroalimentación para 1 Mes de Adelanto',
                'points' => 50,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => '2 Meses de Adelanto',
                'description' => 'Scoring de 2 Meses de Adelanto',
                'feed_back' => 'Retroalimentación para 2 Meses de Adelanto',
                'points' => 60,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Mas de 2 Meses de Adelanto',
                'description' => 'Scoring de Mas de 2  Meses de Adelanto',
                'feed_back' => 'Retroalimentación para Mas de 2  Meses de Adelanto',
                'points' => 70,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Tiempo de Arriendo menor a 12 meses',
                'description' => 'Scoring de Tiempo de Arriendo menor a 12 meses',
                'feed_back' => 'Retroalimentación para  Tiempo de Arriendo menor a 12 meses',
                'points' => 5,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>4,
                'name' => 'Tiempo de Arriendo igual o mayor a 12 meses',
                'description' => 'Scoring de Tiempo de Arriendo igual o  mayor a 12 meses',
                'feed_back' => 'Retroalimentación para  Tiempo de Arriendo igual o mayor a 12 meses',
                'points' => 20,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>5,
                'name' => 'Membresía Basic',
                'description' => 'Scoring de Membresía Basic',
                'feed_back' => 'Retroalimentación para  Membresía Basic',
                'points' => 30,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>5,
                'name' => 'Membresía Select',
                'description' => 'Scoring de Membresía Select',
                'feed_back' => 'Retroalimentación para  Membresía Select',
                'points' => 70,
                'method' => 0,
            ),
            array(
                'cat_scoring_id' =>5,
                'name' => 'Membresía Prime',
                'description' => 'Scoring de Membresía Prime',
                'feed_back' => 'Retroalimentación para  Membresía Prime',
                'points' => 90,
                'method' => 0,
            ),



            array(
                'cat_scoring_id' =>6,
                'name' => 'Variable Financiera',
                'description' => 'Scoring de Variable Financiera',
                'feed_back' => 'Retroalimentación para Variable Financiera',
                'points' => 420,
                'method' => 1,
            ),

            array(
                'cat_scoring_id' =>7,
                'name' => 'Demanda Alta',
                'description' => 'Scoring de Demanda Alta',
                'feed_back' => 'Retroalimentación para Demanda Alta',
                'points' => 70,
                'method' => 0,

            ),
            array(
                'cat_scoring_id' =>7,
                'name' => 'Demanda Media',
                'description' => 'Scoring de Demanda Media',
                'feed_back' => 'Retroalimentación para Demanda Media',
                'points' => 60,
                'method' => 0,

            ),

            array(
                'cat_scoring_id' =>7,
                'name' => 'Demanda Baja',
                'description' => 'Scoring de Demanda Baja',
                'feed_back' => 'Retroalimentación para Demanda Baja',
                'points' => 50,
                'method' => 0,

            ),

        );


        DB::table('scoring')->insert($scoring);
        DB::table('cat_scoring')->insert($cat_scoring);
        DB::table('det_scoring')->insert($det_scoring);


    }
}
