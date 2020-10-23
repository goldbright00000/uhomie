<template>
    <div class="scoring-list">
        <div class="columns">
            <div class="column is-12 line-down">
                <span>Lista de Requerimientos</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">
                <ul>
                    <li>
                        <i v-if="info.phone_verified=='1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación del telefono</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(1)" style="cursor:pointer"></i>
                    </li>
                    <li>
                        <i v-if="info.mail_verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación del correo</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(2)" style="cursor:pointer"></i>
                    </li>
                    <li>
                        <i v-if="info.documents.id_front.verified_ocr == 1 && info.documents.id_back.verified_ocr == 1" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación del documento de identidad</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(3)" style="cursor:pointer"></i>
                    </li>
                    <li>
                        <i v-if="info.documents.id_front.verified == 1" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de identidad</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(4)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.work_constancy && info.employment_type==1 || info.role==5 && info.documents.work_constancy && info.employment_type==1">
                        <i v-if="info.documents.work_constancy.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de la constancia del trabajo</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(5)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.first_settlement && info.employment_type!=2 || info.role == 5 && info.documents.first_settlement && info.employment_type!=2">
                        <i v-if="info.documents.first_settlement.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de la 1ra liquidación</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(6)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.second_settlement && info.employment_type!=2 || info.role == 5 && info.documents.second_settlement && info.employment_type!=2">
                        <i v-if="info.documents.second_settlement.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de la 2da liquidación</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(7)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.third_settlement && info.employment_type!=2 || info.role == 5 && info.documents.third_settlement && info.employment_type!=2">
                        <i v-if="info.documents.third_settlement.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de la 3ra liquidación</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(8)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.afp || info.role == 5 && info.documents.afp">
                        <i v-if="info.documents.afp.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación del certificado de AFP</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(9)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.dicom || info.role == 5 && info.documents.dicom">
                        <i v-if="info.documents.dicom.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación del certificado de DICOM</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(10)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.other_income || info.role == 5 && info.documents.other_income">
                        <i v-if="info.documents.other_income.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de otros ingresos</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(11)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.saves || info.role == 5 && info.documents.saves">
                        <i v-if="info.documents.saves.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de comprobante de ahorros</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(12)" style="cursor:pointer"></i>
                    </li>
                    <li v-if="info.role==1 && info.documents.last_invoice || info.role == 5 && info.documents.last_invoice">
                        <i v-if="info.documents.last_invoice.verified == '1'" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Verificación de ultima factura</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(13)" style="cursor:pointer"></i>
                    </li>
                    <li>
                        <i v-if="membership_expire" class="fa fa-check has-text-success"></i>
                        <i v-else class="fa fa-times has-text-danger"></i>
                        <span style="margin-left: 5px; margin-right: 5px;">Membresia <b>{{membership.name}}</b> esta {{membership_expire ? 'vigente' : 'vencida'}}</span>
                        <i class="fa fa-info-circle has-text-info" @click="InfoHelper(14)" style="cursor:pointer"></i>
                    </li>
                </ul>
            </div>
        </div>

        <div class="modal is-active" v-if="modal_info">
            <div class="modal-background" @click="closeModal"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"><i class="fa fa-info-circle"></i>{{helper_title}}</p>
                    <button class="delete" aria-label="close" @click="closeModal"></button>
                </header>
                <section class="modal-card-body">
                    <article v-if="!membership_expire && helper_info == 14" class="message is-danger">
                        <div class="message-body">
                            <p>Su membresia se encuentra vencida, por favor ingrese en la opción <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong> para renovar o mejorar su suscripción.</p>
                        </div>
                    </article>
                    <article class="message">
                        <div class="message-body">
                            <p v-if="helper_info == 1">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de teléfono</strong> y pasar la validación telefónica.</p>
                            <p v-if="helper_info == 2">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de correo</strong> y pasar la validación del correo.</p>
                            <p v-if="helper_info == 3">Deberás de subir una fotografía de tu carnet, de buena calidad en la pestaña de <strong>Documentos</strong> en el <strong><a @click="$router.push('/')">Perfil</a></strong>, para ello necesitas subir tanto el <strong>Anverso</strong> y <strong>Reverso</strong> del carnet para que nuestro sistema valide tu carnet.</p>
                            <p v-if="helper_info == 4">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de Identidad Digital</strong> seguir los pasos sugeridos para la validación de su identidad.</p>
                            <p v-if="helper_info == 5">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de empleo</strong> y subir un archivo en formato pdf de tu certificado de empleo de acuerdo a lo llenado en el formulario de empleo.</p>
                            <p v-if="helper_info == 6">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>1ra Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 7">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>2da Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 8">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>3ra Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 9">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de AFP</strong> y subir un archivo en formato pdf en el cual se demuestre el acumulado de cotizaciones que usted posee.</p>
                            <p v-if="helper_info == 10">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de DICOM</strong> y subir un archivo en formato pdf en el cual se demuestre el puntaje de DICOM q usted posee. Le recomendamos solicitar su DICOM a través de esta <a href="https://soluciones.equifax.cl/">pagina</a>.</p>
                            <p v-if="helper_info == 11">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Otros Ingresos</strong> y subir un archivo en formato pdf el cual de garantía de sus otros ingresos.</p>
                            <p v-if="helper_info == 12">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Comprobante de Ahorros</strong> y subir un archivo en formato pdf el cual de garantía de su consolidación bancaria.</p>
                            <p v-if="helper_info == 13">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Ultima Factura</strong> y subir un archivo en formato pdf el cual demuestre la ultima factura o boleta por servicios o labores prestadas por usted.</p>
                            <div v-if="helper_info == 14">
                                <p v-if="membership.name == 'Premium'">Actualmente posees la membresia <strong>Premium</strong> la cual te permitira multiples funciones elimitadas para mas información ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                                <p v-if="membership.name == 'Select'">Actualmente posees la membresia <strong>Select</strong> la cual te permite postular a propiedades un limite de veces te recomendamos cambiar por una membresia <strong>Premium</strong> el cual te permitira postular a propiedades de forma ilimitada, para mas informacion de las membrasia ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                                <p v-if="membership.name == 'Basic'">Actualmente posees la membresia <strong>Basic</strong> el cual te permite un numero muy limitado de postulaciones te recomendamos cambiar por una membresia <strong>Select</strong> o <strong>Premium</strong> el cual te permitira postular a propiedades de forma ilimitada, para mas informacion de las membrasia ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                            </div>
                        </div>
                    </article>
                    <article class="message is-warning is-small" v-if="helper_info != 14">
                        <div class="message-body">
                            <p>Se recomienda que todos los archivos de ámbito financiero sean de procedencia legal, para que nuestro sistema encuentre de manera mas precisa toda la información requerida.</p>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModal">Volver</button>
                </footer>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    name: 'ScoringList',
    props: {
        info: Object
    },
    data() {
        return {
            helper_title: '',
            helper_info: '',
            modal_info: false,
            membership: this.info.membership_data,
            membership_expire: moment(this.info.membership_data.pivot.expires_at).isAfter(new Date())
            
        }
    },
    methods: {
        InfoHelper(value){

            

            switch (value) {
                case 1:
                    this.helper_title = 'Verificación de telefono';
                    this.helper_info = 1;
                    break;
                case 2:
                    this.helper_title = 'Verificación de correo';
                    this.helper_info = 2;
                    break;
                case 3:
                    this.helper_title = 'Verificación de carnet';
                    this.helper_info = 3;
                    break;
            
                case 4:
                    this.helper_title = 'Verificación de identidad';
                    this.helper_info = 4;
                    break;
                case 5:
                    this.helper_title = 'Verificación de certificado de empleo';
                    this.helper_info = 5;
                    break;
                case 6:
                    this.helper_title = 'Verificación de 1ra liquidación';
                    this.helper_info = 6;
                    break;
                case 7:
                    this.helper_title = 'Verificación de 2da liquidación';
                    this.helper_info = 7;
                    break;
                case 8:
                    this.helper_title = 'Verificación de 3ra liquidación';
                    this.helper_info = 8;
                    break;
                case 9:
                    this.helper_title = 'Verificación del certificado de AFP';
                    this.helper_info = 9;
                    break;
                case 10:
                    this.helper_title = 'Verificación del certificado de DICOM';
                    this.helper_info = 10;
                    break;
                case 11:
                    this.helper_title = 'Verificación de otros ingrsos';
                    this.helper_info = 11;
                    break;
                case 12:
                    this.helper_title = 'Verificación de comprobante de ahorros';
                    this.helper_info = 12;
                    break;
                case 13:
                    this.helper_title = 'Verificación ultima factura';
                    this.helper_info = 13;
                    break;
                case 14: 
                    this.helper_title = 'Membresia';
                    this.helper_info = 14;
                    break;
                default:
                    this.helper_title = 'Verificación';
                    this.helper_info = 0;
                    break;
            }

            this.modal_info = true;

        },
        closeModal(){
            this.modal_info = false;
        }
    },
}
</script>
