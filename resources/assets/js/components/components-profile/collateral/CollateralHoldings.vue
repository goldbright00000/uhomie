<template>
    <div class="collateral-holding">
        <!-- FILTROS -->
        <div class="columns">
            <div class="column is-4">
            
            </div>
            <div class="column is-8">
                <!-- <a @click="$emit('edit_holding',0)" class="button is-outlined is-primary at-right">Registrar Nuevo Proyecto +</a> -->
                <!-- <router-link to="/first-step/three"><span class="button is-outlined is-primary at-right">Registrar Nuevo Proyecto +</span></router-link> -->
                <a href="/users/collateral/registration/second-step" class="button is-outlined is-primary at-right">Registrar Nuevo Proyecto +</a>

            </div>
        </div>
        <!-- DETALLES -->
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-12">
                <div class="columns" style="border-bottom: 1px solid #eee; border-top: 1px solid #eee;">
                    <div class="column is-1">
                        <span>ID</span>
                    </div>
                    <div class="column is-1">
                        <span>Estado</span>
                    </div>
                    <div class="column is-2">
                        <span>Proyecto</span>
                    </div>
                    <div class="column is-2">
                        <span>&nbsp;</span>
                    </div>
                    <div class="column is-1">
                        <span>Precio de Venta</span>
                    </div>
                    <div class="column is-3">
                        <span>Detalles</span>
                    </div>                
                    <div class="column is-1">
                        <span>Vistas</span>
                    </div>                  
                    <div class="column is-1">
                        <span>Editor</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- DETALLES -->

        <div v-for="item in info.projects" :key="item.id">
            <holding @edit="$emit('edit_holding',$event)" v-bind:info="item"></holding>
        </div>

    </div>    
</template>
<script>
    import Holding from '../../Holding.vue';
    import CollateralCore from './CollateralCore';
    const imagesDir = document.getElementById('images-dir').value;
    export default {
        extends: CollateralCore,
        components: {
            Holding,
        },
        name: 'CollateralHoldings',
        props: {
            
        },       
        data() {
            return {               
                imagesDir: imagesDir       
            }
        },
        methods: {

        },
        
    }
</script>

