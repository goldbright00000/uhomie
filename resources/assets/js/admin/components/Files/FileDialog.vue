<template>
    <div>
        <md-dialog :md-active.sync="showDialog">
            <md-dialog-title>{{name}}</md-dialog-title>
            <md-content style="padding:1em">
                <div><md-switch v-model="files.verified" class="md-primary">Verificado</md-switch></div>
                <div><md-switch v-model="files.verified_ocr" class="md-primary">Verificación OCR</md-switch></div>
                <div><md-switch v-model="files.val_date" class="md-primary">Documento Vigente</md-switch></div>
                <div>
                <md-field>
                    <label>Factor</label>
                    <md-input  v-model="files.factor" type="number"></md-input>
                </md-field>
                </div>
                <div>
                    <md-button v-if="files.path != null" :href="'/get-file/?path='+files.id" class="md-primary">
                        <md-icon>get_app</md-icon>Descargar Documento
                    </md-button>
                    <md-button v-else class="md-accent">
                        <md-icon>report</md-icon>No hay Documento
                    </md-button>
                </div>
            </md-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="hideDialog">Volver</md-button>
                <md-button class="md-primary" @click="saveDataFile()">Guardar</md-button>
            </md-dialog-actions>
        </md-dialog>
        <md-snackbar :md-active.sync="fileDataSaved">{{mensaje}}</md-snackbar>
    </div>
</template>
<script>
import Axios from 'axios';
export default {
    name: 'FileDialog',
    props:{
        showDialog: {
            type: Boolean,
            default: false
        },
        files: Object,
        
    },
    data(){
        return{
            fileDataSaved: false,
            mensaje: ''
        }
    },
    
    computed:{
        name(){
            switch (this.files.name) {
                case 'id_front':
                    return 'RUT (Anverso)'
                    break;
                case 'id_back':
                    return 'RUT (Reverso)'
                    break;
                case 'work_constancy':
                    return 'Certificado de Empleo';
                    break;
                case 'first_settlement':
                    return '1ra Liquidación';
                    break;
                case 'second_settlement':
                    return '2da Liquidación';
                    break;
                case 'third_settlement':
                    return '3ra Liquidación';
                    break;
                case 'afp':
                    return 'AFP';
                    break;
                case 'dicom':
                    return 'DICOM';
                    break;
                case 'other_income':
                    return 'Otros Ingresos';
                    break;
                case 'saves':
                    return 'Comprobante de Ahorros';
                    break;
                case 'last_invoice':
                    return 'Ultima Factura';
                    break;
                case 'last_electricity_bill':
                    return 'Ultima boleta de eletricidad'
                    break;
                case 'last_water_bill':
                    return 'Ultima boleta de agua'
                    break;
                case 'common_expense_receipt':
                    return 'Gastos comunes'
                    break;
                case 'property_certificate':
                    return 'Certificado de propiedad'
                    break;
                default:
                    return this.files.name;
                    break;
            }
        }
    },
    methods:{
        saveDataFile(){
            const vm = this
            Axios.post('/adm/users/file-data-save', {
                file: vm.files
            })
            .then(function (response) {
                console.log(response);
                vm.fileDataSaved = true;
                vm.mensaje = response.data.mensaje
                vm.hideDialog();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        hideDialog(){
            this.$emit('hideDialog', false);
        }
    },
    mounted(){
    }

}
</script>