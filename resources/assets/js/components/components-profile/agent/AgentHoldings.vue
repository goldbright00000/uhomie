<template>
    <div class="agent-holding">
        <!-- FILTROS -->
        <div class="columns">
            <div class="column is-4">
            
            </div>
            <div class="column is-8">                
                <a v-if="proyects_register" href="/users/agent/r/third-step" class="button is-outlined is-primary at-right">Registrar Nuevo Proyecto +</a>
            </div>
        </div>
        <!-- DETALLES -->
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-12">
                <div class="column is-full" style="position:relative;width:100%;margin:auto;overflow:hidden;">
                    <div style="width:100%; overflow:auto;">
                        <table class="table is-fullwidth is-size-7" style="width: 100%; overflow-x: auto;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Estado</th>
                                    <th>Proyecto</th>
                                    <th>Precio de Proyecto</th>
                                    <th>Detalles</th>
                                    <th>Vistas / Postulaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody v-if="info.projects.length > 0">
                                <holding v-for="item in info.projects" v-if="item.id != false" :key="item.id" @edit="$emit('edit_holding',$event)" v-bind:info="item" v-bind:property_type="filters.property_type.options" v-bind:verify_profile="verify_profile" @verifyIdentity="verifyIdentity" v-bind:agent="true" @delProperty="delModalProperty" @chartProperty="modalChartProperty"></holding>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7" class="has-text-centered">No hay proyectos registrados</td>
                                </tr>
                            </tbody>
                        </table>
                        <verify-identity v-if="verify_identity == true" v-bind:documents="info.documents" @verifyIdentity="verifyIdentity"></verify-identity>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="modalDelete == true" class="modal is-active">
            <div class="modal-background" @click="closeModalDeleteProperty()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Eliminar</p>
                    <button class="delete" aria-label="close" @click="closeModalDeleteProperty()"></button>
                </header>
                <section class="modal-card-body">
                    <h2>¿Desea eliminar la propiedad ID {{property.id}}?</h2>
                    <br>
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-96x96">
                                <img :src="property.image">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>Nombre:</strong> {{property.name}}
                                    <br>
                                    <strong>Descripción:</strong> {{property.description}}
                                    <br>
                                    <strong>Ubicación:</strong> {{property.address}}
                                </p>
                            </div>
                        </div>
                    </article>
                    <article class="message is-info is-small">
                        <div class="message-body">
                            Le recomendamos revisar bien la información de la propiedad antes de proceder a <strong>eliminar</strong>.
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-primary" @click="delProperty(property.id)">Eliminar</button>
                    <button class="button" @click="closeModalDeleteProperty()">Volver</button>
                </footer>
            </div>
        </div>

        <chart-property v-bind:info="property" v-bind:modalChart="modalChart" @closeModalChart="closeModalChart"></chart-property>

    </div>    
</template>
<script>
    import Holding from '../../Holding.vue';
    import AgentCore from './AgentCore';
    import VerifyIdentity from '../common/VerifyIdentity.vue';
    import chartProperty from '../common/ChartProperty.vue';
    const imagesDir = document.getElementById('images-dir').value;
    export default {
        extends: AgentCore,
        components: {
            Holding,
            VerifyIdentity,
            chartProperty
        },
        name: 'AgentHoldings',
        props: {
            
        },
        computed: {
            verify_profile() {
                if(this.info.documents.id_front.verified == 1 && this.info.documents.id_front.verified_ocr == 1){
                    if(this.info.documents.id_back.verified == 1 && this.info.documents.id_back.verified_ocr == 1){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            },
            proyects_register(){
                if(this.info.projects.length <= JSON.parse(this.info.membership_data.features).montly_publications || JSON.parse(this.info.membership_data.features).montly_publications == '-1'){
                    return true;
                } else {
                    return false;
                }
            }
        },      
        data() {
            return {               
                imagesDir: imagesDir,
                verify_identity: {
                    type: Boolean,
                    default: false
                },
                modalDelete: false,
                modalChart: false
            }
        },
        methods: {
            verifyIdentity(value){
                this.verify_identity = value.value;
            },
            delProperty(value){
                this.loading = true;
                var id = value;
                axios
                    .get(`owner/delete-property/${id}`)
                    .then(resp => {
                        for (var i = 0; i < this.$parent.info.projects.length; i++) {
                            if (this.$parent.info.projects[i].id == resp.data.id) {
                            this.$parent.info.projects.splice(i, 1);
                            break;
                            }
                        }
                        toastr.success('Se ha eliminado la propiedad');
                        this.modalDelete = false;
                    })
                    .catch((err) => {
                        toastr['error'](err);
                        this.modalDelete = false;
                    });
            },
            delModalProperty(value){
                console.log('Aqui entro');
                this.property = value.info;
                this.modalDelete = true;
            },
            closeModalDeleteProperty(){
                this.modalDelete = false;
            },
            modalChartProperty(value){
                console.log(value);
                this.modalChart = true;
                this.property = value.info;
            },
            closeModalChart(value){
                this.modalChart = value;
            }
        },
        
    }
</script>

