<template>
    <div class="profile-documents">
        <div class="columns">
            <div class="column is-12 line-down">
                <span>Mis Documentos</span>
            </div>
        </div>
        <div class="columns is-multiline">
            <div class="column is-one-third" id="rut_frontal_step">
                <span v-if="info.documents.id_front!=null  ">
                    <doc-view v-on:updateFront="updateFrontFromChild($event)" :file="info.documents.id_front" :mines="'image/jpeg'"></doc-view>{{ info.document_type != 'PASSPORT' ? 'RUT (Anverso)' : 'Pasaporte (Hoja Principal)' }}
                </span>
            </div>
            <div class="column is-one-third">
                <span v-if="info.documents.id_back!=null">
                    <doc-view v-on:updateBack="updateBackFromChild($event)" :file="info.documents.id_back" :mines="'image/jpeg'"></doc-view>{{ info.document_type != 'PASSPORT' ? 'RUT (Reverso)' : 'Pasaporte (Foto Visado)' }}
                </span>
            </div>
            <div v-if="info.role==1 || info.role==5" class="column is-one-third">
                <span v-if="info.employment_type==1 || info.role==5"><doc-view
                        :file="info.documents.work_constancy" :mines="'application/pdf'"></doc-view>Certificado de empleo</span>
            </div>
            <div v-if="info.role==1 && info.employment_type!=2 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.first_settlement!=null"><doc-view
                        :file="info.documents.first_settlement" :mines="'application/pdf'"></doc-view>1ra Liquidación</span>
            </div>
            <div v-if="info.role==1 && info.employment_type!=2 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.second_settlement!=null"><doc-view
                        :file="info.documents.second_settlement" :mines="'application/pdf'"></doc-view>2da Liquidación</span>
            </div>
            <div v-if="info.role==1 && info.employment_type!=2 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.third_settlement!=null"><doc-view
                        :file="info.documents.third_settlement" :mines="'application/pdf'"></doc-view>3ra Liquidación</span>
            </div>
            <div v-if="info.role==1 && info.afp || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.afp!=null"><doc-view :file="info.documents.afp" :mines="'application/pdf'"></doc-view>Certificado de AFP.</span>
            </div>
            <div v-if="info.role==1 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.dicom!=null"><doc-view :file="info.documents.dicom" :mines="'application/pdf'"></doc-view>Certificado DICOM</span>
            </div>

            <div v-if="info.role==1 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.other_income!=null"><doc-view :file="info.documents.other_income" :mines="'application/pdf'"></doc-view>Otros ingresos</span>
            </div>

            <div v-if="info.role==1 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.saves!=null"><doc-view :file="info.documents.saves" :mines="'application/pdf'"></doc-view>Comprobante de Ahorros</span>
            </div>

            <div v-if="info.role==1 || info.role == 5" class="column is-one-third">
                <span v-if="info.documents.last_invoice!=null"><doc-view :file="info.documents.last_invoice" :mines="'application/pdf'"></doc-view>Ultima Factura</span>
            </div>

        </div>
    </div>
</template>
<script>

    import DocView from '../common/DocView.vue';

    export default {
        components: {
            DocView
        },
        name: 'ProfileDocuments',
        props: {
            info: Object,

        },
        data() {
            return {}
        },
        methods:{
            updateFrontFromChild($event){
                console.log('se gatilló el evento updateFrontFromChild');
                this.info.documents.id_front = $event;
                console.log($event);
            },
            updateBackFromChild($event){
                console.log('se gatilló el evento updateBackFromChild');
                this.info.documents.id_back = $event;
                console.log($event);
            }
        }
    }
</script>
