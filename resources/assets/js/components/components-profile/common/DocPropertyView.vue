<template>
    <div class="doc-property-view">
        <div class="columns">
            <div class="column is-three-quarters">
                <p><span>{{getText(file.name)}}</span> <a v-if="file.name == 'property_certificate'" href="https://www.conservador.cl/portal/copia_dominio_vigente" target="_blank">Buscalo aqui</a></p>
            </div>
            <div class="column has-text-right">
                <div v-if="file">
                    <input @change="uploadFile($event)" :hidden="!editing" class="file-input-profile" type="file" :id="file.name" :name="file.name" max="1" v-bind:accept="mines" :disabled="!editing">
                    <span class="tooltip" data-tooltip="Editar"><label :for="file.name" :hidden="!editing" style="cursor: pointer;"><img :src="imagesDir+'/box-edit.png'"/></label></span>
                    <span :class="file.verified == '1' ? 'tooltip': 'is-hidden' " data-tooltip="Documento Verificado"><img :src="imagesDir+'/doc-check.png'" /></span>
                    <span :class="file.verified == '3' ? 'tooltip': 'is-hidden' " data-tooltip="Requiere subir otro documento"><img :src="imagesDir+'/icono_cruz_basic.png'"/></span>
                    <span :class="!file.path ? 'is-hidden' : 'tooltip'" style="cursor: pointer" data-tooltip="Ver Documento"><a :href="'/'+file.path" :download="file.original_name"><img :src="imagesDir+'/doc-eye.png'"/></a></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

const imagesDir = document.getElementById('images-dir').value;
const _token = document.getElementById('_token').value;

export default {
    
    name: 'DocPropertyVue',
    props: {
        //file: Object,
        editing: Boolean,
        mines: {
            type: String,
            default: 'application/pdf'
        }
    },
    computed: {
    },
    data: function () {
        return {
            file: this.$attrs.file, 
            imagesDir: imagesDir
        }
    },
    methods: {
        uploadFile(e) {
            const files = e.target.files
            this.$Progress.start()
            if(files.length) {
                if(e.target.accept == files[0].type || e.target.accept == 'application/pdf') {
                    
                    const formData = new FormData()

                    formData.append(e.target.name, files[0])
                    formData.append('action', 'upload-profile-document')
                    if (this.file.verified) {
                        formData.append('verified', 0)
                    }
                    formData.append('_token', _token)

                    axios.post('/properties/registration/fourth-step/one/'+this.$route.params.idProperty, formData, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                        }
                    }).then((response) => {
                        toastr.success('Los datos se han salvado satisfactoriamente.')
                        console.log(response)
                        //this.$attrs.file = response.data.file;
                        this.$emit('filedata', {'file': response.data.file});
                        this.$Progress.finish();
                    }).catch((error) => {
                        if(error.response) {
                            let info = error.response.data;
                            let status = error.response.status;
                            this.$Progress.fail();
                            toastr.error(info);
                        }else {
                            console.log(error);
                        }
                    });

                } else {
                    toastr.error('EL formato es invalido, suba un archivo de tipo: ' + e.target.accept.split('/')[1])
                }
            }
        },
        getText : function(x) {
            switch(x){
                case "last_electricity_bill":
                    return "Ultimo recibo de Luz"
                break;
                case "last_water_bill":
                    return "Ultimo Recibo de Agua"
                break;
                case "common_expense_receipt":
                    return "Ultimo Recibo de Gastos Comunes"
                break;
                case "property_certificate":
                    return "Certificado de Propiedad o Certificado de Origen"
                break;
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