<template>
    <div class="project-photo">
        <!--Cargador de Imagen Cover-->
        <div class="columns">
            <div class="column is-9">
                 <div class="columns">
                    <div class="column">
                        <upload-photos-property 
                            v-if="cover"
                            v-bind:id="id"
                            v-bind:token="csrf"
                            v-bind:limit="1"
                            v-bind:active="cover"
                            v-bind:destroy="destroy"
                            @close="coverUpdate"
                            @photo="coverPhoto"
                            ></upload-photos-property>
                        <img-cover-property 
                            v-for="item in photo_cover" 
                            :key="item.photo_id" 
                            v-bind:info="item" 
                            v-bind:spaces="spaces"
                            v-bind:editing="editing"
                            @delphotocover="delPhotoCover"
                            @space_id="changeSpace"
                            ></img-cover-property>
                    </div>
                </div>

                <!--Cargador de imagenes de espacios-->
                <div class="columns is-multiline is-mobile" style="margin-bottom: 0px;">
                    <div class="column is-half-mobile is-one-third-widescreen" style="margin: 0"
                        v-for="item in photo_spaces" 
                        :key="item.photo_id">
                        <img-cover-property
                            v-bind:info="item"
                            v-bind:editing="editing"
                            v-bind:spaces="spaces"
                            @delphotocover="delPhotoSpace"
                            @space_id="changeSpace"
                            ></img-cover-property>
                    </div>
                    <div class="column is-half-mobile is-one-third-widescreen" v-for="item in items" v-if="item.active" :key="item.id" :style="editing ? 'margin-bottom: 0px;' : 'margin-bottom: 0px;display:none'">
                        <upload-photos-space-property 
                            :key="item.id"
                            v-bind:info="item"
                            v-bind:id="id"
                            v-bind:token="csrf"
                            v-bind:limit="1"
                            v-bind:active="space"
                            v-bind:destroyspace="destroyspace"
                            @closespace="spaceUpdate"
                            @photospace="spacePhoto"
                            ></upload-photos-space-property>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UploadPhotosProperty from '../../pages/UploadPhotosProperty';
import UploadPhotosSpaceProperty from '../../pages/UploadPhotosSpaceProperty';
import ImgCoverProperty from "../../pages/ImgCoverProperty.vue";
export default {
    name: 'ProjectPhoto',
    components: {
        UploadPhotosProperty,
        UploadPhotosSpaceProperty,
        ImgCoverProperty
    },
    props: {
        id: Number,
        photos: Object,
        spaces: Object,
        editing: {
            type: Boolean,
            default: false
        },
        membership: Object,
    },
    computed: {
        
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            cover: Boolean,
            space: true,
			destroy: false,
			destroyspace: false,
			//submit: true,
            photo_cover: [],
            //spaces: [],
			photo_spaces: [],
            items: [],
            limit: Number
				
            //imagesDir: imagesDir
        }
    },
    methods:{
        getPhotoCover(){
            this.photo_cover = this.photos.filter(photo => photo.cover == "1");
        },
        getPhotoSpaces(){
            this.photo_spaces = this.photos.filter(photo => photo.cover == "0");
        },
        getCover(){
            var item = this.photos.filter(photo => photo.cover == "1");
            if(item.length == 0){
                this.cover = true;
            } else {
                this.cover = false;
            }
            
        },
		/* Methods Cover  */
        coverUpdate(args){
            this.cover = args.value;
        },
        coverAdd(){
            this.cover = true;
            this.destory = false;
        },
        coverPhoto(value){
            this.photo_cover.push(value);
        },
        delPhotoCover(args){
            const params = new FormData()
            
            params.append('photo_id', args.id)

            axios.post('/delete-prop-photos', params, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                }).then((response) => {
                    toastr.success('La imagen a sido eliminada satisfactoriamente.');
                    this.cover = true;
                    this.photo_cover = [];
                }).catch((error) => {
                    
                        //console.log(error)
                });
		},
		/* Get Items */

		getItems(){
            var photosl = this.photo_spaces.length + this.photo_cover.length;
            if(photosl < this.limit){ 
                if(this.photo_cover.length > 0){
                    this.cover = false;
                }
                if(this.photo_spaces.length == 0){
                    this.items = [];
                    this.items.push(
                        {id: '1Dropzone', active : true},
                        {id: '2Dropzone', active : true},
                        {id: '3Dropzone', active : true});
                }
                if(this.photo_spaces.length == 1){
                    this.items.push(
                        {id: '1Dropzone', active : true},
                        {id: '2Dropzone', active : true});
                }
                if(this.photo_spaces.length >= 2){
                    this.items.push(
                        {id: '1Dropzone', active : true});
                }
            }
		},

		/*Methods Spaces */
		spaceUpdate(args){
            this.space = args.value;
		},
		changeSpace(space){
			axios.get('/change-space', {
				params: {
					photo_id: space.photo,
					space_id: space.space
				},
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                //console.log(response)
            }).catch((error) => {
                //console.log(error)
            });
		},
        spacePhoto(value){
			//console.log(value);
			this.photo_spaces.push(value);
			this.space = true;
			this.destroyspace = false;
			var item = this.items.filter(item => item.active == true);
            //console.log(item.length);
            var photosl = this.photo_spaces.length + this.photo_cover.length;
            if(photosl < this.limit){ 
                if(this.photo_spaces.length >= 3){
                    this.items.push(
                        {id: '4Dropzone', active : true});
                }
                else if(item.length == 0){
                    this.items.push(
                        {id: '4Dropzone', active : true});
                }
            }
		},
		delPhotoSpace(args){
            const params = new FormData()
            
            params.append('photo_id', args.id)

            axios.post('/delete-prop-photos', params, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                }).then((response) => {
					toastr.success('La imagen a sido eliminada satisfactoriamente.')
					this.photo_spaces = this.photo_spaces.filter(photo_spaces => photo_spaces.id != args.id);
					var item = this.items.filter(item => item.active == true);
					if(item.length == 0){
						this.items.push({id: '4Dropzone', active : true})
					}
                }).catch((error) => {
					//console.log(error)
                });
        },
        /* Limit Photos */
        getLimit(){
            this.limit = JSON.parse(this.membership.features).photos_per_project;
        }

    },
    mounted() {
		//this.getSpaces();
        this.getPhotoCover();
        this.getPhotoSpaces();
        this.getCover();
        this.getLimit();
        this.getItems();
    },
}
</script>