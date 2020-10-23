<template>
<div>
    <button class="button is-outlined is-basic" @click="switchButtonRecording(true)" :disabled="desactivarBoton" v-if="!verified">Verificar</button>
    <button class="button is-outlined is-basic" v-if="verified">Verificado</button>
    <div  :class="{'is-active': active, 'modal': true}" >
    <div class="modal-background"></div>
        <div class="modal-card" v-if="paso == 1">
            <header class="modal-card-head">
            <p class="modal-card-title">Paso 1: Graba tu rostro</p>
            <button class="delete" aria-label="close" @click="switchButtonRecording(false)"></button>
            </header>
            <section class="modal-card-body">
                <img v-if="showGuide" src="/images/vector_videoselfie.jpg" style="width:100%" >
                <video id="camarita" controls="" autoplay="" v-if="!showGuide"></video>
            </section>
            <footer class="modal-card-foot">
            <button class="button is-info" v-if="!recorded && !recording"  @click="iniciarGrabacion">Iniciar Grabación</button>
            <img style="margin-right:10px;margin-left:10px" src="/images/gifs/rec.gif" v-if="recording"  width="20px">
            <button class="button is-success" v-if="recorded" @click="siguiente" >Siguiente</button>
            <button class="button is-primary" v-if="recorded"  @click="eventoVolverAGrabar">Volver a grabar</button>
            <button class="button" @click="switchButtonRecording(false)">Cancelar</button>
            
            </footer>
        </div>
        <div class="modal-card" v-if="paso == 2" style="z-index: 999999">
            <header class="modal-card-head">
            <p class="modal-card-title">Paso 2: Verificando...</p>
            <button class="delete" aria-label="close" @click="switchButtonRecording(false)"></button>
            </header>
            <section class="modal-card-body">
                
                <img src="/images/gifs/gif-uhomie.gif" :style="{ display: verifyng? 'inline':'none' }" v-if="verifyng">
                <article class="message is-success" v-if="respuesta && !verifyng">
                    <div class="message-body">
                        La verificación facial ha sido un éxito. Cuando todos las partes verifiquen identidad, deberán firmar el contrato de arriendo, recuerda que tendrán un plazo de 48 horas para completarlo.
                    </div>
                </article>
                <article class="message is-danger" v-if="!respuesta && !verifyng && !error">
                    <div class="message-body">
                        No se encontró coincidencia con tu documento de identidad, por favor intenta nuevamente.
                    </div>
                </article>
                <article class="message is-danger" v-if="!respuesta && !verifyng && error">
                    <div class="message-body">
                        Ocurrió un error, por favor intente mas tarde.
                    </div>
                </article>
                <!--
                <article class="message is-danger" v-if="!successMessage && !verifyng">
                    <div class="message-body">
                        Lo sentimos! <strong>No detectamos tu rostro</strong> en el video o no coincide con tu <strong>foto de carnet verificada</strong>.
                    </div>
                </article>
                -->
            </section>
            <footer class="modal-card-foot">
                <button class="button is-link" @click="pasoAnterior" v-if="!verifyng && !verified">Volver a intentar</button>
                <button class="button " @click="switchButtonRecording(false)" v-if="!verified">Cancelar</button>
                <button class="button is-success" v-if="!verifyng" @click="switchButtonRecording(false)">Aceptar</button>
            </footer>
        </div>
        <div class="modal-card" v-if="paso == 3">
            <header class="modal-card-head">
                <p class="modal-card-title">Resultados Verificación</p>
                <button class="delete" aria-label="close" @click="switchButtonRecording(false)"></button>
            </header>
            <section class="modal-card-body">
                <article class="message is-info">
                    <div class="message-body">
                        {{successMessage}}
                    </div>
                </article>
            </section>
            <footer class="modal-card-foot">
            <button class="button is-success" v-if="state == 'processing'" @click="switchButtonRecording(false)">Aceptar</button>
            <button class="button is-success" v-if="state == 'no-match'" @click="paso=1">Aceptar</button>
            <button class="button is-success" v-if="state == 'no-face'" @click="paso=1">Aceptar</button>
            </footer>
        </div>
        <div class="modal-card" v-if="paso == 4">
            <header class="modal-card-head">
                <p class="modal-card-title">Atención</p>
                <button class="delete" aria-label="close" @click="switchButtonRecording(false)"></button>
            </header>
            <section class="modal-card-body">
                <article class="message is-warning">
                    <div class="message-body">
                        Primero debes tener tus documentos de identidad verificados. Te invitamos a hacerlo en la sección Configuración Cuenta &amp; Verificación Identidad del Menú.
                    </div>
                </article>
            </section>
            <footer class="modal-card-foot">
            <button class="button is-success"  @click="switchButtonRecording(false)">Aceptar</button>
            </footer>
        </div>
        <div class="modal-card" v-if="paso == 5">
            <header class="modal-card-head">
                <p class="modal-card-title">Atención</p>
                <button class="delete" aria-label="close" @click="switchButtonRecording(false)"></button>
            </header>
            <section class="modal-card-body">
                <article class="message is-warning">
                    <div class="message-body">
                        Necesitamos que tu carnet trasero esté vigente. Te invitamos a subir una foto de tu carnet trasero (reverso) en la pantalla inicial de "Mi Perfil".
                    </div>
                </article>
            </section>
            <footer class="modal-card-foot">
            <button class="button is-success"  @click="switchButtonRecording(false)">Aceptar</button>
            </footer>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        name: 'ModalVideoRecording',
        props: {
            profile: String,
            postul_id: Number
        },
        
        data() {
            return {
                desactivarBoton: true,
                state: String,
                verified: false,
                active: false,
                recorded: false,
                recording: false,
                blob: null,
                paso: 1,
                verifyng: false,
                xhr: null,
                successMessage: false,
                respuesta: Boolean,
                error: false,
                volverAGrabar: false,
                showGuide: true,
            }
        },
        mounted(){
            var self = this;
            /*
            $.ajax({
                url: '/pre-video-verify',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                type: 'GET',
                success: function(response) {
                    self.desactivarBoton = false;
                    switch(response.respuesta){
                        case 'blank':
                            
                            break;
                        case 'processing':
                            self.paso = 3;
                            self.$nextTick(function(){
                                self.state = response.respuesta;
                                self.successMessage = 'Estamos procesando el video, por favor espere unos minutos.'
                            });
                            break;
                        case 'no-match':
                            self.paso = 3;
                            self.$nextTick(function(){
                                self.state = response.respuesta;
                                self.successMessage = 'Lo sentimos... Las identidades no coincidieron como la misma persona. Pero puedes intentarlo de nuevo!';
                                
                            });
                            break;
                        case 'match':
                            self.paso = 3;
                            self.$nextTick(function(){
                                self.state = response.respuesta;
                                
                                self.verified = true;
                            });
                            break;
                        case 'no-face':
                            self.paso = 3;
                            self.$nextTick(function(){
                                self.state = response.respuesta;
                                self.successMessage = 'Lo sentimos... No detectamos ningun rostro en el video que grabaste. Pero puedes intentarlo de nuevo!'
                                
                            });
                            break;
                    }
                }
            });
            */
           $.ajax({
               url: '/state-video-verify',
               headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
               type: 'POST',
               success: function(response) {
                   self.desactivarBoton = false;
                   if(response.respuesta == 'verified'){
                       self.verified = true;
                   }
                   if(response.respuesta == 'no-verified'){
                       self.verified = false;
                   }
                   if(response.respuesta == 'blank'){
                       self.verified = false;
                   }
                   if(response.respuesta == 'no-verified-sas'){
                       self.verified = false;
                       self.paso = 4;
                   }
                   if(response.respuesta == 'no-verified-in-force'){
                       self.verified = false;
                       self.paso = 5;
                   }
               },
               error: function(){
                   console.log('Hubo un error en consultar estado verificacion de video.');
               }
           })
        },
        methods: {
            switchButtonRecording: function(b){
                this.active = b;
            },
            iniciarGrabacion: function(){
                /**
                 * Iniciando servicio vin
                 */
                this.showGuide = false;
                this.$nextTick(function(){
                        this.verifyng = true;
                    $.ajax({
                        url: '/55ae5abbac6c34cb1d93377cd31598f7', // replace with your own server URL
                        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                        type: 'POST',
                        success: function(response) {
                            
                        },
                        error: function(jqXHR, textStatus, errorThrown ){
                            console.log(jqXHR.textStatus);
                        }
                    });
                    var self = this;
                    navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(function(camera) {
                        self.recording = true;
                        // preview camera during recording
                        document.getElementById('camarita').muted = true;
                        document.getElementById('camarita').srcObject = camera;
                        // recording configuration/hints/parameters
                        let recordingHints = {
                            type: 'video',
                            mimeType: 'video/mp4',
                        };
                        // initiating the recorder
                        let recorder = RecordRTC(camera, recordingHints);
                        // starting recording here
                        recorder.startRecording();
                        // auto stop recording after 5 seconds
                        let milliSeconds = 5000;
                        setTimeout(function() {
                            // stop recording
                            recorder.stopRecording(function() {
                                // get recorded blob
                                let blob = recorder.getBlob();
                                self.blob = blob;
                                // release camera
                                document.getElementById('camarita').srcObject = null;
                                camera.getTracks().forEach(function(track) {
                                    track.stop();
                                });
                                // you can preview recorded data on this page as well
                                document.getElementById('camarita').src = URL.createObjectURL(blob);
                                self.recording = false;
                                self.recorded = true;
                            });

                        }, milliSeconds);
                    });
                });
                
            },
            eventoVolverAGrabar: function(){
                this.recorded = false;
                this.volverAGrabar = false;
            },
            pasoAnterior: function(){
               console.log('PASO ANTERIOR - VOLVER A INTENTAR');
                this.paso = 1;
                this.error = false;
                this.volverAGrabar = true;
                this.showGuide = true;
            },
            siguiente: function(){
                this.paso = 2;
                let blob = this.blob;
                
                this.verifyng = true;
                var self = this;
                // we need to upload "File" --- not "Blob"
                var fileObject = new File([blob], 'video.mp4', {
                    type: 'video/mp4'
                });
                var formData = new FormData();
                // recorded data
                formData.append('video-blob', fileObject);
                // file name
                formData.append('video-filename', fileObject.name);
                $.ajax({
                    url: '/video-verify/'+this.postul_id, // replace with your own server URL
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(response) {
                        self.verifyng = false;
                        self.respuesta = response.respuesta;
                        if( response.respuesta ){
                            self.verified = true;
                            self.recorded = false;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown ){
                        self.respuesta = false;
                        self.verifyng = false;
                        self.error = true;
                        self.recorded = false;
                        console.log(jqXHR.textStatus);
                    }
                });



            }
        }
    }
</script>