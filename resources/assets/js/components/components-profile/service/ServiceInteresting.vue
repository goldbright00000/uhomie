<template>
    <div class="service-interesting">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Proyectos uHomie</span>

            </div>
        </div>

        <div class="columns is-multiline property-list">
            <div class="columns is-multiline service-list">
                <div class="column is-6" v-for="service in projects">
                    <service v-bind="service">
                    </service>
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
        name: 'ServiceInteresting',
        props: {},
        data() {
            return {

                projects: [
                   {
                       imagePath: imagesDir+'/services/img_cliente.jpg',
                       membership: 'basic',
                       name: 'Broker Network',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/services/img_cliente.jpg',
                       membership: 'select',
                       name: 'Remax Brio',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/services/img_cliente.jpg',
                       membership: 'premium',
                       name: 'Habiter',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/services/img_cliente.jpg',
                       membership: 'basic',
                       name: 'Broker Network',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   }
                ],
                imagesDir: imagesDir


            }
        }
    }
</script>
