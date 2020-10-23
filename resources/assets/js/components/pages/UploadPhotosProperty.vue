<template>
    <div>
        <vue-dropzone
            ref="myVueDropzone"
            id="dropzone"
            :destroyDropzone="destroy"
            v-on:vdropzone-error="showError('Ha ocurrido un error')"
            v-on:vdropzone-max-files-exceeded="showError('Ha superado el limite de archivos a cargar')"
            v-on:vdropzone-canceled="showSuccess('Se ha cancelado la imagen exitosamente')"
            v-on:vdropzone-success="showSuccessUpload"
            :options="dropzoneOptions"
            >
            </vue-dropzone>
    </div>
    
</template>
<script>
const _token = document.head.querySelector("[name=csrf-token]").content;
const imagesDir = document.getElementById('images-dir').value;

import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
//import axios from 'axios'
export default {
    name: 'UploadPhotosProperty',
    props: {
        id: Number,
        token: String,
        limit: Number,
        active: Boolean,
        property_type: {
            type: String,
            default: 'Propiedad'
        }
    },
    computed:{
        destroy(){
            return this.$parent.destroy
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
                params: {
                    cover: 1,
                    _token : _token
                },
                url: '/save-prop-photos?property_id='+this.id,
                thumbnailWidth: 200,
                headers: { "X-CSRF-TOKEN": this.token },
                maxFilesize: 8,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                    return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                dictInvalidFileType: "Tipo de archivo no admitido",
                dictFileTooBig: 'Ha superado el limite de carga de imagen',
                resizeWidth: 1024,
                resizeHeight: 1024,
                resizeQuality: 0.8,
                resizeMethod: 'contain',
                //resizeMimeType: 'image/jpeg',
                dictDefaultMessage: this.property_type == 'Bodega' ? 'Sube aqui la imagen cover de tu bodega' : 'Sube aqui la imagen cover de tu propiedad',
                addRemoveLinks: true,
                dictCancelUpload: 'Cancelar Subida',
                dictCancelUploadConfirmation: 'Desea cancelar la carga de imagen',
                dictRemoveFile: 'Quitar Imagen',
                timeout: 5000,
                maxFiles: 1,
                success: function(file, response) 
                {
                    console.log(response);
                    console.log(file);
                    
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

                    axios.post('/delete-prop-photos', params, {
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
            params.append('service', this.id);
            axios.post('this.urlDel', params, {
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
            params.append('property', this.id);
            axios.post('this.urlGet', params, {
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
        },
        showError(m){
            toastr.error(m);
            console.log(m);
        },
        showSuccess(m){
            toastr.success(m);
            console.log(m);
        },
        showSuccessUpload(file, response){
            this.destroy = true
            this.$emit('close', {'value': false});
            this.$emit('photo', response);
            toastr.success('Se ha subido la imagen exitosamente');
        }
    },
    mounted() {
        
        
    },
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