<template>
    <div class="collateral-suggeted">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Proyectos uHomie</span>
            </div>
        </div>
        <div class="columns is-multiline">
            <div class="column is-12 services-header" id="services-header">                
                <div v-for="item in info.services" :key="item.id">
                    <div class="service-type">
                        <service v-bind="item"></service>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const imagesDir = document.getElementById('images-dir').value;
    import Service from './../../Service';

    export default {
        components: {
            Service
        },
        name: 'CollateralSuggested',
        props: {},
        data() {
            return {               
                imagesDir: imagesDir       
            }
        },
        methods: {

        },
        
    }
</script>

