<template>
    <div>
        <div class="columns is-vcentered is-mobile">
            <div class="column is-8-mobile is-10-desktop">
                <div class="property-scoring" style="margin: 0px;">
                    <vue-slider v-model="scoringSlider.value" v-bind="scoringSlider"></vue-slider>
                </div>
            </div>
            <div class="column is-4-mobile is-2-desktop">
                <div class="card tooltip" :data-tooltip="scoring_message">
                    <div class="card-content has-text-centered">
                        <img :src="imagesDir + '/emoticons/' + scoring_emoticon"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-multiline is-mobile">
            <div class="column is-one-quarter-desktop is-half-mobile">
                <div class="card card-scoring" @click="showModalPersonal()" style="cursor:pointer">
                    <div class="card-content has-text-centered content-scoring">
                        <div class="img-scoring">
                            <img :src="imagesDir + '/scoring/icon-verificacion.png' "/>
                            <div><strong class=" is-italic has-text-info">{{scoring_user + scoring_collateral}} / 360</strong></div>
                            <div><small class="is-italic"><b>Verificación de tu perfil y de tu aval</b></small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter-desktop is-half-mobile">
                <div class="card card-scoring" @click="showModalDocs()" style="cursor:pointer">
                    <div class="card-content has-text-centered content-scoring">
                        <div class="img-scoring">
                            <img :src="imagesDir + '/scoring/icon-estabilidad.png' "/>
                            <div><strong class=" is-italic has-text-info">{{scoring_employment + scoring_finantial + scoring_docs}} / 670</strong></div>
                            <div><small class="is-italic"><b>Estabilidad laboral y socio-económica</b></small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter-desktop is-half-mobile">
                <div class="card card-scoring" @click="showModalDicom()" style="cursor:pointer">
                    <div class="card-content has-text-centered content-scoring">
                        <div class="img-scoring">
                            <img :src="imagesDir + '/scoring/icon-riesgo.png' "/>
                            <div><strong class=" is-italic has-text-info">{{scoring_dicom}} / 100</strong></div>
                            <div><small class="is-italic"><b>Riesgo y estabilidad financiera</b></small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-one-quarter-desktop is-half-mobile">
                <div class="card card-scoring" @click="showModalMembership()" style="cursor:pointer">
                    <div class="card-content has-text-centered content-scoring">
                        <div class="img-scoring">
                            <img :src="imagesDir + '/scoring/icon-preferencias.png' "/>
                            <div><strong class=" is-italic has-text-info">{{scoring_membreship + scoring_conditions}} / 250</strong></div>
                            <div><small class="is-italic"><b>Membresia y preferencias de arriendo</b></small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="modal is-active" v-if="modal_personal">
            <div class="modal-background" @click="closeModalPersonal"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"><i class="fa fa-info-circle"></i> Validación de identidad y información personal</p>
                    <button class="delete" aria-label="close" @click="closeModalPersonal"></button>
                </header>
                <section class="modal-card-body">
                    <p><b>Arrendatario</b></p>
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
                    </ul>
                    <p><b>Aval</b></p>
                    <ul>
                        <li>
                            <i v-if="info.confirmed_collateral=='1'" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Confirmación del Aval</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(1)" style="cursor:pointer"></i>
                        </li>
                        <li>
                            <i v-if="info.collateral.phone_verified=='1'" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación del telefono</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(1)" style="cursor:pointer"></i>
                        </li>
                        <li>
                            <i v-if="info.collateral.mail_verified == '1'" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación del correo</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(2)" style="cursor:pointer"></i>
                        </li>
                        <li>
                            <i v-if="info.collateral.documents.id_front.verified_ocr == 1 && info.documents.id_back.verified_ocr == 1" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación del documento de identidad</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(3)" style="cursor:pointer"></i>
                        </li>
                        <li>
                            <i v-if="info.collateral.documents.id_front.verified == 1" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación de identidad</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(4)" style="cursor:pointer"></i>
                        </li>
                    </ul>
                    <article class="message is-info is-small" v-if="helper_info != ''">
                        <div class="message-body">
                            <p v-if="helper_info == 1">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de teléfono</strong> y pasar la validación telefónica.</p>
                            <p v-if="helper_info == 2">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de correo</strong> y pasar la validación del correo.</p>
                            <p v-if="helper_info == 3">Deberás de subir una fotografía de tu carnet, de buena calidad en la pestaña de <strong>Documentos</strong> en el <strong><a @click="$router.push('/')">Perfil</a></strong>, para ello necesitas subir tanto el <strong>Anverso</strong> y <strong>Reverso</strong> del carnet para que nuestro sistema valide tu carnet.</p>
                            <p v-if="helper_info == 4">Deberás dirigirte hacia <strong><a @click="$router.push('/configs')">Configuración Cuenta & Verificación Identidad</a></strong> y dirigirte hacia la pestaña <strong>Verificaciónes</strong> y en la opción <strong>Verificación de Identidad Digital</strong> seguir los pasos sugeridos para la validación de su identidad.</p>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModalPersonal">Volver</button>
                </footer>
            </div>
        </div>



        <div class="modal is-active" v-if="modal_docs">
            <div class="modal-background" @click="closeModalDocs()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"><i class="fa fa-info-circle"></i> Estabilidad laboral</p>
                    <button class="delete" aria-label="close" @click="closeModalDocs()"></button>
                </header>
                <section class="modal-card-body">
                    <p v-if="info.employment_type == 0"><b>No has seleccionado perfil de empleo!!!</b></p>
                    <p v-if="info.employment_type == 1"><b>Empleado</b></p>
                    <p v-if="info.employment_type == 2"><b>Freelancer</b></p>
                    <p v-if="info.employment_type == 3"><b>Desempleado</b></p>
                    <ul>
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
                        <li v-if="info.role==1 && info.documents.afp || info.role == 5 && info.documents.afp">
                            <i v-if="info.documents.afp.verified == '1'" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación de AFP</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(15)" style="cursor:pointer"></i>
                        </li>
                    </ul>
                    <article class="message is-info is-small" v-if="helper_info != ''">
                        <div class="message-body">
                            <p v-if="helper_info == 5">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de empleo</strong> y subir un archivo en formato pdf de tu certificado de empleo de acuerdo a lo llenado en el formulario de empleo.</p>
                            <p v-if="helper_info == 6">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>1ra Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 7">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>2da Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 8">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>3ra Liquidación</strong> y subir un archivo en formato pdf en el cual se demuestre el sueldo liquido que usted percibe mensualmente en su empleo.</p>
                            <p v-if="helper_info == 9">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de AFP</strong> y subir un archivo en formato pdf en el cual se demuestre el acumulado de cotizaciones que usted posee.</p>
                            <p v-if="helper_info == 11">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Otros Ingresos</strong> y subir un archivo en formato pdf el cual de garantía de sus otros ingresos.</p>
                            <p v-if="helper_info == 12">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Comprobante de Ahorros</strong> y subir un archivo en formato pdf el cual de garantía de su consolidación bancaria.</p>
                            <p v-if="helper_info == 13">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Ultima Factura</strong> y subir un archivo en formato pdf el cual demuestre la ultima factura o boleta por servicios o labores prestadas por usted.</p>
                            <p v-if="helper_info == 15">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado AFP</strong> y subir un archivo en formato pdf el cual demuestre tu acumulaciones de cotizaciones q has recibido por tu trayecto laboral.</p>
                        </div>
                    </article>
                    <article class="message is-warning is-small" v-if="helper_info != 14">
                        <div class="message-body">
                            <p>Se recomienda que todos los archivos de ámbito financiero deben poseer una fecha de emisión reciente, para que nuestro sistema encuentre de manera mas precisa toda la información requerida.</p>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModalDocs()">Volver</button>
                </footer>
            </div>
        </div>


        <div class="modal is-active" v-if="modal_dicom">
            <div class="modal-background" @click="closeModalDicom()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"><i class="fa fa-info-circle"></i> Nivel de riesgo financiero</p>
                    <button class="delete" aria-label="close" @click="closeModalDicom()"></button>
                </header>
                <section class="modal-card-body">
                    <ul>
                        <li v-if="info.role==1 && info.documents.dicom || info.role == 5 && info.documents.dicom">
                            <i v-if="info.documents.dicom.verified == '1'" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Verificación del certificado de DICOM</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(10)" style="cursor:pointer"></i>
                        </li>
                    </ul>
                    <article class="message is-info is-small" v-if="helper_info != ''">
                        <div class="message-body">
                            <p v-if="helper_info == 10">Deberás dirigirte hacia <strong><a @click="$router.push('/')">Perfil</a></strong>, en la pestaña de <strong>Documentos</strong> encontrar la opción <strong>Certificado de DICOM</strong> y subir un archivo en formato pdf en el cual se demuestre el puntaje de DICOM q usted posee. Le recomendamos solicitar su DICOM a través de esta <a href="https://soluciones.equifax.cl/">pagina</a>.</p>
                        </div>
                    </article>
                    <article class="message is-warning is-small" v-if="helper_info != 14">
                        <div class="message-body">
                            <p>Se recomienda que todos los archivos de ámbito financiero sean de posean un fecha de emisión reciente, para que nuestro sistema encuentre de manera mas precisa toda la información requerida.</p>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModalDicom()">Volver</button>
                </footer>
            </div>
        </div>

        

        <div class="modal is-active" v-if="modal_membership">
            <div class="modal-background" @click="closeModalMembership()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"><i class="fa fa-info-circle"></i> Preferencias de arriendo</p>
                    <button class="delete" aria-label="close" @click="closeModalMembership()"></button>
                </header>
                <section class="modal-card-body">
                    <ul>
                        <li>
                            <i v-if="membership_expire" class="fa fa-check has-text-success"></i>
                            <i v-else class="fa fa-times has-text-danger"></i>
                            <span style="margin-left: 5px; margin-right: 5px;">Membresia <b>{{membership.name}}</b> esta {{membership_expire ? 'vigente' : 'vencida'}}</span>
                            <i class="fa fa-info-circle has-text-info" @click="InfoHelper(14)" style="cursor:pointer"></i>
                        </li>
                    </ul>
                    <article v-if="!membership_expire && helper_info == 14" class="message is-danger">
                        <div class="message-body">
                            <p>Su membresia se encuentra vencida, por favor ingrese en la opción <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong> para renovar o mejorar su suscripción.</p>
                        </div>
                    </article>
                    <article class="message is-info is-small" v-if="helper_info != ''">
                        <div class="message-body">
                            <div v-if="helper_info == 14">
                                <p v-if="membership.name == 'Premium'">Actualmente posees la membresia <strong>Premium</strong> la cual te permitira multiples funciones ilimitadas para mas información ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                                <p v-if="membership.name == 'Select'">Actualmente posees la membresia <strong>Select</strong> la cual te permite postular a propiedades un limite de veces te recomendamos cambiar por una membresia <strong>Premium</strong> el cual te permitira postular a propiedades de forma ilimitada, para mas informacion de las membrasia ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                                <p v-if="membership.name == 'Basic'">Actualmente posees la membresia <strong>Basic</strong> el cual te permite un numero muy limitado de postulaciones te recomendamos cambiar por una membresia <strong>Select</strong> o <strong>Premium</strong> el cual te permitira postular a propiedades de forma ilimitada, para mas informacion de las membrasia ingresa a <strong><a @click="$router.push('/membership')">Mi membresia y Medios de Pagos</a></strong>.</p>
                            </div>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModalMembership()">Volver</button>
                </footer>
            </div>
        </div>    
    </div>
