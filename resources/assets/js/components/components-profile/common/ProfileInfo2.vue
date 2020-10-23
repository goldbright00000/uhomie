<template>
    <div class="profile-info2">
        <div class="columns">
            <div class="column is-half">
                <span class="with-line" style="font-size: 14px;">Nacimiento: {{ info.birthdate.split('-')[2]+'-'+info.birthdate.split('-')[1]+'-'+info.birthdate.split('-')[0] }}<span style="margin-left:14px;font-size: 12px;" v-if="verify_id_front" class="tag is-link is-success"><i style="color: white; margin-right: 5px;"  class="fa fa-check"></i>Verificado</span> </span>
            </div>
            <div class="column is-half">
                <span class="with-line">{{ document_type }}{{ getFilterData('civilstatus',info.document_type)}} {{ info.document_number }}<span style="margin-left:15px;" v-if="verify_carnet" class="tag is-link is-success"><i style="color: white; margin-right: 5px;" class="fa fa-check"></i>Vigente</span></span>
                
            </div>
        </div>
        <div class="columns">
            <div class="column is-half">
                <span class="with-line">{{ info.address }}</span>
            </div>
            <div class="column is-half">
                <span class="with-line" v-if="tipo=='collateral'">Tus arrendatarios: <a>{{ info.tenants && info.tenants.length }}</a></span>
                <span class="with-line" v-if="tipo=='owner'">Postulaciones Recibidas: <a>{{ info.aplications }}</a></span>
                <!-- <span class="with-line" v-if="tipo!=='owner'">Postulaciones: <a>{{ info.postulations }}</a></span> -->
                <span class="with-line" v-if="tipo=='agent'"><a>{{ info.email }}</a></span>
                <span class="with-line" v-if="tipo=='service'">Celular: {{ info.phone_code }} {{ info.phone }}</span>
                <span class="with-line" v-if="tipo=='tenant'">Postulaciones: <span class="has-text-info">{{ info.postulations }}</span></span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-half">
                <span class="with-line" v-if="(tipo!='service' && tipo!='collateral')">Celular: {{ info.phone_code }} {{ info.phone }}</span>
                <span class="with-line" v-if="tipo=='service'">Teléfono fijo: {{ info.phone_code }} {{ info.phone }}</span>
            </div>
            <div class="column is-half">
                <span class="with-line" v-if="tipo=='owner'">Propiedades Registradas: <a>{{ info.properties && info.properties.length }}</a></span>
                <span class="with-line" v-if="tipo=='service'">Email: <a>{{ info.email}}</a></span>
                <span class="with-line" v-if="tipo=='agent'">Proyectos Registrados: <a>{{ info.projects.length }}</a></span>
                <span class="with-line" v-if="tipo=='tenant'">Propiedades Favoritas: <span class="has-text-info">{{ info.fav_holdings }}</span></span>
            </div>
        </div>
    </div>
</template>
<script>

    import Select2 from 'v-select2-component';
    Vue.component('select2', Select2);

    import ComponentBase from '../../../profiles/base';

    import PanelUpDown from '../../PanelUpDown';
    import MyformPersonal from '../common/MyformPersonal';
    import datasheet from '../../../profiles/datasheet';


    export default {
        extends : ComponentBase,
        components: {
            MyformPersonal,
            PanelUpDown,
            datasheet,
        },
        name: 'ProfileInfo2',

        computed: {
            document_type() {
                if (!this.info) {
                    return "";
                }
                if (!this.info.document_type) {
                    return "";
                }
                if (this.info.document_type.toLowerCase().indexOf('rut') >= 0) {
                    return "RUT: ";
                } else {
                    return "Passporte: ";
                }
            },
            verify_carnet() {
                if(this.info.documents.id_front.verified_ocr == 1){
                    if(this.info.documents.id_back.verified_ocr == 1){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            },
            verify_id_front(){
                if(this.info.documents.id_front.verified_ocr == 1){
                    return true;
                }
                return false;
            }
        },
        props: {
          info: Object,
          filters: Object,
          tipo: String
        },
        data() {
            return {}
        },
    }
</script>
