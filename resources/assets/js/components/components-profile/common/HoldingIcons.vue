<template>
    <section class="holding-details">
        <div class="columns">
            <div class="column is-one-third">
                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono_habitacion.png'">
                    </div>
                    <div class="media-content">
                        {{ info.bedrooms }}
                    </div>
                </div>

            </div>
            <div class="column is-one-third">

                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono_banera.png'">
                    </div>
                    <div class="media-content">
                        {{ info.bathrooms }}
                    </div>
                </div>

            </div>
            <div class="column is-one-third">
                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono_garage.png'">
                    </div>
                    <div class="media-content">
                        {{ info.private_parking }}
                    </div>
                </div>

            </div>

        </div>

        <div class="columns">
            <div class="column is-one-third">
                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono-mascota.png'">
                    </div>
                    <div class="media-content is-uppercase">
                        {{ info.pet_preference }}
                    </div>
                </div>

            </div>
            <div class="column is-one-third">

                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono_amoblado.png'">
                    </div>
                    <div class="media-content">
                        {{ info.furnished }}
                    </div>
                </div>

            </div>
            <div class="column is-one-third">
                <div class="media">
                    <div class="media-left">
                        <img :src="imagesDir+'/icono_superficie.png'">
                    </div>
                    <div class="media-content">
                        {{ info.meters }}m2
                    </div>
                </div>

            </div>

        </div>

    </section>
</template>
<script>

    const imagesDir = document.getElementById('images-dir').value;

    export default {
        name: 'HoldingIcons',
        props: {
          info: Object,
        },
        data() {
            return {
                imagesDir : imagesDir
            }
        }
    }
</script>
