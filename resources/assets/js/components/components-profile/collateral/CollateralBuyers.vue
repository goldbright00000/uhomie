<template>
    <div class="collateral-buyers">
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
                            <option value="Fecha de Visita">Fecha de Visita</option>                            
                        </select>
                    </div>
                </div>
            </div>
        </div>        
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-12">
                <div class="columns" style="border-bottom: 1px solid #eee; border-top: 1px solid #eee; ">
                    <div class="column is-2">
                        <span>Usuario</span>
                    </div>
                    <div class="column">
                        <span>Proyecto</span>
                    </div>
                    <div class="column">
                        <span>Membres&iacute;a</span>
                    </div>
                    <div class="column is-2">
                        <img :src="imagesDir+'/postul-date.png'" />
                    </div>
                    <div class="column is-1">
                        <img :src="imagesDir+'/postul-contact.png'" />
                    </div>
                </div>

            </div>
        </div>

        <!-- DETALLES -->
        <div v-for="collateral in info" :key="collateral.id">
            <collateral v-bind="collateral"></collateral>            
        </div>
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    import Collateral from './../../Collateral';


    export default {
        components: {
            Collateral
        },
        name: 'CollateralBuyers',
        props: {

        },
        data() {
            return {
                info: [

                ]
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