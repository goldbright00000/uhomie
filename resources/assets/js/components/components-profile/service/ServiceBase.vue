<template>
    <div class="service-contracts">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Contrato de arriendo</span>

            </div>
        </div>

        <div class="columns is-multiline property-list">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-4">
                        1
                    </div>
                    <div class="column is-5">
                        2
                    </div>
                    <div class="column is-2">
                        3
                    </div>
                    <div class="column is-1">
                        4
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;


    export default {
        components: {

        },
        name: 'ServiceBase',
        props: {},
        data() {
            return {

                imagesDir: imagesDir


            }
        }
    }
</script>
