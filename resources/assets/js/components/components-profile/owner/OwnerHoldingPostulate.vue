<template>
    <div class="tenant-postulations">
      <div class="columns" style="padding-bottom: 20px; border-bottom: 1px solid #ccc;">
            <div class="column is-8">
                <div class="columns">
                    <div class="column is-12 line-down">
                        <span>Postulantes de Propiedad Id: {{$route.params.idProperty}}</span>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-12">
                        <article class="media">
                            <figure class="media-left">
                                <p class="image">
                                    <img :src="property.image" style="width:100px;height:75px;"/>
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content is-size-7">
                                    <div>
                                        <b>Titulo: </b><span>{{ property.name }}</span>
                                    </div>
                                    <div>
                                        <b>Descripción: </b><span>{{ property.description }}</span>
                                    </div>
                                    <div>
                                        <b>Precio mensual de arriendo: </b><span>$ {{ property.rent }} CPL</span>
                                    </div>
                                    <div>
                                        <b>Dirección: </b><span>{{ property.address }} {{ property.address_details }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            
            <div class="column is-4">
                <div class="is-full has-text-right">
                    <router-link to="/holdings" class="button is-outlined is-primary">
                        <span class="icon">
                            &lt;
                        </span>
                        <span>Volver</span>
                    </router-link>
                </div>
                <div style="padding: 10px 0px;" class="has-text-centered is-size-7">
                    <p>Fecha de Publicación</p>
                    <p>{{moment(property.created_at).locale('es').format('LLL')}}</p>
                </div>
            </div>
        </div>
        <!-- FILTROS -->

        <div class="columns">
            <div class="column is-4">


                <div class="media">
                    <span class="media-left">
                        <input type="checkbox" />
                        <i>Ordenar Por:</i>
                    </span>

                    <div class="select media-content">
                        <select class="" name="">
                            <option value="Fecha de Postulación">Fecha de Postulación</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>


        <!-- DETALLES -->
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-full">
                <table class="table is-size-7" style="width: 100%">
                    <thead>
                        <tr>
                            <td>Estado</td>
                            <td>Postulante / Arrendatario</td>
                            <td class="has-text-centered">
                                <img :src="imagesDir+'/postul-scoring.png'" class="tooltip" data-tooltip="Tooltip Text"/>
                            </td>
                            <td class="has-text-centered">
                                <div>Recomendación</div>
                                <div>UHOMIE</div>
                            </td>
                            <td class="has-text-centered">
                                <span class="tooltip" :data-tooltip="property.type_stay == 'LONG_STAY' ? 'Fecha de postulación' :'Dias Seleccionados/Postulados'"><img :src="imagesDir+'/postul-date.png'"/></span>
                            </td>
                            <td class="has-text-centered">
                                <img :src="imagesDir+'/postul-contact.png'"/>
                            </td>
                            <td class="has-text-centered">
                                <img :src="imagesDir+'/postul-inicontract.png'"/>
                            </td>
                        </tr>
                    </thead>
                    <tbody v-if="property.applications && property.applications.length > 0">
                        <postul v-for="item in property.applications" :key="item.id" v-bind:info="item" v-bind:tenant="true" :type_stay="property.type_stay"></postul>
                    </tbody>
                    <tbody v-else>
                        <td colspan="6" class="has-text-centered">
                            No hay postulaciones para esta propiedad
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
        <UpgradeMembership v-show="toShowMembership" v-bind:toShowMembership="toShowMembership"></UpgradeMembership>
    </div>
</template>

<script>
    import Postul from '../../Postul.vue';
    import OwnerCore from './OwnerCore';
    import UpgradeMembership from '../common/UpgradeMembership';
    const imagesDir = document.getElementById('images-dir').value;

    export default {
        extends: OwnerCore,
        components: {
            Postul,
            UpgradeMembership
        },
        name: 'OwnerHoldinPostulate',
        props: {
            prop: Object,
        },
        mounted () {
            /*const self = this
            
            let prop = this.getProp(this.$route.params.idProperty);

            this.property = prop*/

        },
        computed: {
            newProperty() {
                return !this.idProperty || this.idProperty === 0;
            },
            idProperty() {
                return this.$route.params.idProperty;
            },
            filters() {
                return this.$parent.filters;
            }
        },
        data() {
            return {
                imagesDir: imagesDir,
                postulations: [],
                property: this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0],
                toShowMembership: false
            }
        },
        methods: {
            getData: function() {

            },
            getProp: function(w){
                var nfo=this.info.properties;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t];
                    }
                }
                return {};
            },
        },
        mounted(){
            /** Bloqueo por direcionamiento */
            /*if(this.info.documents.id_front.verified != 1 && this.info.documents.id_front.verified_ocr != 1){
                this.$router.push('/holdings');
            }
            console.log(this.property);*/
        }
    }
</script>
