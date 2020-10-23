<template>
    <div class="tenant-postulations">
      <div class="columns" style="padding-bottom: 20px; border-bottom: 1px solid #ccc;">
            <div class="column is-8">
                <div class="columns">
                    <div class="column is-12 line-down">
                        <span>Visitas agendadas de Propiedad Id: {{$route.params.idProperty}}</span>
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
                <div class="has-text-right">
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
            <div class="column is-full">
                <div class="field">
                    <div class="label-field has-text-right">
                        <span>Filtar por fecha: </span>
                    </div>
                    <div class="control">
                        <v-date-picker
                            v-model='schedule_control'
                            :available-dates="available"
                            :input-props='{
                                placeholder: "Seleccione una fecha",
                                readonly: true,
                                class: "input date",
                                style:"border-radius: 0; border-bottom: 1px solid #0a0a0a;"
                            }'
                            />
                    </div>
                    <a class="button is-outlined is-primary" @click="schedule_control = null">Limpiar</a>
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
                            <td>Visitante / Arrendatario</td>
                            <td class="has-text-centered">
                                Fecha
                            </td>
                            <td class="has-text-centered">
                                Rango
                            </td>
                            <td class="has-text-centered">
                                Agendar
                            </td>
                        </tr>
                    </thead>
                    <tbody v-if="visitors && visitors.length > 0">
                        <visitor v-for="item in visitors" :key="item.id" v-bind:info="item"></visitor>
                    </tbody>
                    <tbody v-else>
                        <td colspan="5" class="has-text-centered">
                            No hay visitas agendadas para esta propiedad
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
        <UpgradeMembership v-show="toShowMembership" v-bind:toShowMembership="toShowMembership"></UpgradeMembership>
    </div>
</template>

<script>
    import Visitor from '../../Visitor';
    import OwnerCore from './OwnerCore';
    import UpgradeMembership from '../common/UpgradeMembership';
    const imagesDir = document.getElementById('images-dir').value;

    export default {
        extends: OwnerCore,
        components: {
            Visitor,
            UpgradeMembership
        },
        name: 'OwnerSchedules',
        props: {
            prop: Object,
        },
        mounted () {

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
            },
            visitors() {
                if(this.schedule_control == null){
                    return this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0].visitors
                } else {
                    var visitors = this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0].visitors
                    return visitors.filter(visit => visit.schedule_date == moment(this.schedule_control).format('YYYY-MM-DD'));
                }
            },
            available(){
                var available = [];
                var visitors = this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0].visitors

                visitors.forEach(element => {
                    available.push({start: element.schedule_date, end: element.schedule_date});
                });

                return available;
            }

        },
        data() {
            return {
                schedule_control: null,
                imagesDir: imagesDir,
                postulations: [],
                property: this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0],
                toShowMembership: false,
            }
        },
        methods: {
            number_format(amount, decimals) {

                amount += ''; // por si pasan un numero en vez de un string
                amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

                decimals = decimals || 0; // por si la variable no fue fue pasada

                // si no es un numero o es igual a cero retorno el mismo cero
                if (isNaN(amount) || amount === 0) 
                    return parseFloat(0).toFixed(decimals);

                // si es mayor o menor que cero retorno el valor formateado como numero
                amount = '' + amount.toFixed(decimals);

                var amount_parts = amount.split(','),
                    regexp = /(\d+)(\d{3})/;

                while (regexp.test(amount_parts[0]))
                    amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');

                return amount_parts.join(',');
            }
        }
    }
</script>