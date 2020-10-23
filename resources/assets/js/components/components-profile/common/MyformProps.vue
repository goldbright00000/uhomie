<template>
    <div class="owner-myforms">
        <div class="columns" v-for="item in info">
            <div class="column is-11">
                ID {{ item.id }} / Creada 10/10/2018
            </div>
            <div class="column is-1">
                <div class="media" style="width: 100px;">
                    <div>
                        <img :src="imagesDir+'/icono-ver.png'" />
                    </div>
                    <div>

                        <img :src="imagesDir+'/box-edit.png'" />
                    </div>
                    <div>
                        <img :src="imagesDir+'/icono_cruz_premium.png'" />
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;

    export default {
        name: 'MyformProps',
        props: {
            info: Array,
        },
        data() {
            return {
                imagesDir: imagesDir
            }
        }
    }
</script>
