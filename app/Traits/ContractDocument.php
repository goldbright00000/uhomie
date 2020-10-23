<?php

namespace App\Traits;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use HelloSign\Client;
use HelloSign\SignatureRequest;
use HelloSign\Signer;
use HelloSign\Account;
use NumeroALetras\NumeroALetras;
use Carbon\Carbon;
use App\File;
use Illuminate\Support\Facades\Storage;


trait ContractDocument {
    
    protected $arrendador_nombre;	
    protected $fecha;
    protected $ciudad;
    protected $arrendador_nacionalidad;
    protected $arrendador_estado_civil;
    protected $arrendador_profesion;
    protected $arrendador_documento;
    protected $arrendador_domicilio;
    protected $arrendador_comuna;
    protected $arrendatario_nombre;
    protected $arrendatario_nacionalidad;	
    protected $arrendatario_estado_civil;
    protected $arrendatario_documento;
    protected $arrendatario_domicilio;
    protected $arrendatario_comuna;
    protected $arrendatario_ciudad;
    protected $propiedad_ciudad;
    protected $propiedad_estacionamiento_numero;
    protected $propiedad_bodega_numero;
    protected $propiedad_comuna;
    protected $propiedad_region;
    protected $propiedad_direccion;
    protected $meses_arriendo;
    protected $inicio_dia;
    protected $inicio_mes;
    protected $inicio_anio;
    protected $fin_dia; 
    protected $fin_mes;	
    protected $fin_anio;
    protected $monto_arriendo;
    protected $monto_arriendo_letras;
    protected $tipo_cuenta;
    protected $cuenta;
    protected $banco;
    protected $garantia_monto;
    protected $aval_nombre;
    protected $aval_nacionalidad;
    protected $aval_estado_civil;
    protected $aval_profesion;
    protected $aval_documento;
    protected $aval_direccion;
    protected $aval_comuna;
    protected $aval_ciudad;
    protected $inventario_linea;
    protected $multa;
    
    protected $contrato = '';


