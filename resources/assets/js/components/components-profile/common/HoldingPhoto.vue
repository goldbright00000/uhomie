<template>
    <div class="holding-photo">
        <div v-if="thisPhoto.cover == '1'">
            <h1 class="form-title">Foto de portada <span style="font-style: italic;font-weight: 400;"> ( para cambiar de portada puedes arrastrar las fotos )</span> </h1>
        </div>
        <label :for="name">
            <div class="preview" :style="styles">
                <span>Elija una Foto</span>
            </div>
        </label>
        <input @change="setFile($event)" class="photo-input-property" type="file" :id="name" :name="name" max="1" v-bind:accept="mines">
        
        <div class="field">
            <div class="control">
                <div class="select is-info.personal" v-if="spaces" :style="{ width: false ? '85%' : '100%' }">
                    <select v-model="photo.space.id" @change="$emit('photoChange', { photo: thisPhoto, file: null })">
                        <option
                        :value="0"
                        :selected="0 == space_id"
                        >Que espacio representa?</option>
                        <option
                        v-for="item in spaces"
                        :value="item.id" :key="item.id"
                        :selected="item.id == space_id"
                        >{{item.text}}</option>
                    </select>
                </div>
                <!--<img v-if="file" :src="imagesDir+'/icono_guardar.png'" @click="uploadphoto" style="margin-top: 8px;"/>-->
            </div>
        </div>
    </div>
</template>
<script>


    import base from '../../../profiles/base';
    const _token = document.getElementById('_token').value;

    export default {
        extends: base,
        name: 'HoldingPhoto',
        props: {
            photo: {
                type: Object,
                default: () => ({
                    id: null,
                    space: {
                        id: 0,
                    },
                    path: null,
                    cover: 0
                })
            },
            propertyid: Number,
            spaces: Array, 
            mines: {
                type: String,
                default: 'image/*'
            }
        },
        data: function () {
            return { file: null, thisPhoto: this.photo, name: this.makeid() }
        },
        computed: {
            space_id () {
                return this.thisPhoto.space.id
            },
            styles () {
                var url = null
                if (this.thisPhoto.path) {
                    url = this.thisPhoto.path
                }
                if (this.file) {
                    url = URL.createObjectURL(this.file)
                }

                return {
                    backgroundImage: 'url(' + url + ')'
                }
            }
        },
        methods: {
            setFile(e) {
                const files = e.target.files
                if(files.length) {
                    if(e.target.accept == files[0].type || e.target.accept == 'image/*') {
                        this.file = files[0]
                        this.$emit('photoChange', { photo: this.thisPhoto, file: this.file }) 
                    } else {
                        toastr.error('EL formato es invalido, suba un archivo de tipo: ' + e.target.accept.split('/')[1])
                    }
                }
            },
            uploadphoto(e) {
                const photos = e.target.files
                if(photos.length) {
                    if(e.target.accept == files[0].type || e.target.accept == 'image/*') {
                        
                        const formData = new FormData()

                        formData.append(e.target.name, files[0])
                        formData.append('action', 'upload-prophoto-document')
                        if (this.thisPhoto.verified) {
                            formData.append('verified', 0)
                        }
                        formData.append('_token', _token)

                        axios.post('/users/tenant/registration/third-step/four', formData, {
                            headers: {
                                'Content-type': 'multipart/form-data',
                            }
                        }).then((response) => {
                            toastr.success('Los datos se han salvado satisfactoriamente.')
                            this.thisPhoto = response.data.photo
                            this.$Progress.finish()
                        }).catch((error) => {
                            if(error.response) {
                                let info = error.response.data;
                                let status = error.response.status;
                                this.$Progress.fail()
                                toastr.error(info)
                            }else {
                                console.log(error)
                            }
                        });

                    } else {
                        toastr.error('EL formato es invalido, suba un archivo de tipo: ' + e.target.accept.split('/')[1])
                    }
                }
            },
            makeid () {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for (var i = 0; i < 5; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                return text;
            }
        }
    }
</script>

<style scoped>

 h1.form-title {
    font-weight: 500;
    font-size: 1.1rem;
    font-style: italic;
    position: relative;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
 }


  .control {
    flex-basis: 100%;
    width: 100%;
}

.form-title:after {
    content: "";
    position: absolute;
    bottom: 0;
    z-index: 1;
    left: 0;
    height: 3px;
    width: 5rem;
    background: #ffd900;
}

 .photo-input-property {
    display: none;
 }

 .holding-photo {
    display: flex;
    flex-flow: column;
}

.preview {
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;
    width: 100%;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview span {
    z-index: 5;
    opacity: 0.8;
    cursor: pointer;
    background-color: #40c8ff;
    width: 100px;
    height: 25px;
    font-size: 10px;
    line-height: 25px;
    text-transform: uppercase;
    text-align: center;
}
</style>