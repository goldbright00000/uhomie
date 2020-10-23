<template>
    <div class="your-mem">
        <div>
            <img :src="imagesDir+'/your-mem.png'">
        </div>
        <div>
            <img :src="imagesDir+'/logo_'+membership_data.name.toLowerCase()+'.png'">
        </div>
        <div class="text">Vencimiento</div>
        <div class="text">{{ moment(membership_data.pivot.expires_at).locale('es').format('LL') }}</div>
        <br>
        <div>
            <!-- <a href="#" class="button is-outlined is-primary">ver detalles</a> -->
            <router-link v-show="role != 5" to="/membership"><span class="button is-outlined is-primary">Ver Detalles</span></router-link>
        </div>
    </div>
</template>
<script>
    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'YourMem',
        props: {
            membership: String,
            membership_data : Object,
            role: Number
        },
        data() {
            return {
                imagesDir: imagesDir,
            }
        }
    }
</script>
