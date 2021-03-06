<?php

use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $communes = array(
            array('name' => 'Arica', 'city_id' =>1),
            array('name' => 'Camarones', 'city_id' =>1),
            array('name' => 'General Lagos', 'city_id' =>2),
            array('name' => 'Putre', 'city_id' =>2),
            array('name' => 'Alto Hospicio', 'city_id' =>3),
            array('name' => 'Iquique', 'city_id' =>3),
            array('name' => 'Camiña', 'city_id' =>4),
            array('name' => 'Colchane', 'city_id' =>4),
            array('name' => 'Huara', 'city_id' =>4),
            array('name' => 'Pica', 'city_id' =>4),
            array('name' => 'Pozo Almonte', 'city_id' =>4),
            array('name' => 'Antofagasta', 'city_id' =>5),
            array('name' => 'Mejillones', 'city_id' =>5),
            array('name' => 'Sierra Gorda', 'city_id' =>5),
            array('name' => 'Taltal', 'city_id' =>5),
            array('name' => 'Calama', 'city_id' =>6),
            array('name' => 'Ollague', 'city_id' =>6),
            array('name' => 'San Pedro de Atacama', 'city_id' =>6),
            array('name' => 'María Elena', 'city_id' =>7),
            array('name' => 'Tocopilla', 'city_id' =>7),
            array('name' => 'Chañaral', 'city_id' =>8),
            array('name' => 'Diego de Almagro', 'city_id' =>8),
            array('name' => 'Caldera', 'city_id' =>9),
            array('name' => 'Copiapó', 'city_id' =>9),
            array('name' => 'Tierra Amarilla', 'city_id' =>9),
            array('name' => 'Alto del Carmen', 'city_id' =>10),
            array('name' => 'Freirina', 'city_id' =>10),
            array('name' => 'Huasco', 'city_id' =>10),
            array('name' => 'Vallenar', 'city_id' =>10),
            array('name' => 'Canela', 'city_id' =>11),
            array('name' => 'Illapel', 'city_id' =>11),
            array('name' => 'Los Vilos', 'city_id' =>11),
            array('name' => 'Salamanca', 'city_id' =>11),
            array('name' => 'Andacollo', 'city_id' =>12),
            array('name' => 'Coquimbo', 'city_id' =>12),
            array('name' => 'La Higuera', 'city_id' =>12),
            array('name' => 'La Serena', 'city_id' =>12),
            array('name' => 'Paihuaco', 'city_id' =>12),
            array('name' => 'Vicuña', 'city_id' =>12),
            array('name' => 'Combarbalá', 'city_id' =>13),
            array('name' => 'Monte Patria', 'city_id' =>13),
            array('name' => 'Ovalle', 'city_id' =>13),
            array('name' => 'Punitaqui', 'city_id' =>13),
            array('name' => 'Río Hurtado', 'city_id' =>13),
            array('name' => 'Isla de Pascua', 'city_id' =>14),
            array('name' => 'Calle Larga', 'city_id' =>15),
            array('name' => 'Los Andes', 'city_id' =>15),
            array('name' => 'Rinconada', 'city_id' =>15),
            array('name' => 'San Esteban', 'city_id' =>15),
            array('name' => 'La Ligua', 'city_id' =>16),
            array('name' => 'Papudo', 'city_id' =>16),
            array('name' => 'Petorca', 'city_id' =>16),
            array('name' => 'Zapallar', 'city_id' =>16),
            array('name' => 'Hijuelas', 'city_id' =>17),
            array('name' => 'La Calera', 'city_id' =>17),
            array('name' => 'La Cruz', 'city_id' =>17),
            array('name' => 'Limache', 'city_id' =>17),
            array('name' => 'Nogales', 'city_id' =>17),
            array('name' => 'Olmué', 'city_id' =>17),
            array('name' => 'Quillota', 'city_id' =>17),
            array('name' => 'Algarrobo', 'city_id' =>18),
            array('name' => 'Cartagena', 'city_id' =>18),
            array('name' => 'El Quisco', 'city_id' =>18),
            array('name' => 'El Tabo', 'city_id' =>18),
            array('name' => 'San Antonio', 'city_id' =>18),
            array('name' => 'Santo Domingo', 'city_id' =>18),
            array('name' => 'Catemu', 'city_id' =>19),
            array('name' => 'Llaillay', 'city_id' =>19),
            array('name' => 'Panquehue', 'city_id' =>19),
            array('name' => 'Putaendo', 'city_id' =>19),
            array('name' => 'San Felipe', 'city_id' =>19),
            array('name' => 'Santa María', 'city_id' =>19),
            array('name' => 'Casablanca', 'city_id' =>20),
            array('name' => 'Concón', 'city_id' =>20),
            array('name' => 'Juan Fernández', 'city_id' =>20),
            array('name' => 'Puchuncaví', 'city_id' =>20),
            array('name' => 'Quilpué', 'city_id' =>20),
            array('name' => 'Quintero', 'city_id' =>20),
            array('name' => 'Valparaíso', 'city_id' =>20),
            array('name' => 'Villa Alemana', 'city_id' =>20),
            array('name' => 'Viña del Mar', 'city_id' =>20),
            array('name' => 'Colina', 'city_id' =>21),
            array('name' => 'Lampa', 'city_id' =>21),
            array('name' => 'Tiltil', 'city_id' =>21),
            array('name' => 'Pirque', 'city_id' =>22),
            array('name' => 'Puente Alto', 'city_id' =>22),
            array('name' => 'San José de Maipo', 'city_id' =>22),
            array('name' => 'Buin', 'city_id' =>23),
            array('name' => 'Calera de Tango', 'city_id' =>23),
            array('name' => 'Paine', 'city_id' =>23),
            array('name' => 'San Bernardo', 'city_id' =>23),
            array('name' => 'Alhué', 'city_id' =>24),
            array('name' => 'Curacaví', 'city_id' =>24),
            array('name' => 'María Pinto', 'city_id' =>24),
            array('name' => 'Melipilla', 'city_id' =>24),
            array('name' => 'San Pedro', 'city_id' =>24),
            array('name' => 'Cerrillos', 'city_id' =>25),
            array('name' => 'Cerro Navia', 'city_id' =>25),
            array('name' => 'Conchalí', 'city_id' =>25),
            array('name' => 'El Bosque', 'city_id' =>25),
            array('name' => 'Estación Central', 'city_id' =>25),
            array('name' => 'Huechuraba', 'city_id' =>25),
            array('name' => 'Independencia', 'city_id' =>25),
            array('name' => 'La Cisterna', 'city_id' =>25),
            array('name' => 'La Granja', 'city_id' =>25),
            array('name' => 'La Florida', 'city_id' =>25),
            array('name' => 'La Pintana', 'city_id' =>25),
            array('name' => 'La Reina', 'city_id' =>25),
            array('name' => 'Las Condes', 'city_id' =>25),
            array('name' => 'Lo Barnechea', 'city_id' =>25),
            array('name' => 'Lo Espejo', 'city_id' =>25),
            array('name' => 'Lo Prado', 'city_id' =>25),
            array('name' => 'Macul', 'city_id' =>25),
            array('name' => 'Maipú', 'city_id' =>25),
            array('name' => 'Ñuñoa', 'city_id' =>25),
            array('name' => 'Pedro Aguirre Cerda', 'city_id' =>25),
            array('name' => 'Peñalolén', 'city_id' =>25),
            array('name' => 'Providencia', 'city_id' =>25),
            array('name' => 'Pudahuel', 'city_id' =>25),
            array('name' => 'Quilicura', 'city_id' =>25),
            array('name' => 'Quinta Normal', 'city_id' =>25),
            array('name' => 'Recoleta', 'city_id' =>25),
            array('name' => 'Renca', 'city_id' =>25),
            array('name' => 'San Miguel', 'city_id' =>25),
            array('name' => 'San Joaquín', 'city_id' =>25),
            array('name' => 'San Ramón', 'city_id' =>25),
            array('name' => 'Santiago', 'city_id' =>25),
            array('name' => 'Vitacura', 'city_id' =>25),
            array('name' => 'El Monte', 'city_id' =>26),
            array('name' => 'Isla de Maipo', 'city_id' =>26),
            array('name' => 'Padre Hurtado', 'city_id' =>26),
            array('name' => 'Peñaflor', 'city_id' =>26),
            array('name' => 'Talagante', 'city_id' =>26),
            array('name' => 'Codegua', 'city_id' =>27),
            array('name' => 'Coínco', 'city_id' =>27),
            array('name' => 'Coltauco', 'city_id' =>27),
            array('name' => 'Doñihue', 'city_id' =>27),
            array('name' => 'Graneros', 'city_id' =>27),
            array('name' => 'Las Cabras', 'city_id' =>27),
            array('name' => 'Machalí', 'city_id' =>27),
            array('name' => 'Malloa', 'city_id' =>27),
            array('name' => 'Mostazal', 'city_id' =>27),
            array('name' => 'Olivar', 'city_id' =>27),
            array('name' => 'Peumo', 'city_id' =>27),
            array('name' => 'Pichidegua', 'city_id' =>27),
            array('name' => 'Quinta de Tilcoco', 'city_id' =>27),
            array('name' => 'Rancagua', 'city_id' =>27),
            array('name' => 'Rengo', 'city_id' =>27),
            array('name' => 'Requínoa', 'city_id' =>27),
            array('name' => 'San Vicente de Tagua Tagua', 'city_id' =>27),
            array('name' => 'La Estrella', 'city_id' =>28),
            array('name' => 'Litueche', 'city_id' =>28),
            array('name' => 'Marchihue', 'city_id' =>28),
            array('name' => 'Navidad', 'city_id' =>28),
            array('name' => 'Peredones', 'city_id' =>28),
            array('name' => 'Pichilemu', 'city_id' =>28),
            array('name' => 'Chépica', 'city_id' =>29),
            array('name' => 'Chimbarongo', 'city_id' =>29),
            array('name' => 'Lolol', 'city_id' =>29),
            array('name' => 'Nancagua', 'city_id' =>29),
            array('name' => 'Palmilla', 'city_id' =>29),
            array('name' => 'Peralillo', 'city_id' =>29),
            array('name' => 'Placilla', 'city_id' =>29),
            array('name' => 'Pumanque', 'city_id' =>29),
            array('name' => 'San Fernando', 'city_id' =>29),
            array('name' => 'Santa Cruz', 'city_id' =>29),
            array('name' => 'Cauquenes', 'city_id' =>30),
            array('name' => 'Chanco', 'city_id' =>30),
            array('name' => 'Pelluhue', 'city_id' =>30),
            array('name' => 'Curicó', 'city_id' =>31),
            array('name' => 'Hualañé', 'city_id' =>31),
            array('name' => 'Licantén', 'city_id' =>31),
            array('name' => 'Molina', 'city_id' =>31),
            array('name' => 'Rauco', 'city_id' =>31),
            array('name' => 'Romeral', 'city_id' =>31),
            array('name' => 'Sagrada Familia', 'city_id' =>31),
            array('name' => 'Teno', 'city_id' =>31),
            array('name' => 'Vichuquén', 'city_id' =>31),
            array('name' => 'Colbún', 'city_id' =>32),
            array('name' => 'Linares', 'city_id' =>32),
            array('name' => 'Longaví', 'city_id' =>32),
            array('name' => 'Parral', 'city_id' =>32),
            array('name' => 'Retiro', 'city_id' =>32),
            array('name' => 'San Javier', 'city_id' =>32),
            array('name' => 'Villa Alegre', 'city_id' =>32),
            array('name' => 'Yerbas Buenas', 'city_id' =>32),
            array('name' => 'Constitución', 'city_id' =>33),
            array('name' => 'Curepto', 'city_id' =>33),
            array('name' => 'Empedrado', 'city_id' =>33),
            array('name' => 'Maule', 'city_id' =>33),
            array('name' => 'Pelarco', 'city_id' =>33),
            array('name' => 'Pencahue', 'city_id' =>33),
            array('name' => 'Río Claro', 'city_id' =>33),
            array('name' => 'San Clemente', 'city_id' =>33),
            array('name' => 'San Rafael', 'city_id' =>33),
            array('name' => 'Talca', 'city_id' =>33),
            array('name' => 'Arauco', 'city_id' =>34),
            array('name' => 'Cañete', 'city_id' =>34),
            array('name' => 'Contulmo', 'city_id' =>34),
            array('name' => 'Curanilahue', 'city_id' =>34),
            array('name' => 'Lebu', 'city_id' =>34),
            array('name' => 'Los Álamos', 'city_id' =>34),
            array('name' => 'Tirúa', 'city_id' =>34),
            array('name' => 'Alto Biobío', 'city_id' =>35),
            array('name' => 'Antuco', 'city_id' =>35),
            array('name' => 'Cabrero', 'city_id' =>35),
            array('name' => 'Laja', 'city_id' =>35),
            array('name' => 'Los Ángeles', 'city_id' =>35),
            array('name' => 'Mulchén', 'city_id' =>35),
            array('name' => 'Nacimiento', 'city_id' =>35),
            array('name' => 'Negrete', 'city_id' =>35),
            array('name' => 'Quilaco', 'city_id' =>35),
            array('name' => 'Quilleco', 'city_id' =>35),
            array('name' => 'San Rosendo', 'city_id' =>35),
            array('name' => 'Santa Bárbara', 'city_id' =>35),
            array('name' => 'Tucapel', 'city_id' =>35),
            array('name' => 'Yumbel', 'city_id' =>35),
            array('name' => 'Chiguayante', 'city_id' =>36),
            array('name' => 'Concepción', 'city_id' =>36),
            array('name' => 'Coronel', 'city_id' =>36),
            array('name' => 'Florida', 'city_id' =>36),
            array('name' => 'Hualpén', 'city_id' =>36),
            array('name' => 'Hualqui', 'city_id' =>36),
            array('name' => 'Lota', 'city_id' =>36),
            array('name' => 'Penco', 'city_id' =>36),
            array('name' => 'San Pedro de La Paz', 'city_id' =>36),
            array('name' => 'Santa Juana', 'city_id' =>36),
            array('name' => 'Talcahuano', 'city_id' =>36),
            array('name' => 'Tomé', 'city_id' =>36),
            array('name' => 'Bulnes', 'city_id' =>37),
            array('name' => 'Chillán', 'city_id' =>37),
            array('name' => 'Chillán Viejo', 'city_id' =>37),
            array('name' => 'Cobquecura', 'city_id' =>37),
            array('name' => 'Coelemu', 'city_id' =>37),
            array('name' => 'Coihueco', 'city_id' =>37),
            array('name' => 'El Carmen', 'city_id' =>37),
            array('name' => 'Ninhue', 'city_id' =>37),
            array('name' => 'Ñiquen', 'city_id' =>37),
            array('name' => 'Pemuco', 'city_id' =>37),
            array('name' => 'Pinto', 'city_id' =>37),
            array('name' => 'Portezuelo', 'city_id' =>37),
            array('name' => 'Quillón', 'city_id' =>37),
            array('name' => 'Quirihue', 'city_id' =>37),
            array('name' => 'Ránquil', 'city_id' =>37),
            array('name' => 'San Carlos', 'city_id' =>37),
            array('name' => 'San Fabián', 'city_id' =>37),
            array('name' => 'San Ignacio', 'city_id' =>37),
            array('name' => 'San Nicolás', 'city_id' =>37),
            array('name' => 'Treguaco', 'city_id' =>37),
            array('name' => 'Yungay', 'city_id' =>37),
            array('name' => 'Carahue', 'city_id' =>38),
            array('name' => 'Cholchol', 'city_id' =>38),
            array('name' => 'Cunco', 'city_id' =>38),
            array('name' => 'Curarrehue', 'city_id' =>38),
            array('name' => 'Freire', 'city_id' =>38),
            array('name' => 'Galvarino', 'city_id' =>38),
            array('name' => 'Gorbea', 'city_id' =>38),
            array('name' => 'Lautaro', 'city_id' =>38),
            array('name' => 'Loncoche', 'city_id' =>38),
            array('name' => 'Melipeuco', 'city_id' =>38),
            array('name' => 'Nueva Imperial', 'city_id' =>38),
            array('name' => 'Padre Las Casas', 'city_id' =>38),
            array('name' => 'Perquenco', 'city_id' =>38),
            array('name' => 'Pitrufquén', 'city_id' =>38),
            array('name' => 'Pucón', 'city_id' =>38),
            array('name' => 'Saavedra', 'city_id' =>38),
            array('name' => 'Temuco', 'city_id' =>38),
            array('name' => 'Teodoro Schmidt', 'city_id' =>38),
            array('name' => 'Toltén', 'city_id' =>38),
            array('name' => 'Vilcún', 'city_id' =>38),
            array('name' => 'Villarrica', 'city_id' =>38),
            array('name' => 'Angol', 'city_id' =>39),
            array('name' => 'Collipulli', 'city_id' =>39),
            array('name' => 'Curacautín', 'city_id' =>39),
            array('name' => 'Ercilla', 'city_id' =>39),
            array('name' => 'Lonquimay', 'city_id' =>39),
            array('name' => 'Los Sauces', 'city_id' =>39),
            array('name' => 'Lumaco', 'city_id' =>39),
            array('name' => 'Purén', 'city_id' =>39),
            array('name' => 'Renaico', 'city_id' =>39),
            array('name' => 'Traiguén', 'city_id' =>39),
            array('name' => 'Victoria', 'city_id' =>39),
            array('name' => 'Corral', 'city_id' =>40),
            array('name' => 'Lanco', 'city_id' =>40),
            array('name' => 'Los Lagos', 'city_id' =>40),
            array('name' => 'Máfil', 'city_id' =>40),
            array('name' => 'Mariquina', 'city_id' =>40),
            array('name' => 'Paillaco', 'city_id' =>40),
            array('name' => 'Panguipulli', 'city_id' =>40),
            array('name' => 'Valdivia', 'city_id' =>40),
            array('name' => 'Futrono', 'city_id' =>41),
            array('name' => 'La Unión', 'city_id' =>41),
            array('name' => 'Lago Ranco', 'city_id' =>41),
            array('name' => 'Río Bueno', 'city_id' =>41),
            array('name' => 'Ancud', 'city_id' =>42),
            array('name' => 'Castro', 'city_id' =>42),
            array('name' => 'Chonchi', 'city_id' =>42),
            array('name' => 'Curaco de Vélez', 'city_id' =>42),
            array('name' => 'Dalcahue', 'city_id' =>42),
            array('name' => 'Puqueldón', 'city_id' =>42),
            array('name' => 'Queilén', 'city_id' =>42),
            array('name' => 'Quemchi', 'city_id' =>42),
            array('name' => 'Quellón', 'city_id' =>42),
            array('name' => 'Quinchao', 'city_id' =>42),
            array('name' => 'Calbuco', 'city_id' =>43),
            array('name' => 'Cochamó', 'city_id' =>43),
            array('name' => 'Fresia', 'city_id' =>43),
            array('name' => 'Frutillar', 'city_id' =>43),
            array('name' => 'Llanquihue', 'city_id' =>43),
            array('name' => 'Los Muermos', 'city_id' =>43),
            array('name' => 'Maullín', 'city_id' =>43),
            array('name' => 'Puerto Montt', 'city_id' =>43),
            array('name' => 'Puerto Varas', 'city_id' =>43),
            array('name' => 'Osorno', 'city_id' =>44),
            array('name' => 'Puero Octay', 'city_id' =>44),
            array('name' => 'Purranque', 'city_id' =>44),
            array('name' => 'Puyehue', 'city_id' =>44),
            array('name' => 'Río Negro', 'city_id' =>44),
            array('name' => 'San Juan de la Costa', 'city_id' =>44),
            array('name' => 'San Pablo', 'city_id' =>44),
            array('name' => 'Chaitén', 'city_id' =>45),
            array('name' => 'Futaleufú', 'city_id' =>45),
            array('name' => 'Hualaihué', 'city_id' =>45),
            array('name' => 'Palena', 'city_id' =>45),
            array('name' => 'Aisén', 'city_id' =>46),
            array('name' => 'Cisnes', 'city_id' =>46),
            array('name' => 'Guaitecas', 'city_id' =>46),
            array('name' => 'Cochrane', 'city_id' =>47),
            array('name' => 'O\'higgins', 'city_id' =>47),
            array('name' => 'Tortel', 'city_id' =>47),
            array('name' => 'Coihaique', 'city_id' =>48),
            array('name' => 'Lago Verde', 'city_id' =>48),
            array('name' => 'Chile Chico', 'city_id' =>49),
            array('name' => 'Río Ibáñez', 'city_id' =>49),
            array('name' => 'Antártica', 'city_id' =>50),
            array('name' => 'Cabo de Hornos', 'city_id' =>50),
            array('name' => 'Laguna Blanca', 'city_id' =>51),
            array('name' => 'Punta Arenas', 'city_id' =>51),
            array('name' => 'Río Verde', 'city_id' =>51),
            array('name' => 'San Gregorio', 'city_id' =>51),
            array('name' => 'Porvenir', 'city_id' =>52),
            array('name' => 'Primavera', 'city_id' =>52),
            array('name' => 'Timaukel', 'city_id' =>52),
            array('name' => 'Natales', 'city_id' =>53),
            array('name' => 'Torres del Paine', 'city_id' =>53)
        );
        DB::table('communes')->insert($communes);
    }
}
