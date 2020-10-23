<template>
    <section class="section">
        <h3 class="subtitle is-size-5 has-text-weight-bold">
            Dias Disponibles
        </h3>
        <v-calendar
            style="border-radius:0;"
            :min-date='new Date()'
            :mode="mode"
            is-inline
            is-expanded
            color="blue"
            :disabled-dates="fechas_ocupadas_computado"
            :columns="$screens({ default: 1, lg: 2 })">
        </v-calendar>
    </section>
</template>
<script>
import Axios from "axios";
import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';
export default {
    data(){
        return {
            mode: 'range',
            fechas_ocupadas: null,
        }
    },
    props:{
        property: Object
    },
    computed:{
        fechas_ocupadas_computado: function(){
            if( !this.fechas_ocupadas ) return null;
            else {
                let arreglo = [];
                this.fechas_ocupadas.disables.forEach(function(elem){
                arreglo.push({start: elem, end: elem});
                });
                this.fechas_ocupadas.postulations.forEach(function(elem){
                arreglo.push(JSON.parse(elem))
                })
                return arreglo;
            }
        }
    },
    methods:{
        disabled_dates(){
            let vm = this;
            Axios.post('/explorar/get-unavailable-property-days', {
                property_id: vm.property.id
            }).then(function(response){
                console.log('response: ', response);
                if( response.status == 244 ){
                toastr.warning('Debes estar logueado para poder postular');
                } 
                if( response.status == 200) {
                vm.fechas_ocupadas = response.data;
                }
                
            });
        }
    },
    mounted(){
        this.disabled_dates()
    }
}
</script>