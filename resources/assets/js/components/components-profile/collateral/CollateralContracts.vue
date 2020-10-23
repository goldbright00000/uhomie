<template>
    <div class="tenant-contracts">
        <!--
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Contrato de arriendo</span>

            </div>
        </div>

        <div class="columns">
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Aval</span>
                            </div>
                           <div class="column is-4">
                               <div class="control">
                                   <input class="input" type="text" :value="info.collateral">
                               </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+info.collateral_stt">{{ stt_title[info.collateral_stt] }}</p>
                            </div>
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Aval</span>
                            </div>
                           <div class="column is-4">
                               <div class="control">
                                   <input class="input" type="text" :value="info.postulate">
                               </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+info.postulate_stt">{{ stt_title[info.postulate_stt] }}</p>
                            </div>
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Aval</span>
                            </div>
                           <div class="column is-4">
                               <div class="control">
                                   <input class="input" type="text" :value="info.aval">
                               </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+info.aval_stt">{{ stt_title[info.aval_stt] }}</p>
                            </div>
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Propiedad</span>
                            </div>
                           <div class="column is-4">
                               <div class="control">
                                   <input class="input" type="text" :value="info.holding">
                               </div>
                            </div>
                            <div class="column is-2">

                            </div>
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Precio de Arriendo</span>
                            </div>
                           <div class="column is-4">
                               <div class="control has-icons-left has-icons-right">
                                   <input class="input" type="text" :value="info.rentprice" >
                                   <span class="icon is-small is-left">
                                    $
                                </span>
                                   <span class="icon is-small is-right">
                                    CLP
                                </span>
                               </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Monto de la transacci&oacute;n</span>
                            </div>
                           <div class="column is-4">
                               <div class="control has-icons-left has-icons-right">
                                   <input class="input" type="text" :value="info.tramount" >
                                   <span class="icon is-small is-left">
                                    $
                                </span>
                                   <span class="icon is-small is-right">
                                    CLP
                                </span>
                               </div>
                            </div>
                          
                        </div>
                    </div>
                </div>

            </div>
        </div>
        -->
        <div class="columns" style="padding-bottom: 20px;" v-if="contracts.length == 0">
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
        <div class="box" v-for="(contract, index) in contracts" :key="index">
            <div v-if="!contract.data.signature_request_id_hs">
                <h6 class="title is-7">Verificación de identidad para contrato de arriendo de la propiedad: {{contract.property.name}}</h6>
                <br>
                <ModalVideoRecording :postul_id="contract.postulacion.id"></ModalVideoRecording>
            </div>
            <div v-if="contract.data.signature_request_id_hs">
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
                <div class="columns" v-if="contract.collateral_name">
                    <div class="column is-12">
                        <div class="columns" >
                            <div class="column is-1">
                                <div class="image media-left is-48x48" >
                                    <img class="is-rounded" :src="contract.collateral_avatar">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p class="subtitle is-5">Aval</p>
                            </div>
                            <div class="column is-4">
                                <div class="control">
                                    <input class="input" type="text" readonly :value="contract.collateral_name">
                                </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+contract.collateral_sign_status">{{ stt_title[contract.collateral_sign_status] }}</p>
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
    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    import ModalVideoRecording from '../../ModalVideoRecording.vue';

    export default {
        components: {
            ModalVideoRecording
        },
        name: 'CollateralContracts',
        
        data() {
            return {

                stt_title : ['Firma Cancelada','Firma en Proceso',' Firma Aceptada'],
                info : {
                    collateral : 'Jennifer Lopez',
                    collateral_stt : 0,
                    postulate : 'Maxime Leumiere',
                    postulate_stt : 1,
                    aval : 'Leon Marquez',
                    aval_stt : 2,
                    holding : 'Apartamento en los Condes',
                    rentprice : '33 523 00.00',
                    tramount : '30 523 00.00',
                },
                imagesDir: imagesDir,
                contracts: [],



            }
        },
        mounted() {
            console.log('eiei');
            console.log(this.$attrs['user-id']);
            
            axios.get('/users/profile/collateral/' + this.$attrs['user-id'] +'/contracts', {
                params: {},
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
                
            }).then(
                response => {
                    this.contracts = response.data;
                    console.log(this.contracts.length);
                }
            ).catch(function (error) {
                console.log(error);
            });
        }
    }
</script>
