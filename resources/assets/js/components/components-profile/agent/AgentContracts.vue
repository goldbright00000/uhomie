<template>
    <div class="agent-contracts">
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
                                <span>Agente</span>
                            </div>
                           <div class="column is-4">
                               <div class="control">
                                   <input class="input" type="text" :value="info.agent">
                               </div>
                            </div>
                            <div class="column is-2">
                                <p :class="'contract-stt-'+info.agent_stt">{{ stt_title[info.agent_stt] }}</p>
                            </div>
                            <div class="column is-1">
                                <img :src="imagesDir+'/doc-eye.png'"/>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <span>Agente</span>
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
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;


    export default {
        components: {

        },
        name: 'AgentContracts',
        props: {},
        data() {
            return {

                stt_title : ['Firma Cancelada','Firma en Proceso',' Firma Aceptada'],
                info : {
                    agent : 'Jennifer Lopez',
                    agent_stt : 0,
                    postulate : 'Maxime Leumiere',
                    postulate_stt : 1,
                    aval : 'Leon Marquez',
                    aval_stt : 2,
                    holding : 'Apartamento en los Condes',
                    rentprice : '33 523 00.00',
                    tramount : '30 523 00.00',
                },
                imagesDir: imagesDir



            }
        }
    }
</script>
