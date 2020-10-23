<template>
    <div class="overlay" v-show="toShowMembership">
        <div class="verification-box">
            <div class="has-text-centered">
                <img :src="imagesDir+'/foco.png'">
            </div>
            <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                <h4>Atención!</h4>
                <p>Has alcanzado el limite de postulaciones segun tu membresia, mejora tu plan actual para gestionar esta y otras postulaciones.</p>
            </div>
            <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                <button style="background-color: transparent;
                    border: 1px solid white;
                    padding: 10px 30px;
                    color: white;
                    font-size: 14px;
                    font-weight: 400;" v-on:click="closeToMembership">VOLVER</button>
                <button style="background-color: transparent;
                    border: 1px solid white;
                    padding: 10px 30px;
                    color: white;
                    font-size: 14px;
                    font-weight: 400;" v-on:click="$router.push('/membership')">MEJORAR</button>
            </div>
        </div>
    </div>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;
export default {
    name: 'upgradeMembership',
    props: {
            toShowMembership: Boolean,
    },
    data(){
        return {
            imagesDir: imagesDir,
        }
    },
    methods:{
        closeToMembership: function(){
            this.$parent.toShowMembership = false
        }
    }
}
</script>


<style>
    .overlay {
        position: fixed;
        z-index: 999999999999999999999;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        background-color: rgba(2, 2, 2, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .overlay.hidden {
        display: none;
    }

    .pinlogin {
        margin: 40px 10px
    }

    .pinlogin input {
        width: 30px;
        height: 30px;
        margin: 5px;
        text-align: center;
        font-size: 18px;
    }
</style>

