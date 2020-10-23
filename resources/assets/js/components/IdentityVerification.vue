<template>

    <div>
        
        <div v-if="state == 3" class="check-status">
            <img :src="imagesDir+'/icono-ok.png'">
            <i>Verificado</i>
        </div>

        <a class="button is-outlined is-basic" :disabled="loading" @click="verifyAction" v-if="state == 0" >{{botton_text}}</a>

        <a class="button is-outlined is-basic" :disabled="loading" @click="verifyAction" v-if="state == 1" >{{botton_text}}</a>

        <a class="button is-outlined is-basic" :disabled="loading" @click="showState" v-if="state == 2" >Ver Estado</a>
        <a class="button is-outlined is-basic" :disabled="loading" @click="showState" v-if="state == 4" >Ver Estado</a>

        <div  :class="{'is-active': active, 'modal': true}" >
            <div class="modal-background"></div>
            <div class="modal-card" v-if="paso == 0">
                <header class="modal-card-head">
                <p class="modal-card-title">Verifica tu identidad</p>
                <button class="delete" aria-label="close" @click="switchButtonVerifyng(false)"></button>
                </header>
                <section class="modal-card-body">
                    
                    <!--<img src="/images/gifs/gif-uhomie.gif" v-if="verifyng">-->
                    <article class="message is-danger" v-if="state == 0">
                        <div class="message-body">
                            {{message}}
                            <img style="margin-top:10px;" src="/images/guia_carnet.jpg">
                        </div>
                    </article>
                    <article class="message is-info" v-if="state == 1">
                        <div class="message-body">
                            {{message}}
                            <img src="/images/guia_foto.jpg">
                        </div>
                    </article>
                    
                    <article class="message is-info" v-if="state == 2"> 
                        <div class="message-body">
                            <!-- El resultado de tu verificacion es: -->
                            <h4>El resultado de tu verificación es: </h4><br>
                            <div v-for="(documento, index) in documentos" :key="index"  :class="['notification', 'is-'+(documento.mensaje == 'OK'? 'success' : 'danger')]">
                                <strong>{{(documento.tipo_doc == 'ID_CARD' ? 'Carnet de Identidad ': (documento.tipo_doc == 'SELFIE'? 'Selfie ':'Pasaporte '))}} {{ ( documento.subtipo_doc? (documento.subtipo_doc == 'FRONT_SIDE'? '(Anverso)':'(Reverso)'): '' ) }}:</strong>
                                <br>
                                {{ documento.mensaje }}
                            </div>
                        </div>
                    </article>
                    
                    <article class="message is-info" v-if="state == 4">
                        <div class="message-body">
                            <!-- Estamos verificando -->
                            {{message}}
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                <button class="button is-success" @click="toPreVerifyngModal" v-if="state == 1">Continuar</button>
                <button class="button is-success" @click="volverAIntentar" v-if="state == 2">Volver a Intentar</button>
                <button class="button is-success" @click="switchButtonVerifyng(false)" v-if="state == 4">OK</button>
                <button class="button" @click="switchButtonVerifyng(false)">Cancelar</button>
                </footer>
            </div>
            <div class="modal-card" v-if="paso == 10">
                <header class="modal-card-head">
                <p class="modal-card-title">Sube una foto</p>
                <button class="delete" aria-label="close" @click="switchButtonVerifyng(false)"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field" style="display: inline;">
                                <div class="file is-centered is-boxed is-success has-name">
                                    <label class="file-label">
                                    <input class="file-input" id="inputFoto" type="file" @change="onFileChange" name="imagen" accept="image/jpeg">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                        <i class="fa fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                        Subir imagen .jpg
                                        </span>
                                    </span>
                                    <span class="file-name">
                                        {{fileName}}
                                    </span>
                                    </label>
                                    <br>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-info" @click="paso=0" >Atrás</button>
                    <button class="button is-success" v-if="subeFoto" @click="sendFoto">Subir Foto</button>
                    <button class="button is-link" @click="toVerifyngModal" >Abrir Cámara</button>
                </footer>
            </div>
            <div class="modal-card" v-if="paso == 1">
                <header class="modal-card-head">
                <p class="modal-card-title">Verifica tu identidad</p>
                <button class="delete" aria-label="close" @click="switchButtonVerifyng(false)"></button>
                </header>
                <section class="modal-card-body">
                    <video id="camarita" controls="" autoplay="" v-if="!shooted"></video>
                    <canvas id="canvas" style="display:none">
                    </canvas>
                    <div class="output" v-if="shooted">
                        <img id="photo" alt="La captura de imagen aun no aparece por aqui :/">
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-info" @click="takePicture" v-if="!shooted">Tomar una foto</button>
                    <button class="button is-info" v-if="shooted" @click="otraFoto">Tomar otra foto</button>
                    <button class="button is-success" v-if="shooted" @click="enviarImagen">Siguiente</button>
                </footer>
            </div>
            <div class="modal-card" v-if="paso == 2">
                <header class="modal-card-head">
                <p class="modal-card-title">Verifica tu identidad</p>
                <button class="delete" aria-label="close" @click="switchButtonVerifyng(false)"></button>
                </header>
                <section class="modal-card-body">
                    <img src="/images/gifs/gif-uhomie.gif" v-if="verifyng">
                    <article class="message is-info" v-if="!verifyng">
                        <div class="message-body">
                            Ok, en estos momentos estamos procesando la imagen, esto demora normalmente entre 10 a 20 minutos, y en el peor de los casos hasta 24 horas, pero no te preocupes, uHomie te avisará mediante sms y email el resultado del proceso.
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-info" @click="switchButtonVerifyng(false)" >Aceptar</button>
                </footer>
            </div>
        </div>
    </div>