    public function toStream($ruta, $id)
    {
        return Storage::disk('contratos')->download('contrato_'.$id.'_pre.pdf');
    }
    public static function streamBorrador($owner, $tenant, $property)
    {
        $arrendador_nombre = $owner->firstname.' '.$owner->lastname;
        $arrendador_nacionalidad = $owner->country()->first()->nationality;
        $arrendador_estado_civil = $owner->civilStatus()->first()->name;
        $arrendador_documento = $owner->document_number;
        $arrendador_domicilio = $owner->address.', '.$owner->address_details;
        $arrendador_domicilio = substr($arrendador_domicilio, 0, strpos($arrendador_domicilio, ',')).', '.$owner->address_details;
        $arrendador_comuna = $owner->city()->first()->name;
        $arrendador_region = $owner->city()->first()->region()->first()->name;
        $arrendador_email = $owner->email;    
        //$arrendador_email = $owner->email;
        $arrendatario_nombre = $tenant->firstname.' '.$tenant->lastname;
        $arrendatario_nacionalidad = $tenant->country()->first()->nationality;	
        $arrendatario_estado_civil = $tenant->civilStatus()->first()->name;
        $arrendatario_documento = $tenant->document_number;
        $arrendatario_domicilio = $tenant->address.', '.$tenant->address_details;
        $arrendatario_domicilio = substr($arrendatario_domicilio, 0, strpos($arrendatario_domicilio, ',')).', '.$tenant->address_details;
        $arrendatario_comuna = $tenant->city()->first()->name;
        $arrendatario_region = $tenant->city()->first()->region()->first()->name;
        $arrendatario_email = $tenant->email;
        //$arrendatario_email = $tenant->email;
        $propiedad_ciudad = $property->city()->first()->name;
        $propiedad_estacionamiento_numero = $property->private_parking;
        $propiedad_bodega_numero = $property->cellar;
        $propiedad_comuna = $property->city()->first()->name;
        $propiedad_region = $property->city()->first()->region()->first()->name;
        $propiedad_direccion = $property->address.', '.$property->address_details;
        $propiedad_direccion = substr($propiedad_direccion, 0, strpos($propiedad_direccion, ',')).', '.$property->address_details;
        $meses_arriendo = $property->tenanting_months_quantity;

        // Aqui vamos a poner la fecha actual como la fecha inicial de contrato y la (fecha actual + 1 año) como fecha fin
        Carbon::setLocale('es');
        $fecha_carbon = Carbon::now('America/Santiago');
        setlocale(LC_TIME, 'Spanish');
        
        $fecha = $fecha_carbon->format('d/m/Y');
        $inicio_dia = $fecha_carbon->format('d');
        $inicio_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $inicio_anio = $fecha_carbon->format('Y');

        $fecha_carbon->addMonths($meses_arriendo);

        $fin_dia = $fecha_carbon->format('d'); 
        $fin_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $fin_anio = $fecha_carbon->format('Y');

        $monto_arriendo = $property->rent;
        $monto_arriendo_letras = substr(NumeroALetras::convertir($property->rent),0,-10);

        $tipo_cuenta = $owner->account_type;
        $cuenta = $owner->account_number;
        $banco = $owner->bank()->get()->first() ? $owner->bank()->get()->first()->name : 'Banco no especificado';

        $garantia_monto = $property->warranty_months_quantity*$property->rent;

        $inventario_linea[] = "Lampara";
        $inventario_linea[] = "Cocina";

        $ciudad = 'Santiago';
        
        $contrato_pre = '<html>
                            <head>
                                <meta charset="UTF-8">
                            </head><body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                            <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>A</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                            <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                            <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                            <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                            <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                            <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                            <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                            <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                            <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                            <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p>
                            <p> <strong></strong></p>
                            <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                            <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                            <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                            <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                            <p> <strong></strong></p>
                            <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                            <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                            <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                            <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                            <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                            <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                            <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                            <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                            <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                            <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                            <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                            <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                            <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                            <p> <strong><u></u></strong></p>
                            <p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                            <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                            <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                            <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                            <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                            <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                            <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                            <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Queda prohibido al Arrendatario:</p>
                            <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                            <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                            <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            
                            
                            ';
                            $contrato_pre.= '
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4 c9"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                            
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                            <br>
                            <p class="c6 c4"><span class="c5"></span></p>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; () &nbsp;/ </span><span class="c5">no ser </span><span class="c1">() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br><p class="c3"><span class="c5">Firma: </span><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.' , cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            ';
        
        //continuar remplazando las demas variables

        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        if( $property->anexo_conditions && $property->anexo_conditions != '' ){
            $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
        }

        $contrato_pre .= "</body></html>";

        $prueba = "<div>Hola Mundo</div>";

        $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //ob_start();
        $html2pdf->writeHTML($contrato_pre);
        //return $html2pdf->output('contrato_borrador.pdf', 'D'); 

        //$filename = '\contrato_borrador_'. uniqid() . '.pdf';
        //$output_path = base_path() . '\public\storage\cof' . $filename;
        //$pdf = $html2pdf->output($output_path, 'F');

        //return response()->file($output_path);
        $html2pdf->output('Pre-Contrato.pdf');
        
    }
    public function putPreContractWithoutAval($owner, $tenant, $property){
        $arrendador_nombre = $owner->firstname.' '.$owner->lastname;
        $arrendador_nacionalidad = $owner->country()->first()->nationality;
        $arrendador_estado_civil = $owner->civilStatus()->first()->name;
        $arrendador_documento = $owner->document_number;
        $arrendador_domicilio = $owner->address.', '.$owner->address_details;
        $arrendador_domicilio = substr($arrendador_domicilio, 0, strpos($arrendador_domicilio, ',')).', '.$owner->address_details;
        $arrendador_comuna = $owner->city()->first()->name;
        $arrendador_region = $owner->city()->first()->region()->first()->name;
        $arrendador_email = $owner->email;    
        //$arrendador_email = $owner->email;
        $arrendatario_nombre = $tenant->firstname.' '.$tenant->lastname;
        $arrendatario_nacionalidad = $tenant->country()->first()->nationality;	
        $arrendatario_estado_civil = $tenant->civilStatus()->first()->name;
        $arrendatario_documento = $tenant->document_number;
        $arrendatario_domicilio = $tenant->address.', '.$tenant->address_details;
        $arrendatario_domicilio = substr($arrendatario_domicilio, 0, strpos($arrendatario_domicilio, ',')).', '.$tenant->address_details;
        $arrendatario_comuna = $tenant->city()->first()->name;
        $arrendatario_region = $tenant->city()->first()->region()->first()->name;
        $arrendatario_email = $tenant->email;
        //$arrendatario_email = $tenant->email;
        $propiedad_ciudad = $property->city()->first()->name;
        $propiedad_estacionamiento_numero = $property->private_parking;
        $propiedad_bodega_numero = $property->cellar;
        $propiedad_comuna = $property->city()->first()->name;
        $propiedad_region = $property->city()->first()->region()->first()->name;
        $propiedad_direccion = $property->address.', '.$property->address_details;
        $propiedad_direccion = substr($propiedad_direccion, 0, strpos($propiedad_direccion, ',')).', '.$property->address_details;
        $meses_arriendo = $property->tenanting_months_quantity;

        // Aqui vamos a poner la fecha actual como la fecha inicial de contrato y la (fecha actual + 1 año) como fecha fin
        Carbon::setLocale('es');
        $fecha_carbon = Carbon::now('America/Santiago');
        setlocale(LC_TIME, 'Spanish');
        
        $fecha = $fecha_carbon->format('d/m/Y');
        $inicio_dia = $fecha_carbon->format('d');
        $inicio_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $inicio_anio = $fecha_carbon->format('Y');

        $fecha_carbon->addMonths($meses_arriendo);

        $fin_dia = $fecha_carbon->format('d'); 
        $fin_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $fin_anio = $fecha_carbon->format('Y');

        $monto_arriendo = $property->rent;
        $monto_arriendo_letras = substr(NumeroALetras::convertir($property->rent),0,-10);

        $tipo_cuenta = $owner->account_type;
        $cuenta = $owner->account_number;
        $banco = $owner->bank()->get()->first() ? $owner->bank()->get()->first()->name : 'Banco no especificado';

        $garantia_monto = $property->warranty_months_quantity*$property->rent;

        $inventario_linea[] = "Lampara";
        $inventario_linea[] = "Cocina";

        $ciudad = 'Santiago';
        
        $contrato_pre = '<html>
                            <head>
                                <meta charset="UTF-8">
                            </head><body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                            <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>A</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                            <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                            <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                            <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                            <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                            <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                            <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                            <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                            <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                            <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p>
                            <p> <strong></strong></p>
                            <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                            <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                            <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                            <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                            <p> <strong></strong></p>
                            <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                            <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                            <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                            <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                            <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                            <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                            <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                            <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                            <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                            <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                            <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                            <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                            <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                            <p> <strong><u></u></strong></p>
                            <p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                            <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                            <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                            <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                            <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                            <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                            <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                            <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Queda prohibido al Arrendatario:</p>
                            <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                            <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                            <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            
                            
                            ';
                            $contrato_pre.= '
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4 c9"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                            
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                            <br>
                            <p class="c6 c4"><span class="c5"></span></p>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; () &nbsp;/ </span><span class="c5">no ser </span><span class="c1">() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br><p class="c3"><span class="c5">Firma: </span><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.' , cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            ';
        
        //continuar remplazando las demas variables

        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        if( $property->anexo_conditions && $property->anexo_conditions != '' ){
            $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
        }

        $contrato_pre .= "</body></html>";

        $html2pdf2 = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
        $html2pdf2->pdf->SetDisplayMode('fullpage');
        $html2pdf2->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        ob_start();
        $content = $contrato_pre;
        $html2pdf2->writeHTML($contrato_pre);
        
        Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf', $html2pdf2->output('cualquiercosa','S'));
        
        $this->path_file_pre = 'contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf';


    }
    public function putPreContractWithAval($owner, $tenant, $collateral, $property){
        $arrendador_nombre = $owner->firstname.' '.$owner->lastname;
        $arrendador_nacionalidad = $owner->country()->first()->nationality;
        $arrendador_estado_civil = $owner->civilStatus()->first()->name;
        $arrendador_documento = $owner->document_number;
        $arrendador_domicilio = $owner->address.', '.$owner->address_details;
        $arrendador_domicilio = substr($arrendador_domicilio, 0, strpos($arrendador_domicilio, ',')).', '.$owner->address_details;
        $arrendador_comuna = $owner->city()->first()->name;
        $arrendador_region = $owner->city()->first()->region()->first()->name;
        $arrendador_email = $owner->email;    
        //$arrendador_email = $owner->email;
        $arrendatario_nombre = $tenant->firstname.' '.$tenant->lastname;
        $arrendatario_nacionalidad = $tenant->country()->first()->nationality;	
        $arrendatario_estado_civil = $tenant->civilStatus()->first()->name;
        $arrendatario_documento = $tenant->document_number;
        $arrendatario_domicilio = $tenant->address.', '.$tenant->address_details;
        $arrendatario_domicilio = substr($arrendatario_domicilio, 0, strpos($arrendatario_domicilio, ',')).', '.$tenant->address_details;
        $arrendatario_comuna = $tenant->city()->first()->name;
        $arrendatario_region = $tenant->city()->first()->region()->first()->name;
        $arrendatario_email = $tenant->email;
        //$arrendatario_email = $tenant->email;
        $aval_nombre = $collateral->firstname.' '.$collateral->lastname;
        $aval_nacionalidad = $collateral->country()->first()->nationality;	
        $aval_estado_civil = $collateral->civilStatus()->first()->name;
        $aval_documento = $collateral->document_number;
        $aval_direccion = $collateral->address.', '.$collateral->address_details;
        $aval_direccion = substr($aval_direccion, 0, strpos($aval_direccion, ',')).', '.$collateral->address_details;
        $aval_comuna = $collateral->city()->first()->name;
        $aval_ciudad = $collateral->city()->first()->name; 
        $aval_region = $collateral->city()->first()->region()->first()->name;
        $aval_email = $collateral->email;
        //$aval_email = $collateral->email;
        $propiedad_ciudad = $property->city()->first()->name;
        $propiedad_estacionamiento_numero = $property->private_parking;
        $propiedad_bodega_numero = $property->cellar;
        $propiedad_comuna = $property->city()->first()->name;
        $propiedad_region = $property->city()->first()->region()->first()->name;
        $propiedad_direccion = $property->address.', '.$property->address_details;

        $propiedad_direccion = substr($propiedad_direccion, 0, strpos($propiedad_direccion, ',')).', '.$property->address_details;
        $meses_arriendo = $property->tenanting_months_quantity;

        // Aqui vamos a poner la fecha actual como la fecha inicial de contrato y la (fecha actual + 1 año) como fecha fin
        Carbon::setLocale('es');
        $fecha_carbon = Carbon::now('America/Santiago');
        setlocale(LC_TIME, 'Spanish');
        
        $fecha = $fecha_carbon->format('d/m/Y');
        $inicio_dia = $fecha_carbon->format('d');
        $inicio_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $inicio_anio = $fecha_carbon->format('Y');

        $fecha_carbon->addMonths($meses_arriendo);

        $fin_dia = $fecha_carbon->format('d'); 
        $fin_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $fin_anio = $fecha_carbon->format('Y');

        $monto_arriendo = $property->rent;
        $monto_arriendo_letras = substr(NumeroALetras::convertir($property->rent),0,-10);

        $tipo_cuenta = $owner->account_type;
        $cuenta = $owner->account_number;
        $banco = $owner->bank()->first()->name;

        $garantia_monto = $property->warranty_months_quantity*$property->rent;

        $inventario_linea[] = "Lampara";
        $inventario_linea[] = "Cocina";

        $ciudad = 'Santiago';

        
        $contrato_pre = '<html>
                            <head>
                                <meta charset="UTF-8">
                            </head><body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                            <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>A</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                            <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                            <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                            <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                            <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                            <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                            <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                            <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                            <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                            <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p>
                            <p> <strong></strong></p>
                            <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                            <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                            <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                            <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                            <p> <strong></strong></p>
                            <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                            <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                            <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                            <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                            <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                            <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                            <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                            <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                            <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                            <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                            <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                            <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                            <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                            <p> <strong><u></u></strong></p>
                            <p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                            <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                            <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                            <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                            <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                            <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                            <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                            <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Queda prohibido al Arrendatario:</p>
                            <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                            <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                            <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p align="center"> <strong>______________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p> <strong></strong></p>
                            <p align="center"> <strong>CODEUDOR SOLIDARIO</strong> <strong>/ AVAL</strong> <strong></strong></p><p> <strong></strong></p>
                            <p align="center"> <strong></strong></p>
                            <p> Por el presente acto comparece don(ña) '.strtoupper($aval_nombre).', '.$aval_nacionalidad.', '.$aval_estado_civil.', cédula de identidad N° '.$aval_documento.', con domicilio en '.$aval_direccion.', comuna de '.$aval_comuna.', '.$aval_ciudad.', en la Region '. (($aval_region == 'Metropolitana de Santiago')? $aval_region : 'de '.$aval_region) .' (en adelante, el “<u>Garante</u>”); quien expone lo que a continuación se indica:</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Codeuda solidaria.</strong> <strong></strong></p>
                            <p> Por el presente acto, el Garante declara que se constituye como codeudor solidario del Arrendatario respecto de todas las obligaciones emanadas del Contrato, aceptando desde ya, sin necesidad de notificación previa, las modificaciones que las Partes introduzcan al mismo, las que asume como indivisibles para todos los efectos legales.</p>
                            <p> <strong></strong></p><p> <strong><u>SEGUNDO</u></strong> <strong>: </strong> <strong>Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Garante por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada, para ser incorporada en sus registros y bases de datos e informadas a terceros. El Garante exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p align="center"> <strong>___________________________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                            ';

                            $contrato_pre.= '
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4 c9"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                            <br><br><p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <a id="t.98c346444373293274ebcd28c94e16de6c3da51e"></a>
                            <a id="t.1"></a>
                            
                            <p class="c13 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                            <br>
                            <p class="c6 c4"><span class="c5"></span></p>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; () &nbsp;/ </span><span class="c5">no ser </span><span class="c1">() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br><p class="c3"><span class="c5">Firma: </span><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.' , cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br><p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$aval_nombre.', cédula nacional de identidad/pasaporte No '.$aval_documento.' &nbsp;de nacionalidad '.$aval_nacionalidad.', declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div><p class="c4 c23"><span class="c1"></span></p>
                            </div>';
        
        //continuar remplazando las demas variables
        
        
                    
        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        
       


        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';


        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        if( $property->anexo_conditions && $property->anexo_conditions != '' ){
            $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
        }

        $contrato_pre .= "</body></html>";
        
        //echo($contrato);
        try {
            
            $html2pdf2 = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf2->pdf->SetDisplayMode('fullpage');
            $html2pdf2->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            ob_start();
           
            $html2pdf2->writeHTML($contrato_pre);
            
            Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf', $html2pdf2->output('cualquiercosa','S'));
            /*
            $this->path_file = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '.pdf';
            $this->path_file_pre = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '_pre.pdf';
            */
            
            $this->path_file_pre = 'contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf';
            //$this->path_file = 'C:\Users\Public\Documents\contrato.pdf';

            
        } catch (Html2PdfException $e) {
            $html2pdf2->clean();
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
    public function toPdfWithoutAval($owner, $tenant, $property){
        
        $arrendador_nombre = $owner->firstname.' '.$owner->lastname;
        $arrendador_nacionalidad = $owner->country()->first()->nationality;
        $arrendador_estado_civil = $owner->civilStatus()->first()->name;
        $arrendador_documento = $owner->document_number;
        $arrendador_domicilio = $owner->address.', '.$owner->address_details;
        $arrendador_domicilio = substr($arrendador_domicilio, 0, strpos($arrendador_domicilio, ',')).', '.$owner->address_details;
        $arrendador_comuna = $owner->city()->first()->name;
        $arrendador_region = $owner->city()->first()->region()->first()->name;
        $arrendador_email = $owner->email;    
        //$arrendador_email = $owner->email;
        $arrendatario_nombre = $tenant->firstname.' '.$tenant->lastname;
        $arrendatario_nacionalidad = $tenant->country()->first()->nationality;	
        $arrendatario_estado_civil = $tenant->civilStatus()->first()->name;
        $arrendatario_documento = $tenant->document_number;
        $arrendatario_domicilio = $tenant->address.', '.$tenant->address_details;
        $arrendatario_domicilio = substr($arrendatario_domicilio, 0, strpos($arrendatario_domicilio, ',')).', '.$tenant->address_details;
        $arrendatario_comuna = $tenant->city()->first()->name;
        $arrendatario_region = $tenant->city()->first()->region()->first()->name;
        $arrendatario_email = $tenant->email;
        //$arrendatario_email = $tenant->email;
        $propiedad_ciudad = $property->city()->first()->name;
        $propiedad_estacionamiento_numero = $property->private_parking;
        $propiedad_bodega_numero = $property->cellar;
        $propiedad_comuna = $property->city()->first()->name;
        $propiedad_region = $property->city()->first()->region()->first()->name;
        $propiedad_direccion = $property->address.', '.$property->address_details;
        $propiedad_direccion = substr($propiedad_direccion, 0, strpos($propiedad_direccion, ',')).', '.$property->address_details;
        $meses_arriendo = $property->tenanting_months_quantity;

        // Aqui vamos a poner la fecha actual como la fecha inicial de contrato y la (fecha actual + 1 año) como fecha fin
        Carbon::setLocale('es');
        $fecha_carbon = Carbon::now('America/Santiago');
        setlocale(LC_TIME, 'Spanish');
        
        $fecha = $fecha_carbon->format('d/m/Y');
        $inicio_dia = $fecha_carbon->format('d');
        $inicio_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $inicio_anio = $fecha_carbon->format('Y');

        $fecha_carbon->addMonths($meses_arriendo);

        $fin_dia = $fecha_carbon->format('d'); 
        $fin_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $fin_anio = $fecha_carbon->format('Y');

        $monto_arriendo = $property->rent;
        $monto_arriendo_letras = substr(NumeroALetras::convertir($property->rent),0,-10);

        $tipo_cuenta = $owner->account_type;
        $cuenta = $owner->account_number;
        $banco = $owner->bank()->first()->name;

        $garantia_monto = $property->warranty_months_quantity*$property->rent;

        $inventario_linea[] = "Lampara";
        $inventario_linea[] = "Cocina";

        $ciudad = 'Santiago';

        $contrato_pre = '<html>
                            <head>
                                <meta charset="UTF-8">
                            </head><body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                            <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>A</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                            <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                            <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                            <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                            <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                            <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                            <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                            <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                            <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                            <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p>
                            <p> <strong></strong></p>
                            <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                            <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                            <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                            <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                            <p> <strong></strong></p>
                            <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                            <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                            <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                            <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                            <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                            <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                            <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                            <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                            <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                            <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                            <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                            <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                            <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                            <p> <strong><u></u></strong></p>
                            <p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                            <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                            <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                            <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                            <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                            <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                            <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                            <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Queda prohibido al Arrendatario:</p>
                            <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                            <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                            <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            
                            
                            ';


        $contrato = '<html>
                        <head>
                            <meta charset="UTF-8">
                        </head>
                        <body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                        [def:$pep_arrendador|check|req1|signer0][def:$pep_arrendatario|check|req1|signer1]
                        <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                        <p align="center"> <strong></strong></p><p align="center"> <strong>A</strong></p>
                        <p align="center"> <strong></strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                        <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                        <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                        <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                        <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                        <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                        <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                        <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                        <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                        <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                        <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                        <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p><p> <strong></strong></p>
                        <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                        <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                        <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                        <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                        <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                        <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                        <p> <strong></strong></p>
                        <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                        <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                        <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                        <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                        <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                        <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                        <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                        <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                        <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                        <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                        <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                        <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                        <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                        <p> <strong><u></u></strong></p><p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                        <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                        <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                        <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                        <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                        <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                        <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                        <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                        <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                        <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                        <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                        <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p><p> <strong></strong></p>
                        <p> Queda prohibido al Arrendatario:</p>
                        <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                        <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                        <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                        <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                        <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                        
                        
                        ';

        
        
        $contrato_pre.= '
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4 c9"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                            <br><br><p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <a id="t.98c346444373293274ebcd28c94e16de6c3da51e"></a>
                            <a id="t.1"></a>
                            
                            <p class="c13 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                            <br>
                            <p class="c6 c4"><span class="c5"></span></p>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; () &nbsp;/ </span><span class="c5">no ser </span><span class="c1">() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br><p class="c3"><span class="c5">Firma: </span><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.' , cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            ';
        
        //continuar remplazando las demas variables
        
        $contrato .= '
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4 c9"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                        <br><br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p><p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p><p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <a id="t.98c346444373293274ebcd28c94e16de6c3da51e"></a>
                        <a id="t.1"></a>
                        
                        <p class="c13 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <div style="page-break-after: always;">&nbsp;</div>
                        <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                        <br>
                        <p class="c6 c4"><span class="c5"></span></p>
                        <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; ([$pep_arrendador]) &nbsp;/ </span><span class="c5">no ser </span><span class="c1">([$pep_arrendador]) cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                        <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                        <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                        <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                        <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                        <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                        <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                        <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                        <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                        <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                        <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                        <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                        <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                        <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                        <p class="c3"><span class="c1">No 18.045.</span></p>
                        <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                        <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <br><br><br>
                        <p class="c3"><span class="c5">Firma: </span><span class="c1">[sig|req|signer0]</span></p>
                        <div style="page-break-after: always;">&nbsp;</div>
                        <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.', cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; ([$pep_arrendatario]) &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;([$pep_arrendatario]) cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                        <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                        <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                        <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                        <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                        <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                        <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                        <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                        <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                        <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                        <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                        <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                        <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                        <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                        <p class="c3"><span class="c1">No 18.045.</span></p>
                        <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                        <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <br><br><br>
                        <p class="c3"><span class="c7">Firma: </span><span class="c1">[sig|req|signer1]</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        ';

                    
        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer0]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>';
        


        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';


        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        

        /*
        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer2]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>';
        */
        /**
         * Obteniendo ruta publica carnet frontal Arrendatario
         */
        
        $query = $tenant->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendatario no ha subido carnet frontal';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_frontal_tenant = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * Obteniendo ruta publica carnet trasero Arrendatario
         */
        $query = $tenant->files()->where('name','id_back')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendatario no ha subido carnet trasero';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_trasero_tenant = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * Obteniendo ruta publica carnet frontal Arrendador
         */
        $query = $owner->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendador no ha subido carnet frontal';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_frontal_owner =  env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * OBteniendo ruta publica carnet trasero Arrendador
         */
        $query = $owner->files()->where('name','id_back')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendador no ha subido carnet trasero';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_trasero_owner = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;

        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>Identificacion de Arrendatario</strong></span></p><br><img style="height:25%" src="'.env('APP_URL_NGROK',url('')).'/'.$tenant->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first()->thumbnail.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_frontal_tenant.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_trasero_tenant.'">';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>Identificacion de Arrendador</strong></span></p><br><img style="height:25%" src="'.env('APP_URL_NGROK',url('')).'/'.$owner->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first()->thumbnail.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_frontal_owner.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_trasero_owner.'">';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer0]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>';
        
        if( $property->anexo_conditions && $property->anexo_conditions != '' ){
            $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
            $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
            $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        }

        
        $contrato_pre .= "</body></html>";
        $contrato .= "</body></html>";
        //echo($contrato);
        try{
        
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            ob_start();
            $content = $contrato;
            
            
                $html2pdf->writeHTML($contrato);
        
            //dd('paso esta wea');
            
            Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '.pdf', $html2pdf->output('cualquiercosa','S'));
            
            $html2pdf2 = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf2->pdf->SetDisplayMode('fullpage');
            $html2pdf2->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            ob_start();
            $content = $contrato_pre;
            $html2pdf2->writeHTML($contrato_pre);
            
            Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf', $html2pdf2->output('cualquiercosa','S'));
        }catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            dd( $formatter->getHtmlMessage() );
        }  
            /*
            $this->path_file = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '.pdf';
            $this->path_file_pre = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '_pre.pdf';
            */
            $this->path_file = 'contrato_' . ($this->id? $this->id :'TEST') . '.pdf';
            $this->path_file_pre = 'contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf';
            //$this->path_file = 'C:\Users\Public\Documents\contrato.pdf';

            // FILE SAVE ---> OBTENER RUTA Y GURDARLA EN LA INSTANCIA CONTRATO
            
            $client = new Client( env('HELLOSIGN_API_KEY') );
            
            $account = new Account;
            $account->setCallbackUrl(env('APP_URL_NGROK',url('') ).'/hellosign_endpoint');
            $responseAccount = $client->updateAccount($account);
            
            $request = new SignatureRequest;
            $request->enableTestMode();
            $request->setTitle("uHomie - Contrato entre ".$tenant->firstname.' '.$tenant->lastname.' y '.$owner->firstname.' '.$owner->lastname);
            $request->setSubject("A continuación debe firmar el contrato de arriendo para la propiedad: ".$property->name);
            $request->setMessage("Usted tiene 48 horas para aceptar y firmar este contrato.");
            $request->setSigningRedirectUrl(env('APP_URL_NGROK',url('') ));
            //en pin va variable enviada por autentificiación via twillio sms / email
            $request->addSigner(new Signer(array(
                'name' => $owner->firstname.' '.$owner->lastname,
                'email_address' => $owner->email,
            )));
            $request->addSigner(new Signer(array(
                'name' => $tenant->firstname.' '.$tenant->lastname,
                'email_address' => $tenant->email,
            )));
            
            
            
            //$request->addFile('C:\Users\Public\Documents\contrato.pdf','F');
            //dd(Storage::disk('contratos')->url('contrato_' . $this->id . '.pdf'));
            $request->addFile(Storage::disk('contratos')->path('contrato_' . ($this->id? $this->id :'TEST') . '.pdf'));
            //return true;
            //dd('ok');
            $request->setUseTextTags(true);
            $request->setHideTextTags(true);
            //dd('ok');
            $response = $client->sendSignatureRequest($request);
            //sleep(10);
            //$client->getFiles($response->signature_request_id, 'contrato2.pdf', SignatureRequest::FILE_TYPE_PDF);
            //dd($response);
            //dd('holas');
            $this->signature_request_id_hs = $response->signature_request_id;
            $this->files_url_hs = $response->files_url;
            $this->signing_url_hs = $response->signing_url;
            $this->details_url_hs = $response->details_url;
            $this->allow_reassign_hs = $response->allow_reassign;
            $this->title_hs = $response->title;
            $this->subject_hs = $response->subject;
            $this->message_hs = $response->message;
            $this->allow_decline = $response->allow_decline;
            $this->save();
            return $response;
            //return dd($client->getSignatureRequest($response->signature_request_id));
        
    }
    public function toPdfWithAvalBeta($owner, $tenant, $collateral, $property){
        
        $arrendador_nombre = $owner->firstname.' '.$owner->lastname;
        $arrendador_nacionalidad = $owner->country()->first()->nationality;
        $arrendador_estado_civil = $owner->civilStatus()->first()->name;
        $arrendador_documento = $owner->document_number;
        $arrendador_domicilio = $owner->address.', '.$owner->address_details;
        $arrendador_domicilio = substr($arrendador_domicilio, 0, strpos($arrendador_domicilio, ',')).', '.$owner->address_details;
        $arrendador_comuna = $owner->city()->first()->name;
        $arrendador_region = $owner->city()->first()->region()->first()->name;
        $arrendador_email = $owner->email;    
        //$arrendador_email = $owner->email;
        $arrendatario_nombre = $tenant->firstname.' '.$tenant->lastname;
        $arrendatario_nacionalidad = $tenant->country()->first()->nationality;	
        $arrendatario_estado_civil = $tenant->civilStatus()->first()->name;
        $arrendatario_documento = $tenant->document_number;
        $arrendatario_domicilio = $tenant->address.', '.$tenant->address_details;
        $arrendatario_domicilio = substr($arrendatario_domicilio, 0, strpos($arrendatario_domicilio, ',')).', '.$tenant->address_details;
        $arrendatario_comuna = $tenant->city()->first()->name;
        $arrendatario_region = $tenant->city()->first()->region()->first()->name;
        $arrendatario_email = $tenant->email;
        //$arrendatario_email = $tenant->email;
        $aval_nombre = $collateral->firstname.' '.$collateral->lastname;
        $aval_nacionalidad = $collateral->country()->first()->nationality;	
        $aval_estado_civil = $collateral->civilStatus()->first()->name;
        $aval_documento = $collateral->document_number;
        $aval_direccion = $collateral->address.', '.$collateral->address_details;
        $aval_direccion = substr($aval_direccion, 0, strpos($aval_direccion, ',')).', '.$collateral->address_details;
        $aval_comuna = $collateral->city()->first()->name;
        $aval_ciudad = $collateral->city()->first()->name; 
        $aval_region = $collateral->city()->first()->region()->first()->name;
        $aval_email = $collateral->email;
        //$aval_email = $collateral->email;
        $propiedad_ciudad = $property->city()->first()->name;
        $propiedad_estacionamiento_numero = $property->private_parking;
        $propiedad_bodega_numero = $property->cellar;
        $propiedad_comuna = $property->city()->first()->name;
        $propiedad_region = $property->city()->first()->region()->first()->name;
        $propiedad_direccion = $property->address.', '.$property->address_details;

        $propiedad_direccion = substr($propiedad_direccion, 0, strpos($propiedad_direccion, ',')).', '.$property->address_details;
        $meses_arriendo = $property->tenanting_months_quantity;

        // Aqui vamos a poner la fecha actual como la fecha inicial de contrato y la (fecha actual + 1 año) como fecha fin
        Carbon::setLocale('es');
        $fecha_carbon = Carbon::now('America/Santiago');
        setlocale(LC_TIME, 'Spanish');
        
        $fecha = $fecha_carbon->format('d/m/Y');
        $inicio_dia = $fecha_carbon->format('d');
        $inicio_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $inicio_anio = $fecha_carbon->format('Y');

        $fecha_carbon->addMonths($meses_arriendo);

        $fin_dia = $fecha_carbon->format('d'); 
        $fin_mes = ucfirst($fecha_carbon->formatLocalized('%B'));
        $fin_anio = $fecha_carbon->format('Y');

        $monto_arriendo = $property->rent;
        $monto_arriendo_letras = substr(NumeroALetras::convertir($property->rent),0,-10);

        $tipo_cuenta = $owner->account_type;
        $cuenta = $owner->account_number;
        $banco = $owner->bank()->first()->name;

        $garantia_monto = $property->warranty_months_quantity*$property->rent;

        $inventario_linea[] = "Lampara";
        $inventario_linea[] = "Cocina";

        $ciudad = 'Santiago';

        
        $contrato_pre = '<html>
                            <head>
                                <meta charset="UTF-8">
                            </head><body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                            <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>A</strong></p>
                            <p align="center"> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                            <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                            <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                            <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                            <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                            <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                            <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                            <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                            <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                            <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p>
                            <p> <strong></strong></p>
                            <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                            <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                            <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                            <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                            <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                            <p> <strong></strong></p>
                            <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                            <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                            <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                            <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                            <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                            <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                            <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                            <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                            <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                            <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                            <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                            <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                            <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                            <p> <strong><u></u></strong></p>
                            <p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                            <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                            <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                            <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                            <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                            <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                            <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                            <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                            <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Queda prohibido al Arrendatario:</p>
                            <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                            <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                            <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                            <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                            <p align="center"> <strong>______________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p> <strong></strong></p>
                            <p align="center"> <strong>CODEUDOR SOLIDARIO</strong> <strong>/ AVAL</strong> <strong></strong></p><p> <strong></strong></p>
                            <p align="center"> <strong></strong></p>
                            <p> Por el presente acto comparece don(ña) '.strtoupper($aval_nombre).', '.$aval_nacionalidad.', '.$aval_estado_civil.', cédula de identidad N° '.$aval_documento.', con domicilio en '.$aval_direccion.', comuna de '.$aval_comuna.', '.$aval_ciudad.', en la Region '. (($aval_region == 'Metropolitana de Santiago')? $aval_region : 'de '.$aval_region) .' (en adelante, el “<u>Garante</u>”); quien expone lo que a continuación se indica:</p>
                            <p> <strong><u>PRIMERO</u></strong> <strong>: Codeuda solidaria.</strong> <strong></strong></p>
                            <p> Por el presente acto, el Garante declara que se constituye como codeudor solidario del Arrendatario respecto de todas las obligaciones emanadas del Contrato, aceptando desde ya, sin necesidad de notificación previa, las modificaciones que las Partes introduzcan al mismo, las que asume como indivisibles para todos los efectos legales.</p>
                            <p> <strong></strong></p><p> <strong><u>SEGUNDO</u></strong> <strong>: </strong> <strong>Información deudas morosas.</strong> <strong></strong></p>
                            <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Garante por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada, para ser incorporada en sus registros y bases de datos e informadas a terceros. El Garante exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                            <p align="center"> <strong>___________________________________________</strong> <strong></strong></p>
                            <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                            ';


        $contrato = '<html>
                        <head>
                            <meta charset="UTF-8">
                        </head>
                        <body lang="ES" link="#000000" vlink="#954F72" style="tab-interval:35.4pt">
                        [def:$pep_arrendador|check|req1|signer0][def:$pep_arrendatario|check|req1|signer1][def:$pep_aval|check|req1|signer2]
                        <p align="center"> <strong>CONTRATO DE ARRIENDO</strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                        <p align="center"> <strong></strong></p><p align="center"> <strong>A</strong></p>
                        <p align="center"> <strong></strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                        <p> En <strong>'.strtoupper($ciudad).', '.$fecha.',</strong> entre '.strtoupper($arrendador_nombre).', cédula nacional de identidad número '.$arrendador_documento.'; por una parte, en adelante denominada indistintamente como el <strong>“Arrendador”</strong>, con domicilio en '.$arrendador_domicilio.', en la comuna de '.$arrendador_comuna.', en la Región '.(($arrendador_region == 'Metropolitana de Santiago')? $arrendador_region : 'de '.$arrendador_region).' y por la otra, '.strtoupper($arrendatario_nombre).', cédula nacional de identidad número '.$arrendador_documento.', en adelante denominada indistintamente como el <strong>“Arrendatario”</strong>, con domicilio en '.$arrendatario_domicilio.', en la comuna de '.$arrendador_comuna.', en la Region '.(($arrendatario_region == 'Metropolitana de Santiago')? $arrendatario_region : 'de '.$arrendatario_region).', vienen en celebrar el presente contrato de arrendamiento, en adelante el <strong>“Contrato”</strong>.</p>
                        <p> <strong><u>PRIMERO</u></strong> <strong>: Antecedentes Inmueble.</strong> El Arrendador es dueño o está legalmente facultado para ser representante de la propiedad ubicada en '.$propiedad_direccion.'. Ubicada en la ciudad de '.$propiedad_ciudad.', en la comuna de '.$propiedad_comuna.', en la Región '.(($propiedad_region == 'Metropolitana de Santiago')? $propiedad_region : 'de '.$propiedad_region).', en adelante, el <strong>“Inmueble”</strong>.</p>
                        <p> <strong><u>SEGUNDO</u></strong> <strong>: Arriendo.</strong> Por el presente instrumento, el Arrendador, da en arrendamiento al Arrendatario, el Inmueble individualizado en la cláusula primera precedente.</p>
                        <p> <strong><u>TERCERO</u></strong> <strong>: Destino del Inmueble y Declaración.</strong> El Arrendatario declara que el Inmueble arrendado deberá ser destinado y usado exclusivamente en forma habitacional. El hecho de destinarse la referida propiedad a una finalidad diferente a la pactada, facultará al Arrendador para poner término ipso facto al presente Contrato.</p>
                        <p> <strong><u>CUARTO</u></strong> <strong>: Plazo.</strong> <strong>Uno) </strong> El plazo de duración del presente contrato de arriendo será de '.$meses_arriendo.' meses a contar de la fecha del presente instrumento. El Contrato se renovará en forma tácita, sucesiva y automática, por períodos de 1 año cada uno, en las mismas condiciones pactadas en el presente instrumento, salvo que cualquiera de las partes comunique a la otra mediante el envío de correo electrónico a las direcciones singularizadas en la cláusula Vigésimo Tercero, su deseo de poner término a éste contrato, notificación que deberá ser enviada con 60 días de anticipación a lo menos, respecto del plazo final de éste o de sus eventuales prórrogas. Esta notificación también podrá ser efectuada de forma online a través de la plataforma UHOMIE.</p>
                        <p> <strong><u>QUINTO</u></strong> <strong>: Renta del contrato de arrendamiento.</strong></p>
                        <p> <strong>Uno. Renta de arrendamiento</strong> : La renta mensual de arrendamiento es la suma equivalente a '.$monto_arriendo_letras.' en pesos chilenos.</p>
                        <p> <strong>Dos.</strong> El Arrendatario deberá pagar la renta señalada, mensualmente por mes adelantado, dentro de los cinco primeros días de cada mes, según este término se define más adelante, mediante un depósito o transferencia bancaria a nombre del ARRENDADOR en la siguiente cuenta '.$cuenta.', '.$tipo_cuenta.', '.$banco.'.</p>
                        <p> <strong>Tres.</strong> Si el Arrendatario se retrasare en el pago de la renta, se devengará a favor del Arrendador una multa moratoria indicada en el <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #1. </strong></p>
                        <p> <strong></strong></p><p> Sin perjuicio de lo anterior, en el evento que el Arrendatario se retrase en el pago de dos o más rentas de arrendamiento, sean o no consecutivas en un año calendario, el Arrendador podrá poner término ipso facto al presente contrato.</p>
                        <p> <strong><u>SEXTO</u></strong> <strong>: Termino anticipado.</strong> <strong></strong></p><p> <strong></strong></p>
                        <p> El Arrendador tendrá la facultad de disponer el término anticipado al presente Contrato, sin necesidad de declaración judicial o indemnización alguna, por las siguientes causales:</p>
                        <p> · Si el Arrendatario no paga la renta mensual o los servicios dentro de los plazos definidos.</p>
                        <p> · Si el Arrendatario o cualquier persona a su cargo causare deterioros a la Propiedad o a sus instalaciones, sea directa o indirectamente.</p>
                        <p> · Si el Arrendatario o cualquier persona a su cargo hiciere variaciones o modificaciones en la Propiedad sin previa autorización escrita del Arrendador.</p>
                        <p> · Si se cambia el destino de la Propiedad estipulado en la cláusula Tercero del Contrato.</p>
                        <p> · Si el Arrendatario incumpliere las obligaciones o prohibiciones establecidas en el presente Contrato o cualquiera de las normas y prohibiciones contenidas en el respectivo reglamento de copropiedad de la Propiedad.</p>
                        <p> <strong></strong></p>
                        <p> Asimismo, el Arrendatario podrá dar término anticipado al presente Contrato, en los mismos términos señalados precedentemente, si el Arrendador incumpliere grave o reiteradamente sus obligaciones estipuladas en el Contrato.</p>
                        <p> <strong><u>SEPTIMO</u></strong> <strong>:</strong> <strong> Entrega Material. </strong> La entrega del Inmueble arrendado se efectuará en un plazo de 5 días hábiles contados desde este fecha, totalmente desocupado, con todos sus consumos básicos y domiciliarios, y sus contribuciones, al día, libre de ocupantes, trabajadores o empleados, de litigios, embargos e interdicciones y de toda otra prohibición o limitación legal o voluntaria que impida, limite o entrabe el libre ejercicio del derecho de uso que por este acto el Arrendador garantiza al Arrendatario. En caso de que la propiedad vencido el plazo máximo de entrega desde la celebración del presente contrato no haya sido entregada al arrendatario en las condiciones pactadas facultará al arrendatario a terminar de forma anticipada la celebración del presente contrato, exigiendo la devolución completa de los meses de adelanto y garantía conforme a las condiciones y los gastos que se incurran producto de esta terminación anticipada serán responsabilidad del arrendador.</p>
                        <p> <strong><u>OCTAVO</u></strong> <strong>: </strong> <strong>Subarrendamiento, cesión y traspaso.</strong> Las partes convienen expresamente que el Arrendatario no podrá subarrendar el inmueble arrendado y/o ceder el presente Contrato, a otra persona natural o jurídica.</p>
                        <p> <strong><u>NOVENO</u></strong> <strong>: Pagos gastos comunes y servicios básicos.</strong></p>
                        <p> <strong>Uno</strong> . En caso de que la propiedad arrendada esté sujeta al pago de gastos comunes. El Arrendatario estará obligado a pagar puntualmente y a quien corresponda, los consumos de luz, agua potable, gas, gastos comunes, consumos de servicios básicos directos y propios del Inmueble y demás consumos, pudiendo el Arrendador en cualquier oportunidad, exigir la presentación de los recibos que acrediten la cancelación de dichos pagos.</p>
                        <p> <strong>Dos.</strong> El atraso de un mes en el pago de cualquiera de los suministros y/o gastos comunes antes indicados dará derecho al Arrendador o la administración del Edificio, según corresponda, a suspender los servicios respectivos, debiendo el Arrendatario cancelarlos reajustados y con las multas que las entidades acreedoras contemplen para estos casos. No obstante lo anterior, el Arrendatario será responsable y se hará cargo de la mantención, aseo y ornato del Inmueble.</p>
                        <p> <strong><u>DÉCIMO</u></strong> <strong>: Mejoras</strong> . El Arrendatario no podrá efectuar mejoras, transformaciones o modificaciones en el Inmueble arrendado, sin previa autorización por escrito del Arrendador, y todas las mejoras, autorizadas o no, que hiciere en la propiedad, serán de su exclusivo costo y sin que el Arrendador deba reembolsarle suma alguna de dinero por ellas. Las mejoras realizadas quedarán en beneficio de la propiedad desde el momento mismo en que sean ejecutadas. No obstante lo anterior, el Arrendatario podrá retirar las mejoras efectuadas siempre y cuando no dañe con ello la estructura y apariencia y no cause detrimento al Inmueble.</p>
                        <p> <strong><u>DÉCIMO PRIMERO</u></strong> <strong>: Mantención</strong> . Será obligación del Arrendatario mantener la propiedad arrendada en buen estado de conservación, realizando todas las reparaciones que se requieran para su mantención. Del mismo modo, el Arrendatario se obliga a conservar el Inmueble arrendado en perfecto estado de aseo y conservación y, en general, a efectuar oportunamente y a su costo todas las reparaciones locativas para la conservación y buen funcionamiento del mismo. En caso de producirse en la propiedad arrendada un desperfecto cuya reparación sea de cargo del Arrendador, el Arrendatario dará aviso inmediato y por escrito al Arrendador o a su representante, a objeto de que proceda a la brevedad a su reparación, de acuerdo a las condiciones indicadas en el <strong> </strong> <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #2. </strong></p>
                        <p> <strong><u>DÉCIMO SEGUNDO</u></strong> <strong>: Restitución de la Propiedad.</strong> El Arrendatario se obliga a restituir la Propiedad inmediatamente al término de este Contrato, entrega que deberá hacerse mediante la desocupación total de la misma, poniéndola a disposición del Arrendador y entregándole las llaves. La entrega deberá efectuarse en el mismo estado que el Arrendatario recibió la Propiedad, habida consideración del transcurso del tiempo y de su uso legítimo.</p>
                        <p> Además, el Arrendatario deberá exhibir los recibos que acrediten el pago hasta el último día que ocupó la Propiedad de los gastos comunes, como así también, de los consumos de energía eléctrica, agua, teléfono, internet, tv cable y otros similares no incluidos en la enunciación precedente.</p>
                        <p> <strong><u>DÉCIMO TERCERO</u></strong> <strong>: Garantía.</strong> <strong></strong></p>
                        <p> A fin de garantizar la conservación de la Propiedad y su restitución en el mismo estado en que la recibe, la mantención y conservación de las especies y artefactos que se indican en el inventario <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICIÓN #3. </strong> , el pago de los perjuicios y deterioros que cause el Arrendatario y/o sus dependientes a la Propiedad, sus servicios e instalaciones y, en general, para responder al fiel cumplimiento de las estipulaciones de este Contrato, el Arrendatario entrega en este acto en garantía a UHOMIE.COM SPA la suma de $ '.number_format(($property->rent*$property->warranty_months_quantity), 0, ',', '.').'.</p>
                        <p> Esta garantía UHOMIE.COM SPA la resguardará hasta el vencimiento del presente contrato y se devolverá al Arrendatario dentro de los 15 días siguientes a la fecha en que se le haya devuelto al Arrendador la Propiedad cumpliéndose los siguientes requisitos indicados en <strong> ANEXO CONDICIONES DE ARRIENDO Y CLAUSULAS ESPECIALES CONDICION # 4 </strong></p>
                        <p> <strong><u></u></strong></p><p> <strong><u>DÉCIMO CUARTO</u></strong> <strong>: Visitas.</strong> <strong></strong></p>
                        <p> <strong></strong></p><p> El Arrendatario se obliga a dar las facilidades necesarias para que el Arrendador o quien lo represente, pueda visitar la Propiedad. La limitación antedicha al derecho de visitas del Arrendador, no será aplicable cuando el Arrendador hubiere tomado conocimiento de daños en la Propiedad o de que se están realizando mejoras en ésta, que no hayan sido autorizadas por él, caso en el cual el Arrendador podrá efectuar las visitas a la Propiedad en la oportunidad que estime conveniente.</p>
                        <p> Asimismo, en caso que el Arrendador desee vender la Propiedad o arrendarla a otra persona, el Arrendatario se obliga a permitir la visita de los potenciales compradores o arrendatarios fechas y horario a convenir.</p>
                        <p> <strong><u>DÉCIMO QUINTO </u></strong> <strong>Gastos.</strong></p>
                        <p> Los gastos e impuestos, establecidos por el uso de la plataforma UHOMIE que deriven del presente Contrato serán de cargo del Arrendatario y Arrendador según las condiciones y políticas de uso de UHOMIE.</p>
                        <p> <strong><u>DECIMO SEXTO</u></strong> <strong>: Información deudas morosas.</strong> <strong></strong></p>
                        <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Arrendatario por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada y/o Boletín Comercial , para ser incorporada en sus registros y bases de datos e informadas a terceros. El Arrendatario exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                        <p> <strong></strong></p><p> <strong><u>DECIMO SEPTIMO </u></strong> <strong>: Otorgamiento. </strong> <strong></strong></p>
                        <p> <strong></strong></p><p> El presente Contrato se firma en tres ejemplares, quedando uno en poder de cada parte.</p>
                        <p> <strong><u>DECIMO OCTAVO</u></strong> <strong>: Domicilio. </strong> <strong></strong></p><p> <strong></strong></p>
                        <p> Para todos los efectos legales, las Partes fijan su domicilio en la Ciudad de Santiago Región Metropolitana y se someten a la competencia de sus Tribunales Ordinarios de Justicia.</p>
                        <p> <strong><u>DECIMO NOVENO</u></strong> <strong>: Prohibiciones.</strong> <strong></strong></p><p> <strong></strong></p>
                        <p> Queda prohibido al Arrendatario:</p>
                        <p> · Subarrendar o ceder a cualquier título el presente Contrato, sin previa autorización expresa del Arrendador.</p>
                        <p> · Crear divisiones o subdivisiones de la Propiedad.</p>
                        <p> · Introducir cualquier tipo de materiales explosivos, inflamables o de mal olor en la Propiedad.</p>
                        <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>
                        <p align="center"> <strong>___________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>
                        <p align="center"> <strong>______________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                        <div style="page-break-after: always;">&nbsp;</div><p> <strong></strong></p>
                        <p align="center"> <strong>CODEUDOR SOLIDARIO</strong> <strong>/ AVAL</strong> <strong></strong></p>
                        <p> <strong></strong></p><p align="center"> <strong></strong></p>
                        <p> Por el presente acto comparece don(ña) '.strtoupper($aval_nombre).', '.$aval_nacionalidad.', '.$aval_estado_civil.', cédula de identidad N° '.$aval_documento.', con domicilio en '.$aval_direccion.', comuna de '.$aval_comuna.', '.$aval_ciudad.', en la Region '. (($aval_region == 'Metropolitana de Santiago')? $aval_region : 'de '.$aval_region) .' (en adelante, el “<u>Garante</u>”); quien expone lo que a continuación se indica:</p>
                        <p> <strong><u>PRIMERO</u></strong> <strong>: Codeuda solidaria.</strong> <strong></strong></p>
                        <p> Por el presente acto, el Garante declara que se constituye como codeudor solidario del Arrendatario respecto de todas las obligaciones emanadas del Contrato, aceptando desde ya, sin necesidad de notificación previa, las modificaciones que las Partes introduzcan al mismo, las que asume como indivisibles para todos los efectos legales.</p>
                        <p> <strong></strong></p><p> <strong><u>SEGUNDO</u></strong> <strong>: </strong> <strong>Información deudas morosas.</strong> <strong></strong></p>
                        <p> <strong></strong></p><p> Con objeto de dar cumplimiento a lo dispuesto en la Ley 19.628 sobre Protección de Datos de Carácter Personal, el Garante por el presente acto, otorga irrevocablemente su consentimiento libre e informado, para que el Arrendador o su representante, pueda dar a conocer cualquier morosidad, en las rentas, gastos comunes y de cualquier otro cobro que fuere procedente en conformidad al Contrato, a Servicios Equifax Chile Limitada, para ser incorporada en sus registros y bases de datos e informadas a terceros. El Garante exime al Arrendador de cualquier responsabilidad que pudiere originarse a este respecto.</p>
                        <p align="center"> <strong>___________________________________________</strong> <strong></strong></p>
                        <p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>
                        ';

        
        
        $contrato_pre.= '
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4 c9"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                            <br><br><p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c9 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <a id="t.98c346444373293274ebcd28c94e16de6c3da51e"></a>
                            <a id="t.1"></a>
                            
                            <p class="c13 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                            <br>
                            <p class="c6 c4"><span class="c5"></span></p>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; () &nbsp;/ </span><span class="c5">no ser </span><span class="c1">() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br><p class="c3"><span class="c5">Firma: </span><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.' , cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div style="page-break-after: always;">&nbsp;</div>
                            <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                            <br>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                            <br><p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c2">Yo, '.$aval_nombre.', cédula nacional de identidad/pasaporte No '.$aval_documento.' &nbsp;de nacionalidad '.$aval_nacionalidad.', declaro </span><span class="c7">ser</span><span class="c2">&nbsp; () &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;() cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                            <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                            <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                            <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                            <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                            <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                            <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                            <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                            <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                            <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                            <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                            <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                            <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                            <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                            <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                            <p class="c3"><span class="c1">No 18.045.</span></p>
                            <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                            <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                            <p class="c3 c4"><span class="c1"></span></p><p class="c3 c4"><span class="c1"></span></p>
                            <br><br><br>
                            <p class="c3"><span class="c7">Firma: </span><span class="c1"></span></p>
                            <p class="c3 c4"><span class="c1"></span></p>
                            <div><p class="c4 c23"><span class="c1"></span></p>
                            </div>';
        
        //continuar remplazando las demas variables
        
        $contrato .= '
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4 c9"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g) del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a la sociedad Uhomie SpA.</span></p>
                        <br><br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p><p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p><p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c9 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <a id="t.98c346444373293274ebcd28c94e16de6c3da51e"></a>
                        <a id="t.1"></a>
                        
                        <p class="c13 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <div style="page-break-after: always;">&nbsp;</div>
                        <p class="c6"><span class="c1">&nbsp; </span><span class="c5">Anexo N&deg;3</span></p>
                        <br>
                        <p class="c6 c4"><span class="c5"></span></p>
                        <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">Yo, </span><span class="c2">'.$arrendador_nombre.'</span><span class="c1">&nbsp;, cédula nacional de identidad/pasaporte No </span><span class="c2">'.$arrendador_documento.'</span><span class="c1">&nbsp; de nacionalidad </span><span class="c2">'.$arrendador_nacionalidad.'</span><span class="c1">&nbsp;declaro </span><span class="c5">ser</span><span class="c1">&nbsp; ([$pep_arrendador]) &nbsp;/ </span><span class="c5">no ser </span><span class="c1">([$pep_arrendador]) cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                        <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                        <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                        <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                        <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                        <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                        <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                        <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                        <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                        <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                        <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                        <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                        <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                        <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                        <p class="c3"><span class="c1">No 18.045.</span></p>
                        <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                        <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">En Santiago de Chile, el </span><span class="c2">'.$fecha.'</span><span class="c1">. </span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <br><br><br>
                        <p class="c3"><span class="c5">Firma: </span><span class="c1">[sig|req|signer0]</span></p>
                        <div style="page-break-after: always;">&nbsp;</div>
                        <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c2">Yo, '.$arrendatario_nombre.', cédula nacional de identidad/pasaporte No '.$arrendatario_documento.' &nbsp;de nacionalidad '.$arrendatario_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; ([$pep_arrendatario]) &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;([$pep_arrendatario]) cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                        <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                        <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                        <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                        <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                        <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                        <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                        <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                        <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                        <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                        <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                        <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                        <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                        <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                        <p class="c3"><span class="c1">No 18.045.</span></p>
                        <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                        <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <br><br><br>
                        <p class="c3"><span class="c7">Firma: </span><span class="c1">[sig|req|signer1]</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <div style="page-break-after: always;">&nbsp;</div>
                        <p class="c6"><span class="c5">DECLARACION DE PEP</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">DECLARACIÓN DE VÍNCULO CON PERSONAS EXPUESTAS POLÍTICAMENTE (PEP)</span></p>
                        <br>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c2">Yo, '.$aval_nombre.', cédula nacional de identidad/pasaporte No '.$aval_documento.' &nbsp;de nacionalidad '.$aval_nacionalidad.' declaro </span><span class="c7">ser</span><span class="c2">&nbsp; ([$pep_aval]) &nbsp;/ </span><span class="c7">no ser </span><span class="c1">&nbsp;([$pep_aval]) cónyuge o parientes hasta el segundo grado de consanguinidad (abuelo(a), padre, madre, hijo(a), hermano(a), nieto(a)), ni haber celebrado pacto de actuación conjunta mediante el cual tengan poder de voto suficiente para influir en sociedades constituidas en Chile, con ninguna de las Personas Políticamente Expuestas que a continuación se indican, sea que actualmente desempeñen o hayan desempeñado uno o más de los siguientes cargos:</span></p>
                        <p class="c3"><span class="c1">1) Presidente de la República.</span></p>
                        <p class="c3"><span class="c1">2) Senadores, Diputados y Alcaldes.</span></p>
                        <p class="c3"><span class="c1">3) Ministros de la Corte Suprema y Cortes de Apelaciones.</span></p>
                        <p class="c3"><span class="c1">4) Ministros de Estado, Subsecretarios, Intendentes, Gobernadores, Secretarios Regionales</span></p>
                        <p class="c3"><span class="c1">Ministeriales, Embajadores, Jefes Superiores de Servicio, tanto centralizados como descentralizados y el directivo superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">5) Comandantes en Jefe de las Fuerzas Armadas, Director General Carabineros, Director General de Investigaciones, y el oficial superior inmediato que deba subrogar a cada uno de ellos.</span></p>
                        <p class="c3"><span class="c1">6) Fiscal Nacional del Ministerio Público y Fiscales Regionales.</span></p>
                        <p class="c3"><span class="c1">7) Contralor General de la República.</span></p>
                        <p class="c3"><span class="c1">8) Consejeros del Banco Central de Chile.</span></p>
                        <p class="c3"><span class="c1">9) Consejeros del Consejo de Defensa del Estado.</span></p>
                        <p class="c3"><span class="c1">10) Ministros del Tribunal Constitucional.</span></p>
                        <p class="c3"><span class="c1">11) Ministros del Tribunal de la Libre Competencia</span></p>
                        <p class="c3"><span class="c1">12) Integrantes titulares y suplentes del Tribunal de Contratación Pública</span></p>
                        <p class="c3"><span class="c1">13) Consejeros del Consejo de Alta Dirección Pública</span></p>
                        <p class="c3"><span class="c1">14) Los directores y ejecutivos principales de empresas públicas, según lo definido por la Ley</span></p>
                        <p class="c3"><span class="c1">No 18.045.</span></p>
                        <p class="c3"><span class="c1">15) Directores de sociedades anónimas nombrados por el Estado o sus organismos.</span></p>
                        <p class="c3"><span class="c1">16) Miembros de las directivas de los partidos políticos</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3"><span class="c1">En Santiago de Chile, el '.$fecha.'. </span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <br><br><br>
                        <p class="c3"><span class="c7">Firma: </span><span class="c1">[sig|req|signer2]</span></p>
                        <p class="c3 c4"><span class="c1"></span></p>
                        <div><p class="c4 c23"><span class="c1"></span></p>
                        </div>';

                    
        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.1 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer0]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>';
        


        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';


        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.2 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        


        $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';

        
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br><p class="c3"><span class="c1"><b>CONDICIÓN 1: Multa por No Pago a tiempo.</b></span></p><p class="c3"><span class="c1">El Pago de la renta que no se efectuare dentro del plazo estipulado o con atraso
        (mayor a 5 días de atraso) constituirá al arrendatario una mora por retardo de pleno
        derecho y deberá pagará una multa de 0.5 UF por día de retraso desde el vencimiento.
        Lo anterior sin perjuicio de la facultad del arrendador de dar término anticipado al
        contrato conforme lo señala el presente contrato.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 2: Arreglo por daños en la propiedad.</b></span></p><p class="c3"><span class="c1">De ser necesaria alguna reparación en la propiedad de cargo del arrendador, como
        filtraciones de cañerias, problemas de techumbres y en general de la estructura de la
        propiedad, el Arrendatario deberá dar aviso vía correo electrónico, carta o llamada
        telefónica al Arrendador para que el o en su representación proceda a las
        reparaciones.</span></p><p class="c3"><span class="c1">Si dentro de 10 Días corridos y continuos al aviso el Arrendador no ha solucionado el
        problema, el arrendatario queda facultado para efectuar las reparaciones y
        descontarlo del monto de arriendo, siempre y cuando se cuente con al menos dos (2)
        presupuestos.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 3: Inventario de la Propiedad.</b></span></p><p class="c3"><span class="c1">Para la propiedad indicad en el presente contrato. Las Partes declaran y dejan
        constancia que junto con la Propiedad se encuentran los bienes que a continuación se
        Indican</span></p><p class="c3"><span class="c1">'.($property->furnished_description? $property->furnished_description : '-Arrendador no registró bienes-').'</span></p><p class="c3"><span class="c1">El Arrendador declara estar en conocimiento de lo establecido en el artículo 8 letra g)
        del Decreto Ley 825, y por tanto reconoce su obligación de enterar el Impuesto al
        Valor Agregado, en caso de corresponderle. Asimismo, deja expresa constancia de
        que exime de toda responsabilidad del pago de este tributo al notario que autoriza y a
        la sociedad Uhomie.com SpA.</span></p><p class="c3"><span class="c1"><b>CONDICIÓN 4: Devolución de Garantía</b></span></p><p class="c3"><span class="c1"><b><u>La Garantía será devuelta al Arrendatario vía transferencia bancaria cuando:</u></b></span></p><p class="c3"><span class="c1"><ul><li>Todos los servicios como agua, luz, gas, Gastos comunes, Internet están
        completamente pagados.</li><br><li>La restitución del inmueble arrendado ha sido efectuada con entrega de llaves
        al arrendador. Se deberá notificar en la plataforma UHOMIE que la devolución
        se ha efectuado, indicando Fecha, y Hora.</li></ul></span></p><p class="c3"><span class="c1"><b><u>UHOMIE.COM SPA Podrá descontar de la Garantía al arrendatario
        Cuando:</u></b></span></p><p class="c3"><span class="c1"><ol><li>Costo de reparaciones de deterioros previamente entregado un informa
        completo por UHOMIE sobre los daños, el informe considerará al menos dos (2)
        cotizaciones para reparación de los daños con un tope máximo del valor de la
        Garantía entregada por el arrendatario a UHOMIE, se tomará siempre la
        cotización más económica de las entregadas al arrendatario. La
        responsabilidad de UHOMIE será hasta el monto máximo de la garantía
        entregada. El remanente que queda después de la reparaciones serán
        devueltas al arrendatario en un lapso no mayor de <b>5 días hábiles</b> después de
        efectuada las reparaciones. La devolución se hará mediante transferencia
        bancaria al arrendatario. UHOMIE se compromete siempre a tomar la cotización
        para reparación más económica y de calidad que garantice la reparación
        efectiva de los daños ocasionados.</li><br><li>UHOMIE queda desde ya facultado para descontar de la garantía el valor de
        los deterioros y perjuicios producidos por el uso indebido, negligencia o dolo
        del Arrendatario sobre la propiedad.</li><br><li>Esta garantía en ningún caso podrá ser imputada o compensada con una renta
        de arrendamiento morosa o futura, multas o con gastos que estuviere obligado
        a pagar el Arrendatario por el uso del Inmueble.</li><br><li>Se deja expresamente establecido que el Arrendador no le corresponderá bajo
        ninguna circunstancia, cancelar los gastos, consumos o cuentas impagas de
        gastos comunes de luz, agua teléfono, gas, u otros servicios similares,
        debiendo el Arrendatario hacerlo con anterioridad a la entrega de la propiedad
        arrendada y término del contrato de arrendamiento.</li></ol></span></p>';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer2]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>';
        
        /**
         * Obteniendo ruta publica carnet frontal Arrendatario
         */
        $query = $tenant->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrrendatario no ha subido carnet frontal';
        }
        //dd(storage_path("app/".$carnet->path));
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_frontal_tenant = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * Obteniendo ruta publica carnet trasero Arrendatario
         */
        $query = $tenant->files()->where('name','id_back')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendatario no ha subido carnet trasero';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_trasero_tenant = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * Obteniendo ruta publica carnet frontal Arrendador
         */
        $query = $owner->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendador no ha subido carnet frontal';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_frontal_owner =  env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * OBteniendo ruta publica carnet trasero Arrendador
         */
        $query = $owner->files()->where('name','id_back')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario arrendador no ha subido carnet trasero';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_trasero_owner = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * OBteniendo ruta publica carnet frontal Aval
         */
        $query = $collateral->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario aval no ha subido carnet frontal';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_frontal_collateral = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;
        /**
         * OBteniendo ruta publica carnet trasero Aval
         */
        $query = $collateral->files()->where('name','id_back')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario aval no ha subido carnet trasero';
        }
        $ruta_destino = 'images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , storage_path('app/public/'.$ruta_destino) );
        $ruta_publica_carnet_trasero_collateral = env('APP_URL_NGROK',url('')).'/storage/'.$ruta_destino;


        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>Identificacion de arrendatario</strong></span></p><br><img style="height:25%" src="'.env('APP_URL_NGROK',url('')).'/'.$tenant->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first()->thumbnail.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_frontal_tenant.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_trasero_tenant.'">';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>Identificacion de Arrendador</strong></span></p><br><img style="height:25%" src="'.env('APP_URL_NGROK',url('')).'/'.$owner->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first()->thumbnail.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_frontal_owner.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_trasero_owner.'">';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer0]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendador_nombre).'</strong></p>';
        $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>Identificacion de Aval</strong></span></p><br><img style="height:25%" src="'.env('APP_URL_NGROK',url('')).'/'.$collateral->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first()->thumbnail.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_frontal_collateral.'"><br><img style="height:25%" src="'.$ruta_publica_carnet_trasero_collateral.'">';
        $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer2]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($aval_nombre).'</strong></p>';
        
        if( $property->anexo_conditions && $property->anexo_conditions != '' ){
            $contrato_pre .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
            $contrato .= '<div style="page-break-after: always;">&nbsp;</div><p class="c3" align="center"><span class="c1"><strong>ANEXO # 1.3 CONDICIONES DE ARRIENDOS Y CLÁUSULAS ESPECIALES</strong></span></p><br>
            '.$property->anexo_conditions.'';
            $contrato .= '<p class="c3" align="center"><span class="c1">[sig|req1|signer1]</span></p><p align="center"> <strong>___________________________</strong></p><p align="center"> <strong>'.strtoupper($arrendatario_nombre).'</strong></p>';
        }

        $contrato_pre .= "</body></html>";
        $contrato .= "</body></html>";
        //echo($contrato);
        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            ob_start();
            
            $html2pdf->writeHTML($contrato);
            
            Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '.pdf', $html2pdf->output('cualquiercosa','S'));
            
            $html2pdf2 = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf2->pdf->SetDisplayMode('fullpage');
            $html2pdf2->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            ob_start();
           
            $html2pdf2->writeHTML($contrato_pre);
            
            Storage::disk('contratos')->put('contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf', $html2pdf2->output('cualquiercosa','S'));
            /*
            $this->path_file = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '.pdf';
            $this->path_file_pre = $_SERVER['DOCUMENT_ROOT'].'../storage/contratos/contrato_' . ($this->id? :'TEST') . '_pre.pdf';
            */
            $this->path_file = 'contrato_' . ($this->id? $this->id :'TEST') . '.pdf';
            $this->path_file_pre = 'contrato_' . ($this->id? $this->id :'TEST') . '_pre.pdf';
            //$this->path_file = 'C:\Users\Public\Documents\contrato.pdf';

            // FILE SAVE ---> OBTENER RUTA Y GURDARLA EN LA INSTANCIA CONTRATO
            //echo($contrato);
            //dd('se kae aca');
            $client = new Client( env('HELLOSIGN_API_KEY') );
        
            $account = new Account;
            $account->setCallbackUrl(env('APP_URL_NGROK',url('') ).'/hellosign_endpoint');
            $responseAccount = $client->updateAccount($account);
            
            $request = new SignatureRequest;
            $request->enableTestMode();
            $request->setTitle("uHomie - Contrato entre ".$tenant->firstname.' '.$tenant->lastname.' y '.$owner->firstname.' '.$owner->lastname);
            $request->setSubject("A continuación debe firmar el contrato de arriendo para la propiedad: ".$property->name);
            $request->setMessage("Usted tiene 48 horas para aceptar y firmar este contrato.");
            $request->setSigningRedirectUrl(env('APP_URL_NGROK',url('') ));
            //en pin va variable enviada por autentificiación via twillio sms / email
            $request->addSigner(new Signer(array(
                'name' => $owner->firstname.' '.$owner->lastname,
                'email_address' => $owner->email,
            )));
            $request->addSigner(new Signer(array(
                'name' => $tenant->firstname.' '.$tenant->lastname,
                'email_address' => $tenant->email,
            )));
            
            $request->addSigner(new Signer(array(
                'name' => $collateral->firstname.' '.$collateral->lastname,
                'email_address' => $collateral->email,
            )));
            
            
            //$request->addFile('C:\Users\Public\Documents\contrato.pdf','F');
            //dd(Storage::disk('contratos')->url('contrato_' . $this->id . '.pdf'));
            $request->addFile(Storage::disk('contratos')->path('contrato_' . ($this->id? $this->id :'TEST') . '.pdf'));
            //return true;
            //dd('ok');
            $request->setUseTextTags(true);
            $request->setHideTextTags(true);
            //dd('ok');
            $response = $client->sendSignatureRequest($request);
            //sleep(10);
            //$client->getFiles($response->signature_request_id, 'contrato2.pdf', SignatureRequest::FILE_TYPE_PDF);
            $this->signature_request_id_hs = $response->signature_request_id;
            $this->files_url_hs = $response->files_url;
            $this->signing_url_hs = $response->signing_url;
            $this->details_url_hs = $response->details_url;
            $this->allow_reassign_hs = $response->allow_reassign;
            $this->title_hs = $response->title;
            $this->subject_hs = $response->subject;
            $this->message_hs = $response->message;
            $this->allow_decline = $response->allow_decline;
            $this->save();
            //dd('holas');
            return $response;
            //return dd($client->getSignatureRequest($response->signature_request_id));
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            dd( $formatter->getHtmlMessage() );
        }



    }
    
    
        


    
    
}

