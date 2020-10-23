<template>
    <div>
        <div class="tenant-contract" v-if="contract">
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
                                    <span>Arrendatario</span>
                                </div>
                            <div class="column is-4">
                                <div class="control">
                                    <input class="input" type="text" :value="info.tenant">
                                </div>
                                </div>
                                <div class="column is-2">
                                    <p :class="'contract-stt-'+info.tenant_stt">{{ stt_title[info.tenant_stt] }}</p>
                                </div>
                                <div class="column is-1">
                                    <img :src="imagesDir+'/doc-eye.png'"/>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="columns">
                                <div class="column is-4">
                                    <span>Arrendador</span>
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


        </div>
        <div class="tenant-contracts" v-if="!contract">
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
    </div>
</template>

<script>
    import GetingData from '../common/GetingData';

    const imagesDir = document.getElementById('images-dir').value;


    export default {
        extends : GetingData,
        components: {

        },
        name: 'TenantContracts',
        props: {
            contracts_id: []
        },
        created() {
            this.getData(4)
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
