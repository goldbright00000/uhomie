<template>
    <div class="modal is-active">
        <div class="modal-background" @click="closeIdentity"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title"> <i class="fa fa-exclamation-triangle" style="margin-right: 5px; color: #363636"></i>Requerimiento</p>
                <button class="delete" @click="closeIdentity" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                Para ingresar en las postulaciones de tus propiedades deberas cumplir con los siguientes requerimientos.
                <article v-if="documents.id_front.verified_ocr != 1 && documents.id_back.verified_ocr != 1" class="message is-warning is-small">
                    <div class="message-body">
                        Debes verificar el documento de identidad, debes cargar las imagenes de tu documento de identidad el <strong>Anverso</strong> y el <strong>Reverso</strong> en <a @click="$router.push('/')">Mi perfil</a> en el panel <strong>Documentos</strong>, te recomendamos que se tome su tiempo para tomar la imagenes y pueda cargar una imagen de buena calidad.
                    </div>
                </article>
                <article v-if="documents.id_front.verified != 1 && documents.id_back.verified != 1" class="message is-warning is-small">
                    <div class="message-body">
                        Debes verificar tu identidad, pero antes debiste validar los documentos de identidad, si ya lo completaste exitosamente dirigirte a <a @click="$router.push('/configs')">Configuraciones de mi Cuenta</a> y en el panel de <strong>Verificaciones</strong> encontralaras la opción <strong>Identidad</strong>.
                    </div>
                </article>
            </section>
            <footer class="modal-card-foot">
                <button class="button" @click="closeIdentity">Cerrar</button>
            </footer>
        </div>
    </div>
</template>
<script>
export default {
    name: 'VerifyIdentity',
    props: {
        documents: Object
    },
    data() {
        return {
            
        }
    },
    methods: {
        closeIdentity(){
            this.$emit('verifyIdentity', {'value': false});
        }
    }
    
}
</script>