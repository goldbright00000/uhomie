<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
        <form id="executiveForm" @submit.prevent="save">
            <div class="md-layout-item md-small-size-100 md-size-100" >
                <h3 class="md-title"> Ejecutivo de Propiedad </h3>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100" >
                <md-field>
                    <label for="executives">Seleccione Ejecutivo</label>
                    <md-select v-model="form.executive_id" name="executives" id="executives">
                        <md-option v-for="executive in executives" :value="executive.id" :key="executive.id">{{executive.name}}</md-option>
                    </md-select>
                </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
                <md-button type="submit" class="md-info" >Guardar</md-button>
            </div>
        </form>
        <md-snackbar :md-active.sync="dataSaved">Datos guardados exitosamente!</md-snackbar>
    </div>
</template>
<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : csrf_token
};
export default {
    name:'executive-form',
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
        return {
            value: null,
            executives:[],
            form:{
                propertyId: null,
                executive_id: null,
            },
            isLoading: false,
            fullPage: true,
            loader: "dots",
            loaderColor: "#1ac036",
            dataSaved: false,
        }
    },
    methods:{
        getUsersExecutive(){
            const vm = this;
            const url = '/adm/users-admin'
            axios.get(url)
            .then(function (response) {
                console.log(response);
                vm.executives = response.data.records.filter(element => element.role == 3);
            })
            .catch(function (error) {
                console.log(error);
            })
        },
        getProperty() {
            const vm = this;
            const propertyId = JSON.parse(vm.$route.params.propertyId)
            const url = '/adm/properties/' + propertyId
            vm.isLoading = true
            axios.get(url)
            .then((response) => {
            vm.isLoading = false
            vm.property = response.data.property

            vm.form = {
                propertyId: vm.property.id,
                executive_id: vm.property.executive_id
            }
            console.log(response.data.property_for)
            for (var i = 0; i < response.data.property_for.length; i++) {
                // vm.propertyForSelected[i] = response.data.property_for[i].id
                console.log(response.data.property_for[i])
            }

            });
        },
        save() {
            const vm = this;
            vm.isLoading = true
            axios.post(vm.saveDataUrl, {
                propertyId: vm.form.propertyId,
                executive_id: vm.form.executive_id,
            })
            .then(function(response) {
                vm.dataSaved = true;
                vm.isLoading = false;
            })
            .catch(function(error) {
            console.log(error);
            });
        },
    },
    mounted(){
        this.getUsersExecutive();
        this.getProperty();
    }
}
</script>