<template>
    <div>
        <div v-if="checked" class="check-status">
            <img :src="imagesDir+'/icono-ok.png'">
            <i>Verificado</i>
        </div>

        <a v-else class="button is-outlined is-basic" v-on:click="open = true" :disabled="disabled">Verificar</a>
        
        
        
    </div>
    
    
</template>

<script>
    

    const imagesDir = document.getElementById('images-dir').value;

    export default {
        name: 'Checkstatus',
        props: {
            checked: Boolean,
            disabled: Boolean,
            method: String,
        },
        data : function(){
            return {
                imagesDir : imagesDir,
                open: Boolean
            }
        },
        
        methods : {
            onverify : function(){
                this.$emit('verifynow')

                console.log('enviando')
            },
            
        }
    }
</script>