</template>


<script>
const imagesDir = document.getElementById('images-dir').value;

export default {
    name: 'IdentityVerification',
    props: {

    },
    data() {
        return {
            botton_text: 'Verificar',
            loading: true,
            state: 0,
            imagesDir: imagesDir,
            paso: 0,
            state: 0,
            message: String,
            active: false,
            streaming: false,
            shooted: false,
            xhr: null,
            verifyng: false,
            fileName: 'No se ha seleccionado archivo..',
            subeFoto: false,
            documentos: []
        }
    },
    methods: {
        volverAIntentar: function(){
            this.state = 1;
            this.paso = 0;
            this.message = 'Excelente!. A continuación necesitamos que te tomes una selfie junto a tu carnet de identidad/pasaporte, como en el caso en verde de la imagen de abajo, para continuar el proceso de verificación de documento de identidad. ';
        },
        onFileChange(event){
            var fileData =  event.target.files[0];
            this.fileName=fileData.name;
            this.subeFoto = true;
        },
        switchButtonVerifyng: function(b){
            this.active = b;
        },
        checkState: function(){
            var self = this;
            $.ajax({
                url: '/identity-check',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                //data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(response) {
                    console.log(response);
                    self.loading = false;
                    self.state = response.state;
                    self.paso = response.paso;
                    if( response.hasOwnProperty('mensaje') ){
                        self.message = response.mensaje;
                    }
                    if( response.hasOwnProperty('documentos') ){
                        self.documentos = response.documentos;
                        console.log(self.documentos);
                    }
                    
                    
                },
                error: function(jqXHR,textStatus,errorThrown){
                    console.log(jqXHR);
                }
            });
        },
        verifyAction: function(){
            console.log('makeToVerify');
            if(!this.loading){
                this.switchButtonVerifyng(true);
            }
            
            
        },
        toPreVerifyngModal: function(){
            this.paso = 10;
        },
        toVerifyngModal: function(){
            this.paso = 1;
            this.startup();
        },
        startup: function(){
            let self = this;
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function(stream) {
                document.getElementById('camarita').srcObject = stream;
                document.getElementById('camarita').play();
                
                //self.funcioncita();
                document.getElementById('camarita').addEventListener('canplay', function(ev){
                    if (!self.streaming) {
                        
                        document.getElementById('canvas').setAttribute('width', document.getElementById('camarita').videoWidth);
                        document.getElementById('canvas').setAttribute('height', document.getElementById('camarita').videoHeight);
                        self.streaming = true;
                        
                    }
                }, false);
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });
        },
        takePicture: function(){
            
            let context = document.getElementById('canvas').getContext('2d');
            document.getElementById('canvas').width = document.getElementById('camarita').videoWidth;
            document.getElementById('canvas').height =document.getElementById('camarita').videoHeight;
            
            context.drawImage(document.getElementById('camarita'), 0, 0, document.getElementById('canvas').width, document.getElementById('canvas').height);
            
            var data = document.getElementById('canvas').toDataURL('image/jpeg');
            
            this.shooted = true;
            
            
            var self = this;
            self.closeCamera();
            self.$nextTick(function(){
                document.getElementById('photo').setAttribute('src', data);
                
            });
               
            
        },
        closeCamera: function(){
            let stream = document.getElementById('camarita').srcObject;
            stream.getTracks().forEach(track => track.stop());
        },
        showState: function(){
            console.log('showState');
            console.log(this.loading);
            if( this.loading == false){
                this.switchButtonVerifyng(true);
            }
            
        },
        otraFoto: function(){
            this.shooted = false;
            var self = this;
            this.$nextTick(function(){
                self.startup();
            });
            
        },
        enviarImagen: function(){
            let dataImage = document.getElementById('photo').src;
            var self = this;
            self.verifyng = true;
            var formData = new FormData();
            // recorded data
            formData.append('dataimage', dataImage);
            $.ajax({
                url: '/identity-send-image',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(response){
                    console.log(response);
                    self.state = response.state;
                    self.message = response.mensaje;
                    self.paso = response.paso;
                    self.verifyng = false;
                    self.botton_text = 'Verificando';
                },
                beforeSend: function(){
                    self.paso = 2;
                    self.verifyng = true;
                    self.botton_text = 'Verificando';

                },
                complete: function(){
                    self.verifyng = false;
                    self.botton_text = 'Verificando';

                }
            });
        },
        sendFoto: function(){
            let dataImage = $('#inputFoto')[0].files[0];
            var self = this;
            self.verifyng = true;
            var formData = new FormData();
            // recorded data
            formData.append('dataimage', dataImage);
            $.ajax({
                url: '/identity-send-image',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(response){
                    console.log(response);
                    self.state = response.state;
                    self.message = response.mensaje;
                    self.paso = response.paso;
                    self.verifyng = false;
                    self.botton_text = 'Verificando';
                },
                beforeSend: function(){
                    self.paso = 2;
                    self.verifyng = true;
                    self.botton_text = 'Verificando';
                },
                complete: function(){
                    self.verifyng = false;
                    self.botton_text = 'Verificando';
                }
            });
        },
    },
    mounted() {
        this.checkState();
    },
    
}
</script>
    