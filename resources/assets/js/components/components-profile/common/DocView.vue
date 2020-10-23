<template>
    <div class="doc-view" v-if="file">
        <input @input="uploadFile($event)" class="file-input-profile" type="file" :id="this.file.name" :name="this.file.name" max="1" v-bind:accept="mines">
        <label :for="file.name" ><img :src="imagesDir+'/doc-plus.png'"/></label>
        <div class="tooltip" data-tooltip="Documento Verificado"><img :src="imagesDir+'/doc-check.png'"  :class="file.path && !(file.verified_ocr == '0') ? '': 'is-hidden' "/></div>
        <a :href="'/get-file/?path='+file.id" ><img :src="imagesDir+'/icono-descarga-g.png'"
                                                    :class="!file.path ? 'is-hidden' : ''" style="cursor: pointer"/></a>

    </div>
</template>
<script>


    import base from '../../../profiles/base';
    const _token = document.getElementById('_token').value;

    export default {
        extends: base,
        name: 'DocView',
        props: {
            file: Object, 
            mines: {
                type: String,
                default: 'image/*'
            }
        },
        data: function () {
            return { thisFile: this.file }
        },
        methods: {
            uploadFile(e) {
                const files = e.target.files
                if(files.length) {
                    if(e.target.accept == files[0].type || e.target.accept == 'image/*') {
                        
                        const formData = new FormData();
                        var re = /(?:\.([^.]+))?$/;
                        formData.append('ext',re.exec(files[0].name)[1]);
                        formData.append(e.target.name, files[0])
                        formData.append('action', 'upload-profile-document')
                        if (this.file.verified_ocr) {
                            formData.append('verified_ocr', 0)
                        }
                        formData.append('_token', _token)
                        var self = this;
                        
                        axios.post('/ocr_verify_perfil', formData, {
                            headers: {
                                'Content-type': 'multipart/form-data',
                            }
                        }).then((response) => {
                            // Actualizando documentos en componente Padre
                            /*
                             * **************************************************
                             * Codigo casos especiales ( rut frontal y trasero)
                             * **************************************************
                             */
                            if( response.data.hasOwnProperty('id_front') || response.data.hasOwnProperty('id_back')){
                                if ( response.data.hasOwnProperty('id_front')){
                                    console.log('viene un id_front');
                                    this.$emit('updateFront', response.data.id_front.file);
                                    if( response.data.id_front.success ){
                                        if( response.data.id_front.hasOwnProperty('mensaje') ){
                                            toastr.info(response.data.id_front.mensaje);
                                        } 
                                    }
                                    if( response.data.id_front.hasOwnProperty('razon') ){
                                        toastr.error(response.data.id_front.razon);
                                    }
                                    if( response.data.id_front.hasOwnProperty('exito') ){
                                        toastr.success(response.data.id_front.exito);
                                    }
                                }
                                if( response.data.hasOwnProperty('id_back') ){
                                    console.log('viene un id_back');
                                    this.$emit('updateBack', response.data.id_back.file);
                                    if( response.data.id_back.success ){
                                        if( response.data.id_back.hasOwnProperty('mensaje') ){
                                            toastr.info(response.data.id_back.mensaje);
                                        }
                                    }
                                    if( response.data.id_back.hasOwnProperty('razon') ){
                                        toastr.error(response.data.id_back.razon);
                                    }
                                    if( response.data.id_back.hasOwnProperty('exito') ){
                                        toastr.success(response.data.id_back.exito);
                                    }
                                }
                                var body = $("body");
                                body.removeClass("loading");
                                return;
                            }
                            /*
                             * **************************************************
                             * Codigo casos especiales ( rut frontal y trasero)
                             * **************************************************
                             */
                            
                            this.file =  response.data.file;
                            if( response.data.success ){
                                if( response.data.hasOwnProperty('mensaje') ){
                                    toastr.info(response.data.mensaje);
                                }
                            }
                            if( response.data.hasOwnProperty('razon') ){
                                toastr.error(response.data.razon);
                            }
                            if( response.data.hasOwnProperty('exito') ){
                                toastr.success(response.data.exito);
                            }
                            
                            var body = $("body");
                            body.removeClass("loading");
                            console.log(response.data);
                            
                        }).catch((error) => {
                            if(error.response) {
                                console.log(error);
                                let info = error.response.data;
                                let status = error.response.status;
                                
                                var body = $("body");
                                body.removeClass("loading");
                                toastr.error(info)
                            }else {
                                console.log(error)
                            }
                        });

                    } else {
                        toastr.error('EL formato es invalido, suba un archivo de tipo: ' + e.target.accept.split('/')[1])
                    }
                }
            }
        }
    }
</script>

<style>
 .doc-view > img:not(:last-child) {
    cursor: pointer;
 }
 .file-input-profile {
    display: none;
 }
</style>
