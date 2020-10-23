<template>
    <tr class="agent-holdings">
        <td style="vertical-align:middle">
            <figure class="image is-24x24" v-if="info.verified == 1" title="Propiedad Verificada">
                <img :src="imagesDir+'/explore-details/ico_propiedad_verificada.png'">
            </figure>
        </td>
        <td style="vertical-align:middle">
            <b>{{info.id}}</b>
        </td>
        <td style="vertical-align:middle">
            <div :class="status.name">
                <div class="has-text-centered">
                    <div><b>{{info.is_project == 1 ? 'Venta' : 'Arriendo'}}</b></div>
                    <div><b>{{propertyType.text}}</b></div>
                </div>
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <button class="button is-small" aria-haspopup="true" aria-controls="dropdown-menu2">
                            <span>{{status.text}}</span>
                            <span class="icon is-small">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu2" role="menu">
                        <div class="dropdown-content" v-if="info.status == 0">
                            <a @click="operation(1)" class="dropdown-item">
                                Pausar
                            </a>
                            <a @click="operation(3)" class="dropdown-item">
                                Arrendada
                            </a>
                            <a @click="operation(4)" class="dropdown-item">
                                Eliminar
                            </a>
                        </div>
                        <div class="dropdown-content" v-if="info.status == 1">
                            <a @click="operation(2)" class="dropdown-item">
                                Publicar
                            </a>
                            <a @click="operation(3)" class="dropdown-item">
                                Arrendada
                            </a>
                            <a href="#" class="dropdown-item">
                                Eliminar
                            </a>
                        </div>
                        <div class="dropdown-content" v-if="info.status == 2">
                            <a @click="operation(3)" class="dropdown-item">
                                Publicar
                            </a>
                            <a @click="operation(4)" class="dropdown-item">
                                Eliminar
                            </a>
                        </div>
                        <div class="dropdown-content" v-if="info.status == 4">
                            <a :href="info.redirect + info.id" class="dropdown-item">
                                Completar Registro
                            </a>
                            <a @click="operation(4)" class="dropdown-item">
                                Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="columns is-gapless is-vcentered">
                <div class="column" style="margin-right: 5px">
                    <div class="container-crop">
                        <img class="crop" :src="info.image"/>
                    </div>
                </div>
                <div class="column" style="margin-right: 5px">
                    <span>{{info.address}}</span>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div v-if="info.is_project == 1">
                <div>Desde <span><b>UF {{number_format(info.rent)}}</b></span></div>
                <div>Hasta <span><b>UF {{number_format(info.rent_up)}}</b></span></div>
            </div>
            <div v-else>
                <p>
                    <b>$ {{number_format(info.rent)}} CPL</b>
                    <span v-if="info.type_stay == 'LONG_STAY'"> Mensual</span>
                    <span v-else> Diarios</span>
                </p>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="columns" v-if="propertyType.type != 2">
                <div class="column" v-if="propertyType.type == 0">
                    <span class="tooltip" data-tooltip="Habitaciones">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_habitacion.png'">
                            </div>
                            <div class="media-content">
                                {{ info.bedrooms }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.type == 1">
                    <span class="tooltip" data-tooltip="Habitaciones q se pueden habilitar">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_habitacion.png'">
                            </div>
                            <div class="media-content">
                                {{ info.bedrooms }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.type == 1 || propertyType.type == 0">
                    <span class="tooltip" data-tooltip="Baños">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_banera.png'">
                            </div>
                            <div class="media-content">
                                {{ info.bathrooms }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.type == 1 || propertyType.type == 0">
                    <span class="tooltip" data-tooltip="Garage">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_garage.png'">
                            </div>
                            <div class="media-content">
                                {{ info.private_parking }}
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="columns">
                <div class="column" v-if="propertyType.type == 0">
                    <span class="tooltip" data-tooltip="Mascotas">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono-mascota.png'">
                            </div>
                            <div class="media-content is-uppercase">
                                {{ info.pet_preference }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.type == 1">
                    <span class="tooltip" data-tooltip="Salas de Reunion">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_trabajo.png'">
                            </div>
                            <div class="media-content is-uppercase">
                                {{ info.meeting_room }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.type == 1 || propertyType.type == 0">
                    <span class="tooltip" data-tooltip="Amoblado">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_amoblado.png'">
                            </div>
                            <div class="media-content">
                                {{ info.furnished == 0 ? 'No' : 'Si' }}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column">
                    <span class="tooltip" data-tooltip="Metros">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_superficie.png'">
                            </div>
                            <div class="media-content">
                                {{ info.meters }}m2
                            </div>
                        </div>
                    </span>
                </div>
                <div class="column" v-if="propertyType.text == 'Estacionamiento'">
                    <span class="tooltip" data-tooltip="Garage"> 
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <img :src="imagesDir+'/icono_garage.png'">
                            </div>
                            <div class="media-content">
                                {{ info.private_parking }}
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="columns is-gapless is-centered">
                <div class="column is-half">
                    <span class="tooltip" data-tooltip="Visualizaciones">
                        <div class="media">
                            <div class="media-left" style="margin-right: 5px">
                                <p class="image is-24x24">
                                    <img :src="imagesDir+'/doc-eye.png'">
                                </p>
                            </div>
                            <div class="media-content">
                                {{ info.views }}
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="columns is-gapless is-centered">
                <div class="column is-half has-text-centered">
                    <span class="tooltip" data-tooltip="Postulaciones">
                        <div class="media">
                            <div class="media-left" style="margin-right: 0px">
                                <p class="image is-24x24">
                                    <i class="fa fa-users" style="color:#08b7ff"></i>
                                </p>
                            </div>
                            <div class="media-content">{{ info.postulation }}</div>
                        </div>
                    </span>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <div>
                <div v-if="info.status != 4" class="holding-edit">
                    <router-link :to="'/holdings/edit/' + info.id">
                        <div class="save_ico">
                            <span class="tooltip is-tooltip-left" data-tooltip="Editar">
                                <img v-if="info.status <= 1 || info.available == 0 && info.status == 2" :src="imagesDir+'/box-edit.png'" style="width:32px; height:32px;" @click="$emit('edit',info.id)">
                            </span>
                        </div>
                    </router-link>
                    <div style="margin-left: 5px">
                        <span class="tooltip is-tooltip-left" data-tooltip="Postulaciones">
                            <!--<img v-if="verify_profile" @click="$router.push('/holdings/postulates/' + info.id)" :src="imagesDir+'/postul-inicontract.png'" style="cursor: pointer;">-->
                            <img v-if="true" @click="$router.push('/holdings/postulates/' + info.id)" :src="imagesDir+'/postul-inicontract.png'" style="cursor: pointer;">
                            <img v-else @click="verifyIdentity" :src="imagesDir+'/postul-inicontract.png'" style="cursor: pointer;">
                        </span>
                    </div>
                    <div style="margin-left: 5px">
                        <span class="tooltip is-tooltip-left" data-tooltip="Agenda">
                            <img @click="$router.push('/holdings/schedules/' + info.id)" :src="imagesDir+'/icono-calendario-azul.png'" style="cursor: pointer;">
                        </span>
                    </div>
                    <div style="margin-left: 5px" v-if="info.verified != 1 && info.is_project == 0">
                        <span class="tooltip is-tooltip-left" data-tooltip="Verificar">
                            <img :src="imagesDir+'/icons/seguridad.png'" @click="$emit('property', {'id': info.id})" style="cursor: pointer;">
                        </span>
                    </div>
                </div>
                <div v-else>
                    <div style="margin-left: 5px">
                        <span class="tooltip is-tooltip-left" data-tooltip="Completar Registro">
                            <a :href="info.redirect + info.id"  style="color:#08b7ff">
                                <i class="fa fa-edit fa-2x"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <i class="fa fa-bar-chart fa-lg" @click="modalChart()" aria-hidden="true" style="cursor: pointer"></i>
            </div>
        </td>
    </tr>
</template>
<script>
    const imagesDir = document.getElementById('images-dir').value;

    import HoldingIcons from '../components/components-profile/common/HoldingIcons';

    export default {
        name: 'Holding',
        components: {
            HoldingIcons,
        },
        props: {
            info: Object,
            verify_profile: {
                type: Boolean,
                default: false
            },
            agent: {
                type: Boolean,
                default: false
            },
            property_type: Array
        },
        data: function () {
            return {
               imagesDir: imagesDir
            }
        },
        computed: {
            certificade: function(){
                return this.info.files.filter(property => property.name == 'property_certificate')[0];
            },
            propertyType: function(){
                var type = this.property_type.filter(type => type.id == this.info.property_type_id )[0];
                if(type.id == 1 || type.id == 2 || type.id == 3){
                    type.type = 0;
                }
                if(type.id == 4 || type.id == 5){
                    type.type = 1;
                }
                if(type.id == 6 || type.id == 7 || type.id == 8){
                    type.type = 2;
                }
                return type;
            },
            status: function() {
                var status = {}
                switch (this.info.status) {
                    case '0':
                        status.text = 'Publicado'
                        status.name = 'publicado'
                        break;
                    case '1':
                        status.text = 'En pausa'
                        status.name = 'en-pausa'
                        break;
                    case '2':
                        status.text = 'Arrendado'
                        status.name = 'arrendado'
                        break;
                    case '3':
                        status.text = 'Cancelado'
                        status.name = 'cancelado'
                        break;
                    case '4':
                        status.text = 'Incompleto'
                        status.name = 'cancelado'
                        break;
                }

                return status
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
            },
            verify(){
                this.$emit('property', {'id': this.info.id});
                //this.verify = true;
            },
            verifyIdentity(){
                this.$emit('verifyIdentity', {'value': true});
            },
            operation(value){
                switch (value) {
                    case 1:
                        this.loading = true;
                        var id = this.info.id;
                        axios
                            .get(`owner/pause-property/${id}`)
                            .then(resp => {
                                this.info.status = '1';
                                this.info.statusname = 'en-pausa';
                                this.loading = false;
                                toastr['success']('Se ha cambiado el estatus exitosamente');
                            })
                            .catch(() => {
                                toastr['error'](err);
                            });
                        break;
                    case 2:
                        this.loading = true;
                        var id = this.info.id;
                        axios
                            .get(`owner/publish-property/${id}`)
                            .then(resp => {
                                this.info.status = '0';
                                this.info.statusname = 'publicado';
                                this.loading = false;
                                toastr['success']('Se ha cambiado el estatus exitosamente');
                            })
                            .catch(() => {
                                toastr['error'](err);
                            });
                        break;
                    case 3:
                        this.loading = true;
                        var id = this.info.id;
                        axios
                            .get(`owner/leased-property/${id}`)
                            .then(resp => {
                                this.info.status = String(resp.data.info.status);
                                this.loading = false;
                                toastr['success']('Se ha cambiado el estatus exitosamente');
                            })
                            .catch(() => {
                                toastr['error'](err);
                            });
                        break;
                    case 4:
                        this.$emit('delProperty', {'value': true, 'info': this.info});
                        break;
                
                    default:
                        break;
                }
            },
            modalChart(){
                this.$emit('chartProperty', {'value': true, 'info': this.info});
            }
        },
        filters: {
            truncate: function(value, length) {
            return value.length > length
                ? value.substr(0, length - 1) + "..."
                : value;
            },
            money: function(value) {
            return window.globals.filters.moneyFormat(value, 0);
            }
        },
        mounted() {
            //console.log(this.certificade)
        },
    }
</script>

<style>
.price.select::after {
    content: none !important;
}
.en-pausa {
    border-bottom: 3px solid hsl(48, 100%, 67%);
}

.publicado {
    border-bottom: 3px solid hsl(141, 71%, 48%);
}

.arrendado {
    border-bottom: 3px solid hsl(204, 86%, 53%);
}

.cancelado
{
    border-bottom: 3px solid hsl(348, 100%, 61%);
}

.container-crop {
    width: 95px;
    height: 95px;
    overflow: hidden;
    margin: 10px;
    position: relative;
}
.container-crop > .crop {
    position:absolute;
    left: -100%;
    right: -100%;
    top: -100%;
    bottom: -100%;
    margin: auto;
    min-height: 100%;
    min-width: 100%;
    max-width: 150%;
}


</style>
