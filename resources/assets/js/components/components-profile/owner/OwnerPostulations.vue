<template>
    <div class="owner-postulations">

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
            <div class="column is-12">


                <div class="columns" style="border-bottom: 1px solid #eee; border-top: 1px solid #eee; ">
                    <div class="column is-2">
                        <span>Estado</span>
                    </div>
                    <div class="column is-2">
                        <span>Postulante / Arrendatario</span>
                    </div>
                    <div class="column">
                        <span>Propiedad</span>
                    </div>
                    <div class="column is-1">
                        <img :src="imagesDir+'/postul-scoring.png'" />
                    </div>
                    <div class="column is-2">
                        <img :src="imagesDir+'/postul-date.png'" />
                    </div>
                    <div class="column is-1">
                        <img :src="imagesDir+'/postul-contact.png'" />
                    </div>
                    <div class="column is-1">
                        <img :src="imagesDir+'/postul-inicontract.png'" />
                    </div>

                </div>

            </div>
        </div>
        <!-- DETALLES -->
        <div v-for="item in info" v-bind:key="item.id">
            <postul @tenantdetail="showTenantProfile($event)" :tenant="false" v-bind:info="item"></postul>
        </div>
    </div>
</template>

<script>

    import Postul from '../../Postul.vue';


    const imagesDir = document.getElementById('images-dir').value;

    import OwnerCore from './OwnerCore';
    export default {
        extends: OwnerCore,
        components: {
            Postul
        },
        name: 'OwnerPostulations',
        props: {},
        data() {
            return {
                info: [
                    {
                        id: 1,
                        status : 'aproved',
                        text : 'Aprobado',
                        tenant : {

                            avatar : imagesDir+ '/avatars/user01.jpg',
                            name : 'Lucas',
                            lastname : 'Phelps',
                        },
                        holding : {
                            price : '$ 1200 000.00 CLP',
                            address : 'Pisos en los condes',
                            city : 'Santiago de Chile',
                        },
                        membership : 'mem-select',
                        scoring : 900,
                        postul_date : {
                            ondate: '12/12/2018',
                        },
                        contacts : {
                            chat : true,
                            email : 'contact@example.com',
                            phone : '555-555-5555',
                        }
                    },
                    {
                        id: 2,
                        status : 'review',
                        text : 'En Revision',
                        tenant : {

                            avatar : imagesDir+ '/avatars/user02.jpg',
                            name : 'Raul',
                            lastname : 'Recio',
                        },
                        holding : {
                            price : '$2 000 000.00 CLP',
                            address : 'Pisos en los condes',
                            city : 'Santiago de Chile',
                        },
                        membership : 'mem-premium',
                        scoring : 900,
                        postul_date : {
                            ondate: '12/12/2018',

                        },
                        contacts : {
                            chat : true,
                            email : 'contact@example.com',
                            phone : '',
                        }
                    },
                    {
                        id: 3,
                        status : 'rejected',
                        text : 'Rechazado',
                        tenant : {

                            avatar : imagesDir+ '/avatars/user03.jpg',
                            name : 'Ruiz',
                            lastname : 'Molina'
                        },
                        holding : {
                            price : '$3 000 000.00 CLP',
                            address : 'Pisos en los condes',
                            city : 'Santiago de Chile',
                        },
                        membership : 'mem-basic',
                        scoring : 900,
                        postul_date : {
                            ondate: '12/12/2018',
                        },
                        contacts : {
                            chat : true,
                            email : '',
                            phone : '555-555-5555',
                        }
                    }
                ]

                ,
                imagesDir: imagesDir


            }
        },
        methods: {
            showTenantProfile : function(id){ // id del tenant
                this.$emit('showtenantdetail',id)
            }
        }
    }
</script>