</template>

<script>

const imagesDir = document.getElementById('images-dir').value;

import vueSlider from "vue-slider-component";

export default {
    
    name: 'TableScoring',
    components: {
        vueSlider
    },
    props: {
        info: Object
    },
    data() {
        return {
            imagesDir: imagesDir,
            modal_personal: false,
            modal_docs: false,
            modal_dicom: false,
            modal_membership: false,
            helper_title: '',
            helper_info: '',
            modal_info: false,
            membership: this.info.membership_data,
            membership_expire: moment(this.info.membership_data.pivot.expires_at).isAfter(new Date()),
            scoring_user: this.info.scoring_info.contact + this.info.scoring_info.identity + this.info.scoring_info.nationality,
            scoring_collateral: this.info.scoring_info.collateral_contact + this.info.scoring_info.collateral_identity + this.info.scoring_info.collateral_nationality + this.info.scoring_info.collateral_confirm,
            scoring_employment: this.info.scoring_info.employment,
            scoring_docs: this.info.scoring_info.docs,
            scoring_finantial: this.info.scoring_info.finantial,
            scoring_membreship: this.info.scoring_info.membreship,
            scoring_conditions: this.info.scoring_info.conditions,
            scoring_dicom: this.info.scoring_info.dicom,

            scoringSlider: {
                    value: this.info.scoring_info.contact + this.info.scoring_info.identity + this.info.scoring_info.nationality + this.info.scoring_info.collateral_contact + this.info.scoring_info.collateral_identity + this.info.scoring_info.collateral_nationality + this.info.scoring_info.collateral_confirm + this.info.scoring_info.employment + this.info.scoring_info.docs + this.info.scoring_info.finantial + this.info.scoring_info.membreship + this.info.scoring_info.conditions + this.info.scoring_info.dicom,
                    min: 0,
                    max: 1380,
                    height: 5,
                    tooltip: "always",
                    piecewise: true,
                    disabled: true,
                    interval: 250,
                    bgStyle: {
                    background:
                        "linear-gradient(90deg, rgba(241,62,13,1) 0%, rgba(232,245,11,1) 50%, rgba(30,181,6,1) 100%)"
                    },
                    processStyle: {
                    background: "transparent"
                    },
                    piecewiseStyle: {
                    visibility: "visible",
                    width: "12px",
                    height: "12px"
                    },
                    sliderStyle: {
                    background: "transparent",
                    boxShadow: "none"
                    }
                },
            
            scoring_emoticon:  '018-shocked.png',
            scoring_message: 'No se encuentra Scoring'
        }
    },
    methods: {
        InfoHelper(value){
            if(value != 0){
                this.helper_info = value;
            } else {
                this.helper_info = 0;
            }

            //this.modal_info = true;

        },
        closeModal(){
            this.modal_info = false;
        },
        showModalPersonal(){
            this.modal_personal = true;
        },
        closeModalPersonal(){
            this.modal_personal = false;
            this.helper_info = '';
        },
        showModalDocs(){
            this.modal_docs = true;
        },
        closeModalDocs(){
            this.modal_docs = false;
            this.helper_info = '';
        },
        showModalDicom(){
            this.modal_dicom = true;
        },
        closeModalDicom(){
            this.modal_dicom = false;
            this.helper_info = '';
        },
        showModalMembership(){
            this.modal_membership = true;
        },
        closeModalMembership(){
            this.modal_membership = false;
            this.helper_info = '';
        },
        classification(){
            var scoring = this.info.scoring_info.contact + this.info.scoring_info.identity + this.info.scoring_info.nationality + this.info.scoring_info.collateral_contact + this.info.scoring_info.collateral_identity + this.info.scoring_info.collateral_nationality + this.info.scoring_info.collateral_confirm + this.info.scoring_info.employment + this.info.scoring_info.docs + this.info.scoring_info.finantial + this.info.scoring_info.membreship + this.info.scoring_info.conditions + this.info.scoring_info.dicom;
            if(scoring >= 0 && scoring <= 300){
                this.scoring_message = 'Tu probabilidad de arriendo es: Muy baja',
                this.scoring_emoticon = '032-dissapointment.png'
            }
            if(scoring >= 301 && scoring <= 500){
                this.scoring_message = 'Tu probabilidad de arriendo es: Bajo',
                this.scoring_emoticon = '033-thinking.png'
            }
            if(scoring >= 501 && scoring <= 800){
                this.scoring_message = 'Tu probabilidad de arriendo es: Medio',
                this.scoring_emoticon = '017-creepy.png'
            }
            if(scoring >= 801 && scoring <= 900){
                this.scoring_message = 'Tu probabilidad de arriendo es: Bueno',
                this.scoring_emoticon = '036-cynical.png'
            }
            if(scoring >= 901 && scoring <= 1100){
                this.scoring_message = 'Tu probabilidad de arriendo es: Alto',
                this.scoring_emoticon = '021-grinning.png'
            }
            if(scoring >= 1101 && scoring <= 1380){
                this.scoring_message = 'Tu probabilidad de arriendo es: Muy alta',
                this.scoring_emoticon = '035-famous.png'
            }
        }
    },
    mounted() {
        this.classification();
    },
}
</script>

<style>

.card-scoring {
    margin-top: 50px;
}

.img-scoring {
    position:relative;
    top:-80px;
}

.content-scoring {
    height: 180px;
}

</style>