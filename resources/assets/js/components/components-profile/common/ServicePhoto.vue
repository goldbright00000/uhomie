<template>
    <div class="service-photo">
        <div class="columns is-mobile" style="padding-left:24px;">
            <div class="column is-half line-down">
                <span>Fotos</span>
            </div>
            <div class="column">
                <div class="has-text-right">
                    <img v-if="upload" :src="imagesDir+'/icono_guardar.png'" style="cursor:pointer" @click="addPhotos()"/>
                    <img v-if="!upload" :src="imagesDir+'/box-edit.png'" style="cursor:pointer" @click="switchDelPhotos(true)"/>
                </div>
            </div>
        </div>
        <div class="columns is-multiline" v-if="!upload ? 'display:block' : 'display:none'">
            <div v-for="item in info.photos" :key="item.id" class="column is-4">
                <img :src="item.path">
            </div>
        </div>
        <div id="box-dropzone">
            <vue-dropzone :style="upload ? 'display:block' : 'display:none'" ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone>
        </div>
        <div :class="editing ? 'modal is-active' : 'modal'">
            <div class="modal-background" @click="switchDelPhotos(false)"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Advertencia</p>
                    <button class="delete" aria-label="close" @click="switchDelPhotos(false)"></button>
                </header>
                <section class="modal-card-body">
                    <article class="message is-success">
                        <div class="message-body">
                            Si desea cambiar las fotos de su Servicio este proceso borrara las fotos antes ingresadas y se desplegara una nueva caja donde podras ingresar nuevas fotos.
                        </div>
                    </article>
                    <article class="message is-info">
                        <div class="message-body">
                            La cantidad de imagenes que podras carga según tu membresia es de <strong>{{membership}}</strong> imagenes.
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" @click="delPhotosService()">Aceptar</button>
                    <button class="button" @click="switchDelPhotos(false)">Cancelar</button>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
const imagesDir = document.getElementById('images-dir').value;
const _token = document.getElementById('_token').value;
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
//import axios from 'axios'
export default {
  name: 'ServicePhoto',
  props: {
      info: Object,
      membership: Number,
      photo: Object
  },
  computed:{
      idService(){
          return this.info.id
      }
  },
  components: {
    vueDropzone: vue2Dropzone
  },
  data: function () {
    return {
        imagesDir: imagesDir,
        icon: true,
        editing: false,
        upload: false,
        dropzoneOptions: {
            url: '/users/service/save-photos/' + this.info.id,
            thumbnailWidth: 200,
            headers: { 'X-CSRF-TOKEN': _token },
            maxFilesize: 2,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time+file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            dictDefaultMessage: 'Sube tus fotos Aqui',
            addRemoveLinks: true,
            dictCancelUpload: 'Cancelar Subida',
            dictCancelUploadConfirmation: 'Subida Cancelada',
            dictRemoveFile: 'Quitar Imagen',
            timeout: 5000,
            maxFiles: this.membership,
            success: function(file, response) 
            {
                toastr.success('La imagen a sido cargada satisfactoriamente.')
                console.log(response);
                
            },
            error: function(file, response)
            {
                return false;
            },
            removedfile: function(file) 
            {
                var name = file.upload.filename;
                const params = new FormData()
                
                params.append('_token', _token);
                params.append('filename', name)

                axios.post('/users/service/delete-service-photos/', params, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                        }
                    }).then((response) => {
                        toastr.success('La imagen a sido eliminada satisfactoriamente.')
                    }).catch((error) => {
                        
                            console.log(error)
                    });
                var fileRef;
                return (fileRef = file.previewElement) != null ? 
                fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
        },
    }
  },
  methods:{
        delPhotosService(){
            this.icon = false
            const params = new FormData()
            params.append('_token', _token);
            params.append('service', this.info.id);
            axios.post('/users/service/delete-photos-service', params, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                this.upload = true
                this.editing = false
                this.info.photos = []
                this.info.photo = {
                    path: ''
                }
                toastr.success('Caja limpia satisfactoriamente, ahora puedes cargar imagenes.')
            }).catch((error) => {
                console.log(error)
            });
            
        },
        switchDelPhotos(w){
            this.editing = w
        },
        addPhotos(){
            const params = new FormData()
            params.append('_token', _token);
            params.append('service', this.info.id);
            axios.post('/users/service/get-photos-service', params, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                this.upload = false
                this.icon = true
                this.info.photos = response.data.photos
                this.info.photo = response.data.photo
                /*var elem = document.getElementById("box-dropzone")
                elem.parentNode.removeChild (elem)*/
                toastr.success('Las imagenes han sido guardadas satisfactoriamente.')
            }).catch((error) => {
                console.log(error)
            });
        }
  },
  mounted() {
      
  }
}
</script>

<style>
    .dropzone {
        background: white;
        border-radius: 5px;
        border: 2px dashed rgb(0, 135, 247);
        border-image: none;
    }
</style>