<template>
    <div class="owner-contracts" >
            <div class="columns" style="padding-bottom: 20px;">
                <div class="column is-12 ">
                    <div class="line-down column is-12">
                    <span >Contratos de arriendo</span>
                    </div>
                    <div v-if="!info.length">
                        <p style="text-align: center">
                            <br>
                                No tienes contratos.
                            <br>
                            <br>
                            <img src="/images/errors/marca-de-agua.png" alt="uHomie">
                            <br>
                            <br>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <div class="box" v-for="(contract, index) in info" :key="index">
                <h6 class="title is-6">Contrato de Arriendo para la Propiedad: {{contract.property.name}}</h6>
                <div class="columns" >
                    <div class="column is-12">
                        <span><a target="_blank" :href="'/contracts/'+contract.id+'/get_stream'"><img src="/images/icons/contratos_hover.png" style="width:20px;margin-right: 10px" >Descargar copia del contrato</a></span>
                    </div>
                </div>
                <div class="columns" >
                    <div class="column is-12">
                        <div class="columns" >
                            <div class="column is-1">
                                <div class="image media-left is-48x48" >
                                    <img class="is-rounded" :src="contract.tenant_avatar">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p class="subtitle is-5">Arrendatario</p>
                            </div>
                            <div class="column is-4">
                                <div class="control">
                                    <input class="input" type="text" readonly :value="contract.tenant_name">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+contract.tenant_sign_status">{{ stt_title[contract.tenant_sign_status] }}</p>
                            </div>
                            <!--
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
                <div class="columns" >
                    <div class="column is-12">
                        <div class="columns" >
                            <div class="column is-1">
                                <div class="image media-left is-48x48" >
                                    <img class="is-rounded" :src="contract.owner_avatar">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p class="subtitle is-5">Arrendador</p>
                            </div>
                            <div class="column is-4">
                                <div class="control">
                                    <input class="input" type="text" readonly :value="contract.owner_name">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+contract.owner_sign_status">{{ stt_title[contract.owner_sign_status] }}</p>
                            </div>
                            <!--
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    import OwnerCore from './OwnerCore';
    import GetingData from '../common/GetingData';

    export default {
        extends: OwnerCore,
        components: {},
        name: 'OwnerContracts',
        props: {},
        mounted(){
            console.log('eiei');
            console.log(this.$attrs['user-id']);
            
            axios.get('/users/profile/owner/' + this.$attrs['user-id'] +'/contracts', {
                params: {},
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
                
            }).then(
                response => {
                    this.info = response.data;
                    console.log(this.info.length);
                }
            ).catch(function (error) {
                console.log(error);
            });
        },
        data() {
            return {
                contract: null,
                stt_title : ['Firma Cancelada', 'Firma en Proceso', ' Firma Aceptada'],
                info : [],
                imagesDir: imagesDir



            }
        }
    }
</script>
