<template>
    <div class="owner-interesting">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Sugeridos uHomie</span>

            </div>
        </div>

        <div class="columns is-multiline">


            <!--<div class="column is-12 services-header" id="services-header">

                <h1 class="title">Gasistas</h1>
                <div v-for="service in services" :key="service.id">
                    <div class="service-type">
                    <service v-bind="service"></service>
                    </div>
                </div>
            </div>-->
            <div class="column is-12">
                <div>
                    <p style="text-align: center">
                        <br>
                            Próximamente...
                        <br>
                        <br>
                        <img src="/images/errors/marca-de-agua.png" alt="uHomie">
                        <br>
                        <br>
                    </p>
                </div>
            </div>


        </div>


    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;
    import Service from './../../Service';
    import OwnerCore from './OwnerCore';

    export default {
        extends: OwnerCore,
        components: {
            Service
        },
        name: 'OwnerSuggested',
        props: {},
        data() {
            return {

                services: [
                    {
                        imagePath: imagesDir + '/services/jardinero.jpg',
                        rating: 4,
                        name: 'Carlos Lopez',
                        serviceName: 'Jardinero',
                        description: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Molestiae iure totam libero explicabo nostrum dolore, ipsa debitis amet eum, facilis a? Impedit, incidunt amet consequuntur perferendis ex alias magnam illum.',
                        membership: 'basic',
                        imagesDir: imagesDir
                    },
                    {
                        imagePath: imagesDir + '/services/electricista.jpg',
                        rating: 3,
                        name: 'Marcos Batista',
                        serviceName: 'Técnico Electricista',
                        description: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Molestiae iure totam libero explicabo nostrum dolore, ipsa debitis amet eum, facilis a? Impedit, incidunt amet consequuntur perferendis ex alias magnam illum.',
                        membership: 'select',
                        imagesDir: imagesDir
                    }

                ],
                imagesDir: imagesDir


            }
        }
    }
</script>
