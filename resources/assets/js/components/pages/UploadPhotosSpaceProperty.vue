<template>
    <div>
        <vue-dropzone
            ref="myVueDropzone"
            id="dropzone"
            :destroyDropzone="destroyspace"
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
    name: 'UploadPhotosSpaceProperty',
    props: {
        id: Number,
        token: String,
        limit: Number,
        activespace: Boolean,
        info: Object
    },
    computed:{
        
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
            destroyspace: false,
            dropzoneOptions: {
                params: {
                    cover: 0,
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
                dictDefaultMessage: "<i class='fa fa-plus fa-5x'></i>",
                addRemoveLinks: true,
                dictCancelUpload: 'Cancelar Subida',
                dictCancelUploadConfirmation: 'Desea cancelar la subida',
                dictRemoveFile: 'Quitar Imagen',
                timeout: 5000,
                maxFiles: 1,
                success: function(file, response) 
                {
                    //console.log(response);
                    //console.log(file);
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
                            
                                //console.log(error)
                        });
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
                },
            },
        }
    },
    methods:{
        showError(m){
            toastr.error(m);
            //console.log(m);
        },
        showSuccess(m){
            toastr.success(m);
            //console.log(m);
        },
        showSuccessUpload(file, response){
            this.destroyspace = true;
            this.info.active = false;
            this.$emit('closespace', {'value': false});
            this.$emit('photospace', {'id': response.photo_id, 'path': response.path, 'space_id': response.space_id});
            toastr.success('Se ha subido la imagen exitosamente');
        }
    },
    mounted() {
        this.destroyspace = this.$parent.destroyspace;
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