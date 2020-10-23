<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
        <div class="md-layout-item md-small-size-100 md-size-100">
            <h3 class="md-title"> Documentos de la Propiedad </h3>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100">
            <ul>
                <li v-for="file in files.files" :key="file.id">
                    <a @click="modalFile(file)">{{file.name}}</a>
                </li>
            </ul>
        </div>
        <file-dialog v-bind:showDialog="showDialog" v-bind:files="document" @hideDialog="hideDialog"></file-dialog>
        <div class="md-layout-item md-small-size-100 md-size-100">
            <form @submit.prevent="save">
                <div class="md-layout-item md-small-size-100 md-size-100" >
                    <label>Propiedad Verificada</label>
                </div>
                <div class="md-layout-item md-small-size-100 md-size-100" >
                    <md-switch v-model="verified" class="md-primary">Verificado</md-switch>
                </div>
                <div class="md-layout-item md-small-size-100 md-size-100">
                    <md-button type="submit" class="md-info" >Guardar</md-button>
                </div>
            </form>
        </div>
        <md-snackbar :md-active.sync="dataSaved">Datos guardados exitosamente!</md-snackbar>
    </div>
</template>
<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import FileDialog from "../../../components/Files/FileDialog.vue";
const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : csrf_token
};
export default {
    name: 'FilesForm',
    components:{
        FileDialog,
        Loading
    },
    props: ['saveDataUrl'],
    data(){
        return {
            showDialog: false,
            files: [],
            verified: '',
            document:{},
            isLoading: false,
            fullPage: true,
            loader: "dots",
            loaderColor: "#1ac036",
            dataSaved: false,
            propertyId: '',
        }
    },
    methods:{
        getPropertyData() {
            const vm = this;
            const propertyId = JSON.parse(vm.$route.params.propertyId)
            this.propertyId = propertyId
            const url = '/adm/properties/' + propertyId
            vm.isLoading = true
            axios.get(url)
            .then((response) => {
                vm.isLoading = false
                vm.files = {
                    files: response.data.property.files
                }
                vm.verified = Boolean(response.data.property.verified)
            });
        },
        modalFile(value){
            this.document = {
                id: value.id,
                name: value.name,
                verified: Boolean(Number(value.verified)),
                verified_ocr: Boolean(Number(value.verified_ocr)),
                val_date: Boolean(Number(value.val_date)),
                factor: value.factor,
                path: value.path
            },
            this.showDialog = true
        },
        hideDialog(value){
            this.showDialog = false
        },
        save() {
            const vm = this;
            vm.isLoading = true
            axios.post(vm.saveDataUrl, {
                propertyId: vm.propertyId,
                verified: vm.verified,
            })
            .then(function(response) {
                vm.isLoading = false
                vm.dataSaved = true
                vm.getPropertyData()
            })
            .catch(function(error) {
            console.log(error);
            });
        },
    },
    mounted(){
        this.getPropertyData()
    }
}
</script>