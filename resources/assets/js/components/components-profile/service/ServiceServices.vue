<template>
    <div class="service-postulations">

        <!-- FILTROS -->

        <div class="columns">
            <div class="column is-4">


                <div class="media">
                    
                </div>

            </div>
            <div class="column is-8">
                <a :href="createService" class="button is-outlined is-primary at-right">Registrar nuevo servicio + </a>
            </div>
        </div>


        <!-- DETALLES -->
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-12">
                <table style="overflow-x:auto;" class="table is-fullwidth">
                    <thead>
                        <tr class=" is-size-7">
                            <th>Foto</th>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th class="has-text-centered">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <services v-for="item in info.services" :key="item.id" v-bind:info="item"></services>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- DETALLES -->
        <div >
            
        </div>
    </div>
</template>

<script>

    import Services from '../../Services.vue';
    import ServiceCore from './ServiceCore';
    const imagesDir = document.getElementById('images-dir').value;


    export default {
        extends : ServiceCore,
        components: {
            Services
        },
        name: 'ServiceServices',
        props: {

        },
        computed: {
        },
        data() {
            return {
                imagesDir: imagesDir,
                createService: '/services/registration/first-step/one'
            }
        }

    }
</script>

<style>
.profiles .postul-status .media-content .approved, .profiles .holding-status .media-content .approved {
  border-bottom: 5px solid #51e3c4;
}

.profiles .postul-status .media-content .waiting_for_acceptance, .profiles .holding-status .media-content .waiting_for_acceptance {
  border-bottom: 5px solid #f7d16c;
}

.profiles .postul-status .media-content .refused, .profiles .holding-status .media-content .refused {
  border-bottom: 5px solid #d86862;
}

</style>
