<template>
    <div>
        <div class="columns">
            <div class="column">
                <input type="hidden" v-model="info.id">
                <div class="field">
                    <label class="label">Servicio Primario:</label>
                    <div class="control">
                        <div class="select">
                            <select v-model="info.type_id" :disabled="!editing">
                                <option value="">[Seleccione una Opción]</option>
                                <option v-for="item in filters.service_type.options" @click="getServices(item.id)" :key="item.id" :value="item.id" :selected="item.id==info.type_id">{{item.text}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Servicio Secundario:</label>
                    <div class="control">
                        <div class="select">
                            <select v-model="info.list_id" :disabled="!editing">
                                <option value="">[Seleccione una Opción]</option>
                                <option v-for="item in services" :key="item.id" :value="item.id" :selected="item.id==info.list_id">{{item.text}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Descripcion de Servicio:</label>
                    <div class="control">
                        <textarea rows="6" style="width: 100%;padding: 6px;" required v-model="info.description" :disabled="!editing"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MyformPersonal',
    props: {
        info: Object,
        filters: Object,
        editing:false,
    },
    computed:{
        services: function(){
            var w = this.info.type_id
            var nfo=this.filters.service_list.options
            let approved = nfo.filter(nfo => nfo.type == w);
            return approved;
        }
    },
    data() {
        return {
        }
    },
    methods:{
        getServices(w){
            var nfo=this.filters.service_list.options
            let approved = nfo.filter(nfo => nfo.type == w);
            this.services = approved;
        }
    },
    created(){

    }
}
</script>

