<template>
    <div class="tenant-interesting">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Sugeridos uHomie</span>

            </div>
        </div>

        <div class="columns is-multiline">


            <div class="column is-12 services-header" id="services-header">

                <h1 class="title">Gasistas</h1>
                <div v-for="service in services">
                    <div class="service-type">
                    <service v-bind="service"></service>
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
        name: 'TenantSuggested',
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
