<template>
    <div class="property-photo">
        <!--Cargador de Imagen Cover-->
        <div class="columns">
            <div class="column is-9">
                 <div class="columns">
                    <div class="column">
                            <upload-photo-company 
                                v-if="cover"
                                v-bind:id="info.id"
                                v-bind:save="save_logo"
                                v-bind:del="del_logo"
                                v-bind:token="csrf"
                                v-bind:limit="1"
                                v-bind:active="cover"
                                v-bind:destroy="destroy"
                                @close="coverUpdate"
                                @photo="coverPhoto"
                                ></upload-photo-company>
                        <img-cover-logo
                            v-for="item in logo" 
                            :key="item.id" 
                            v-bind:info="item"
                            v-bind:editing="editing"
                            @delphotocover="delPhotoCover"
                            ></img-cover-logo>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
import UploadPhotoCompany from "../../pages/UploadPhotoCompany";
import ImgCoverProperty from "../../pages/ImgCoverProperty";
import ImgCoverLogo from "../../pages/ImgCoverLogo.vue";
export default {
    name: 'LogoPhoto',
    components: {
        UploadPhotoCompany,
        ImgCoverProperty,
        ImgCoverLogo
    },
    props: {
        editing: {
            type: Boolean,
            default: false
        },
        info: Object,
        logo: Array,
        del_logo: String,
        get_logo: String,
        save_logo: String
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            cover: true,
            space: true,
			destroy: false,
            destroyspace: false,
			editing: true,
			//submit: true,
            spaces: [],
			photo_spaces: [],
			items: []
				
            //imagesDir: imagesDir
        }
    },
    methods:{
		/* Methods Cover  */
        coverUpdate(args){
            this.cover = args.value;
        },
        coverAdd(){
            this.cover = true;
            this.destory = false;
        },
        coverPhoto(value){
            this.logo.push(value);
        },
        delPhotoCover(args){
            console.log(args);
            const params = new FormData()
            
            params.append('_token', _token);
            params.append('photo_id', args.id);

            axios.post(this.del_logo, params, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                }).then((response) => {
                    toastr.success('La imagen a sido eliminada satisfactoriamente.');
                    this.cover = true;
                    this.logo = [];
                }).catch((error) => {
                    //console.log(error)
                });
		},
		
		/* Get Photos */

		getPhotos(){
			axios.get(this.get_logo, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                if(response.data.logo.length == 1){
                    this.cover = false;
                }
                
                this.logo = response.data.logo;
                //console.log(response)
            }).catch((error) => {
                //console.log(error)
            });
		},
    },
    mounted() {
		this.getPhotos();
    },
}
</script>