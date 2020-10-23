<template>
    <div class="tenant-profile">
        <div class="columns">
            <div class="column is-8">
                <div class="columns">
                    <div class="column is-12 line-down">
                        <span>Perfil Arrendatario</span>
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
                                    <b>Precio mensual de arriendo: </b><span>$ {{ money(property.rent) }} CPL</span>
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
                    <router-link :to="'/holdings/postulates/' + $route.params.idProperty" class="button is-outlined is-primary">
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
        <div class="columns is-gapless is-vcentered">
            <div class="column">
                <div class="columns is-vcentered">
                    <div class="column is-5">
                        <div class="columns is-vcentered">
                            <div class="column is-one-third">
                                <div class="avatar image">
                                    <img class="is-rounded" :src="postulation.tenant.photo || imagesDir + '/roles/avatar-uhomie.png'" style="width:75px; height:75px; border-radius: 50%;"/>
                                </div>
                            </div>
                            <div class="column">
                                <div>
                                    <b style="color: #ffd900">{{ postulation.tenant.firstname + ' ' + postulation.tenant.lastname }}</b>
                                </div>
                                <div>
                                    <b>Arrendatario</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <img :src="imagesDir+'/logo_'+postulation.tenant.logo_membership+'.png'">
                    </div>
                    <div class="column is-4 is-size-7">
                        <a target="_blank" href="https://uhomiehelp.zendesk.com/hc/es-419/articles/360029656251-Puntaje-Scoring-UHOMIE-Zonas-de-Clasificaci%C3%B3n-">
                            <article class="media">
                                <figure class="media-left">
                                    <div class="profiles">
                                        <div class="scoring">
                                            <div :class="postulation.tenant.logscoring + ' tooltip'" :data-tooltip="scoring_message.clasification"  style="display: inline;">
                                                <p><b>{{ postulation.tenant.score }}</b></p>
                                                <img :src="imagesDir+'/posit.png'"/>
                                            </div>
                                        </div>
                                    </div>
                                </figure>
                                <div class="media-content">
                                    <div class="has-text-centered">
                                        <div>{{scoring_message.clasification}}</div>
                                        <div>{{scoring_message.recommendation}}</div>
                                        <div><b>{{scoring_message.risk}}</b> <i class="fa fa-info-circle"></i></div>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{postulation.tenant.age}} Años, {{getCivilStatus(postulation.tenant.civil_status_id)}}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{ getCountry(postulation.tenant.country_id) }}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{ postulation.tenant.document_type }} {{ postulation.tenant.document_number }}</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Empleo</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{ postulation.tenant.company }}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{ postulation.tenant.position }}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">{{ postulation.tenant.job_type }}</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-1">
                <span class="is-italic has-text-weight-bold">De</span>
            </div>
            <div class="column is-3">
                <span class="with-line is-size-7">{{ postulation.tenant.worked_from_date }}</span>
            </div>
            <div class="column is-1">
                <span class="is-italic has-text-weight-bold">Hasta</span>
            </div>
            <div class="column is-3">
                <span class="with-line is-size-7" v-if="postulation.tenant.to_date == null">La Actualidad</span>
                <span class="with-line is-size-7" v-if="postulation.tenant.to_date != null">{{postulation.tenant.to_date}}</span>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Indicadores Financieros</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-quarter">
                <span class="is-size-7">Última liquidación Sueldo</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">$ {{ money(postulation.tenant.amount) }} CPL</span>
            </div>
            <div class="column is-one-quarter">
                <span class="is-size-7">Ingresos Adicionales</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">$ {{ money(postulation.tenant.other_income_amount) }} CPL</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-quarter">
                <span class="is-size-7">Liquidez Total</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">$ {{money(parseInt(postulation.tenant.amount) + parseInt(postulation.tenant.other_income_amount))}} CPL</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Presupuesto</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-quarter">
                <span class="is-size-7">Limite Mensual de Arriendo</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">$ {{money(postulation.tenant.expenses_limit)}} CPL</span>
            </div>
            <div class="column is-one-quarter">
                <span class="is-size-7">Limite Mensual de Gastos</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">$ {{money(postulation.tenant.common_expenses_limit)}} CPL</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-quarter">
                <span class="is-size-7">¿Cuantos Meses de Garantía?</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">{{getMonth(postulation.tenant.warranty_months_quantity)}}</span>
            </div>
            <div class="column is-one-quarter">
                <span class="is-size-7">Limite Mensual de Gastos</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">{{getMonth(postulation.tenant.months_advance_quantity)}}</span>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Tiempos de Arriendo</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-quarter">
                <span class="is-size-7">Día de mudanza</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">{{postulation.tenant.move_date}}</span>
            </div>
            <div class="column is-one-quarter">
                <span class="is-size-7">Arrienda por:</span>
            </div>
            <div class="column is-one-quarter">
                <span class="with-line is-size-7">{{getMonth(postulation.tenant.tenanting_months_quantity)}}</span>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Tipo de Propiedad</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-one-tree">
                <span class="with-line is-size-7">Propiedad: {{getPropType(postulation.tenant.property_type)}}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">Estado: {{getPropCond(postulation.tenant.property_condition)}}</span>
            </div>
            <div class="column is-one-tree">
                <span class="with-line is-size-7">Para: {{getPropFor(postulation.tenant.property_for)}}</span>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <div class="field">
                    <div class="label-field">
                        <span>¿Amueblado?</span>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="smoking_allowed" :checked="postulation.tenant.furnished" v-model="postulation.tenant.furnished" value="1" disabled="true">
                            Si
                        </label>
                        <label class="radio">
                            <input type="radio" name="smoking_allowed" :checked="!postulation.tenant.furnished" v-model="postulation.tenant.furnished" value="0" disabled="true">
                            No
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <div class="field">
                    <div class="label-field">
                        <span>¿Tienes Mascotas?</span>
                    </div>
                    <div class="control">
                        <label class="radio">
                        <input type="radio" name="pet_preference" value="dog" :checked="postulation.tenant.pet_preference=='dog'" v-model="postulation.tenant.pet_preference" disabled="true">
                            <img :src="imagesDir+'/icono-perro.png'">
                        </label>
                        <label class="radio">
                            <input type="radio" name="pet_preference" value="cat" :checked="postulation.tenant.pet_preference=='cat'" v-model="postulation.tenant.pet_preference" disabled="true">
                            <img :src="imagesDir+'/icono-gato.png'">
                        </label>
                        <label class="radio">
                            <input type="radio" name="pet_preference" value="other" :checked="postulation.tenant.pet_preference=='other'" v-model="postulation.tenant.pet_preference" disabled="true">
                            Otro
                        </label>
                        <label class="radio">
                            <input type="radio" name="pet_preference" value="no" :checked="postulation.tenant.pet_preference=='no'" v-model="postulation.tenant.pet_preference" disabled="true">
                            No
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <div class="field">
                    <div class="label-field">
                        <span>¿Fuman?</span>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="smoking_allowed" value="true" :checked="postulation.tenant.smoking_allowed" v-model="postulation.tenant.smoking_allowed" disabled="true">
                            Si
                        </label>
                        <label class="radio">
                            <input type="radio" name="smoking_allowed" value="0" :checked="!info.smoking_allowed" v-model="info.smoking_allowed" disabled="true">
                            No
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Servicios de la unidad que desea</span>
                <square-list @change="changeAmenities($event)" :items="filters.property_amenities" :values="postulation.tenant.amenities"></square-list>
            </div>
            
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <span class="is-italic has-text-weight-bold">Servicios de condominio que desea</span>
                <square-list @change="changeAmenities($event)" :items="filters.common_amenities" :values="postulation.tenant.amenities"></square-list>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-full">
                <div class="columns">
                    <div class="column">
                        <span class="is-italic has-text-weight-bold">Documentos del Postulante</span>
                        </div>
                </div>
                <div class="columns">
                    <div class="column" style="padding: 20px">
                        <DocTenantView v-for="item in postulation.tenant.files" :key="item.id" v-bind:info="item" @document="documentInfo"></DocTenantView>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div v-if="postulation.tenant.collateral != false">
            <div class="columns">
                <div class="column is-full">
                    <span class="is-italic has-text-weight-bold">Datos de Aval</span>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{ postulation.tenant.collateral.firstname + ' ' + postulation.tenant.collateral.lastname }}</span>
                </div>
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{ getCountry(postulation.tenant.collateral.country_id) }}</span>
                </div>
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{ postulation.tenant.collateral.document_type }}: {{ postulation.tenant.collateral.document_number }}</span>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{postulation.tenant.collateral.age}} Años</span>
                </div>
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{postulation.tenant.collateral.email}}</span>
                </div>
                <div class="column is-one-tree">
                    <span class="with-line is-size-7">{{ postulation.tenant.collateral.address }}</span>
                </div>
            </div>
            <hr>
            <div class="columns">
                <div class="column is-full">
                    <div class="columns">
                        <div class="column">
                            <span class="is-italic has-text-weight-bold">Documentos del Aval</span>
                            </div>
                    </div>
                    <div class="columns">
                        <div class="column" style="padding: 20px">
                            <DocTenantView v-for="item in postulation.tenant.collateral.files" :key="item.id" v-bind:info="item" @document="documentInfo"></DocTenantView>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div v-else>
            <div class="columns">
                <div class="column is-full">
                    <span class="is-italic has-text-weight-bold">Datos de Aval</span>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    Este arrendatario no posee aval
                </div>
            </div>
            <hr>
        </div>
        <div v-if="document_view" class="modal is-active">
            <div class="modal-background" @click="closeModalDocument()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{document_info.document_name}}</p>
                    <button class="delete" aria-label="close" @click="closeModalDocument()"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <iframe class="column" :src="'https://docs.google.com/viewer?url='+document_info.tmps3+'&embedded=true'" style="height:600px;" frameborder="0"></iframe>
                        <div v-if="info.membership != 'premium'" class="emblema">
                            <img style="margin-left: 10px" :src="imagesDir + '/roles/avatar-uhomie.png'">
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" @click="closeModalDocument()">Volver</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    const imagesDir = document.getElementById('images-dir').value;
    import ProfileInfo from '../common/ProfileInfo.vue';
    import ProfileInfo3 from '../common/ProfileInfo3.vue';
    import OwnerCore from './OwnerCore';
    import SquareList from '../../SquareList';
    import DocTenantView from '../common/DocTenantView.vue';

    export default {
        extends: OwnerCore,
        components: {
            ProfileInfo,
            ProfileInfo3,
            SquareList,
            DocTenantView
        },
        name: 'OwnerTenantProfile',
        props: {
            
        },
        data() {
            return {
                imagesDir: imagesDir,
                property: [],
                postulation: [],
                document_view: false,
                document_info: {},
                loading: false
            }
        },
        mounted () {
            const self = this
            let prop = this.getProp(this.$route.params.idProperty);
            this.property = prop

            let postu = this.getPostulates(this.$route.params.idPostulate);
            this.postulation = postu

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
            getPostulates: function(w){
                var nfo=this.property.applications;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t];
                    }
                }
                return {};
            },
            getCivilStatus: function(w){
                var nfo=this.filters.civilstatus.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            getCountry: function(w){
                var nfo=this.filters.countries.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            money: function(value) {
                return window.globals.filters.moneyFormat(value, 0);
            },
            getMonth: function(w){
                var nfo=this.filters.months.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            getPropType: function(w){
                var nfo=this.filters.property_type.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            getPropCond: function(w){
                var nfo=this.filters.property_condition.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            getPropFor: function(w){
                var nfo=this.filters.property_for.options;
                for(var t in nfo){
                    if (nfo[t].id==w) {
                        return nfo[t].text;
                    }
                }
                return {};
            },
            changeAmenities: function(arr) {
                this.info.amenities = arr;
            },
            documentInfo(value){
                console.log(value);
                if(value.modal == true){
                    this.document_view = true;
                    this.document_info = value.document;
                    this.document_info.tmps3 = value.s3;
                }
            },
            closeModalDocument(){
                this.document_view = false;
            }
        },
        filters: {
            truncate: function(value, length) {
            return value.length > length
                ? value.substr(0, length - 1) + "..."
                : value;
            },
            
        },
        computed:{
            scoring_message(){
                var scoring_message = {}
                if(this.postulation.tenant.score >= 0 && this.postulation.tenant.score <= 300){
                    scoring_message = {
                        clasification: 'El puntaje es muy bajo',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo muy Alto'
                    };
                }
                if(this.postulation.tenant.score >= 301 && this.postulation.tenant.score <= 500){
                    scoring_message = {
                        clasification: 'El puntaje es bajo',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo muy alto'
                    };
                }
                if(this.postulation.tenant.score >= 501 && this.postulation.tenant.score <= 800){
                    scoring_message = {
                        clasification: 'El puntaje es medio',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo alto'
                    };
                }
                if(this.postulation.tenant.score >= 801 && this.postulation.tenant.score <= 900){
                    scoring_message = {
                        clasification: 'El puntaje es alto',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo medio'
                    };
                }
                if(this.postulation.tenant.score >= 901 && this.postulation.tenant.score <= 1100){
                    scoring_message = {
                        clasification: 'El puntaje es muy alto',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo bajo'
                    };
                    return ;
                }
                if(this.postulation.tenant.score >= 1101){
                    scoring_message = {
                        clasification: 'El puntaje es estelar',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo muy bajo.'
                    };
                }
                return scoring_message;
            },
            
        }
    }
</script>

<style>
.avatar {
    overflow: hidden;
    border: 1px solid #eee;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar img {
    height: 100%;
    padding: 5px;
}
.emblema {
    position:absolute;
    top: 65px;
    right:20px;
    width:100px;
    height:100px;
    background-color:white;
    -webkit-border-bottom-left-radius: 50%;
    -moz-border-radius-bottomleft: 50%;
    border-bottom-left-radius: 50%;
}
.profiles .scoring > div > p {
    position: absolute;
    font-size: 12px;
    width: 46px;
    margin-top: 20px;
    text-align: center;
    color: #fff;
    display: inline-block;
}
.scoring .basic img {
    background-color: #00B1D9;
}
.scoring .selects img {
    background-color: #947AFB;
}
.scoring .premium img {
    background-color: #F981F6;
}

</style>
