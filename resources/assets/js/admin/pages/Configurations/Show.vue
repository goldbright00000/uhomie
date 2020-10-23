<template>
    <div class="content">
        <div class="md-layout">
            <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
                <md-card>
                    <md-card-header data-background-color="black">
                        <h4 class="title">Configuraciones</h4>
                        <p class="category">Lista de configuraciones</p>
                    </md-card-header>
                    <md-card-content>
                        <md-card class="md-layout-item md-size-100 md-small-size-100">
                            <md-card-content>
                                <div class="md-layout-item md-small-size-100" v-for="item in configurations" :key="item.id">
                                    <md-field>
                                        <input type="hidden" name="id" :value="item.id"/>
                                        <label for="enabled">{{item.title}}</label>
                                        <md-select name="enabled" v-model="item.enabled" id="enabled" :disabled="sending">
                                            <md-option value="1">Si</md-option>
                                            <md-option value="0">No</md-option>
                                        </md-select>
                                    </md-field>
                                </div>
                                <md-progress-bar md-mode="indeterminate" v-if="sending" />
                                <md-button type="buttom" @click="saveConfigurations" class="md-primary" :disabled="sending">Guardar configuraciones</md-button>
                            </md-card-content>
                        </md-card>
                    </md-card-content>
                </md-card>
            </div>
        </div>
        <md-snackbar :md-active.sync="configurationsSaved">Configuraciones guardadas exitosamente</md-snackbar>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    name: 'Show',
    data: () => ({
        sending: false,
        configurationsSaved: false,
        configurations: [],
        getDataUrl: '/adm/configurations',
        saveDataUrl: '/adm/configurations/update'
    }),
    methods:{
        getConfigurations(){
            const vm = this
            vm.sending = true
            axios.get(vm.getDataUrl)
            .then(function(response) {
                console.log(response)
                vm.sending = false
                vm.configurations = response.data
            }).catch(function(error) {
                vm.sending = false
                console.log(error)
            })
        },
        saveConfigurations(){
            const vm = this
            vm.sending = true
            axios.post(vm.saveDataUrl,{
                configurations: vm.configurations
            }).then(function(response){
                console.log(response)
                vm.sending = false
                vm.configurationsSaved = true
            }).catch(function(error){
                vm.sending = false
                console.log(error)
            })
        }
    },
    computed:{
    },
    mounted(){
        this.getConfigurations();
    }
}
</script>