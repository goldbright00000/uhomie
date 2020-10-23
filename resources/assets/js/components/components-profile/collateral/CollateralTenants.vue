<template>
    <div class="collateral-tenants">

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


        <div class="columns">
            <div class="column is-full">
                <table class="table is-fullwidth">
                    <thead class="is-italic">
                        <td>Usuario</td>
                        <td class="has-text-centered">Membresia</td>
                        <td class="has-text-centered"><img :src="imagesDir+'/postul-contact.png'"/></td>
                        <td class="has-text-centered">Acciones</td>
                    </thead>
                    <tbody v-if="info.tenants && info.tenants.length > 0">
                        <Tenant v-for="item in info.tenants" :key="item.id" v-bind:info="item"></Tenant>
                    </tbody>
                    <tbody v-else>
                        <td colspan="4">No tienes arrendatarios</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    const imagesDir = document.getElementById('images-dir').value;

    import GetingData from '../common/GetingData'
    import CollateralCore from './CollateralCore'
    import Tenant from '../../Tenant'
    export default {
        extends : CollateralCore,
        name: 'CollateralTenants',
        components: {
            Tenant
        },
        props: {

        },
        data() {
            return {
                imagesDir: imagesDir
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
