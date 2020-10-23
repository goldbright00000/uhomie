<template>
    <span :class="online ? 'online' : 'offline'"
          :title="online ? 'Conectado' : 'Desconectado'">
    </span>
</template>

<script>
	export default {
        props: {
            online: Boolean
        },
        data() {
            return {

            };
        },
        mounted() {            
        }
    }
</script>

