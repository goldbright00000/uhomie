<template>
    <div class="columns is-mobile">
        <div class="column is-three-quarters">
            <span>{{info.document_name}}</span>
        </div>
        <div class="column has-text-right">
            <p>
                <span :class="info.verified == '0' ? 'tooltip': 'is-hidden' " data-tooltip="Documento No Verificado"><img :src="imagesDir+'/icono_cruz_basic.png'" /></span>
                <span :class="info.verified == '1' ? 'tooltip': 'is-hidden' " data-tooltip="Documento Verificado"><img :src="imagesDir+'/doc-check.png'" /></span>
                <span v-if="view" class="tooltip" data-tooltip="Ver Documento">
                    <a @click="modalDocument(info.id)">
                        <img :src="imagesDir+'/doc-eye.png'" :class="!info.path ? 'is-hidden' : ''" style="cursor: pointer"/>
                    </a>
                    <!--<a :href="'/get-file/?path='+info.id">
                        <img :src="imagesDir+'/doc-eye.png'" :class="!info.path ? 'is-hidden' : ''" style="cursor: pointer"/>
                    </a>-->
                </span>
            </p>
        </div>
    </div>
</template>
<script>
import Axios from 'axios';
    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'DocTenantView',
        props: {
            info: Object
        },        
        data: function () {
            return {
                imagesDir : imagesDir,
                name: '',
                view: {
                    type: Boolean,
                    default: false
                }
            }
        },
        methods: {
            getTextName(){
                switch(this.info.name){
                    case 'id_front':
                        this.info.document_name = 'Identificacion Anverso';
                        this.view = false;
                        break;
                    case 'id_back':
                        this.info.document_name = 'Identificacion Reverso';
                        this.view = false;
                        break;
                    case 'dicom':
                        this.info.document_name = 'Certificado Dicom';
                        this.view = true;
                        break;
                    case 'first_settlement':
                        this.info.document_name = 'Primera Liquidación';
                        this.view = true;
                        break;
                    case 'second_settlement':
                        this.info.document_name = 'Segunda Liquidación';
                        this.view = true;
                        break;
                    case 'third_settlement':
                        this.info.document_name = 'Tercera Liquidacion';
                        this.view = true;
                        break;
                    case 'afp':
                        this.info.document_name = 'Certificado de AFP';
                        this.view = true;
                        break;
                    case 'work_constancy':
                        this.info.document_name = 'Constancia de Trabajo';
                        this.view = true;
                        break;
                    case 'saves':
                        this.info.document_name = 'Certificado de Ahorros';
                        this.view = true;
                        break;
                    case 'other_income':
                        this.info.document_name = 'Certificado de otros ingresos';
                        this.view = true;
                        break;
                    case 'last_invoice':
                        this.info.document_name = 'Ultima boleta por servicios prestados';
                        this.view = true;
                        break;
                    
                }
            },
            modalDocument(id){
                this.loading = true;
                Axios
                .get(`/get-file-s3/${id}`)
                .then(resp => {
                    this.loading = false;
                    this.$emit('document', {'s3': resp.data.s3, 'modal' : true, 'document': this.info});
                })
                .catch(resp => {
                    this.loading = false;
                    toastr.error('El documento no se encuentra disponible para visualización')
                });
            }
        },
        mounted(){
            this.getTextName();
        }
    }
</script>