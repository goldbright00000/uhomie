<template>
    <div>
        <div :class="info.space_id == null ? 'card warning-card' : 'card'">
            <header class="card-header">
                <p class="card-header-title">
                    <select v-model="info.space_id" style="width:100%;" class="list-spaces" id="select" @change="changeSpace(info.photo_id ? info.photo_id : info.id,info.space_id)" :disabled="editing ? false : true">
                        <option value="null" selected disabled>[Selecione un Espacio]</option>
                        <option v-for="item in spaces" :key="item.id" :value="item.id">{{item.name}}</option>
                    </select>
                </p>
                <a class="card-header-icon" @click="editing ? delPhoto(info.id) : ''" aria-label="more options" >
                    <span class="icon">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content" style="padding: 0">
                <div class="content">
                    <div class="has-text-centered">
                        <img :src="info.path">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

const _token = document.head.querySelector("[name=csrf-token]").content;

export default {
    name: 'ImgCoverProperty',
    props: {
        info: Object,
        editing: {
            type: Boolean,
            default: false
        },
        property_type: {
            type: String,
            default: 'Propiedad'
        }
    },
    computed: {
        spaces(){
            switch (this.property_type) {
                case 'Bodega':
                    return this.$parent.spaces.filter((elemento) =>  elemento.name == 'Bodega');
                    break;
                case 'Terreno':
                    return this.$parent.spaces.filter((elemento) =>  elemento.name == 'Terreno');
                    break;
                case 'Estacionamiento':
                    return this.$parent.spaces.filter((elemento) =>  elemento.name == 'Estacionamiento');
                    break;
                case 'Oficina':
                    return this.$parent.spaces.filter((elemento) =>  elemento.name == 'Oficina');
                    break;
                default:
                    return this.$parent.spaces;
                    break;
            }
        }
    },
    data() {
        return {
        }
    },
    methods: {
        delPhoto(id){
            this.$emit('delphotocover', {'id': id});
        },
        changeSpace(photo,space){
            this.$emit('space_id', {'photo': photo, 'space': space});
        }
    },
    mounted(){
        
    }
}
</script>

<style>
.warning-card {
    -webkit-box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
    -moz-box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
    box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
}
</style>