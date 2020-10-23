<template>
    <div class="collateral-interesting">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Proyectos uHomie</span>

            </div>
        </div>

        <div class="columns is-multiline property-list">
            <div class="columns is-multiline collateral-list">
                
                    <div class="column is-6" v-for="collateral in projects">
                    <collateral v-bind="collateral">
                    </collateral>
                </div>
                </div>
            </div>
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
        name: 'CollateralInteresting',
        props: {},
        data() {
            return {

                projects: [
                   {
                       imagePath: imagesDir+'/collaterals/img_cliente.jpg',
                       membership: 'basic',
                       name: 'Broker Network',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/collaterals/img_cliente.jpg',
                       membership: 'select',
                       name: 'Remax Brio',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/collaterals/img_cliente.jpg',
                       membership: 'premium',
                       name: 'Habiter',
                       description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt magnam voluptatibus minima vitae blanditiis distinctio eaque inventore ipsum soluta quasi.',
                       imagesDir: imagesDir
                   },
                   {
                       imagePath: imagesDir+'/collaterals/img_cliente.jpg',
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
