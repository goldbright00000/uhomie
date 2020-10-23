<template>
    <div class="tenant-interesting">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Proyectos uHomie</span>

            </div>
        </div>

        <div class="columns is-multiline property-list">
            <div class="columns is-multiline agent-list">
                <!--
                <div class="column is-6" v-for="agent in projects">
                    <agent v-bind="agent">
                    </agent>
                </div>
                -->
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


    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;
    import Agent from './../../Agent';


    export default {
        components: {
            Agent
        },
        name: 'TenantInteresting',
        props: {},
        data() {
            return {

                projects: [
                   {
                       imagePath: imagesDir+'/agents/img_cliente.jpg',
                       membership: 'basic',
                       name: 'Broker Network',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/agents/img_cliente.jpg',
                       membership: 'select',
                       name: 'Remax Brio',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/agents/img_cliente.jpg',
                       membership: 'premium',
                       name: 'Habiter',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/agents/img_cliente.jpg',
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
