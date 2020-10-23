const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const _getPhotos = document.getElementById('photo_uri').value;
const _savePhotos = document.getElementById('photo_save').value;
const _delPhotos = document.getElementById('photo_del').value;

import UploadPhotoCompany from "../components/pages/UploadPhotoCompany.vue";
import ImgCoverProperty from "../components/pages/ImgCoverProperty.vue";
import ImgCoverLogo from "../components/pages/ImgCoverLogo.vue";
//const imagesDir = document.getElementById("images-dir").value;
const photos = new Vue({
    el: '#logo',
    components: {
        UploadPhotoCompany,
        ImgCoverProperty,
        ImgCoverLogo
    },
    computed: {
        
    },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            cover: true,
            space: true,
			destroy: false,
            destroyspace: false,
            save: _savePhotos,
            del: _delPhotos,
			editing: true,
			//submit: true,
            photo_cover: [],
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
            this.photo_cover.push(value);
        },
        delPhotoCover(args){
            console.log(args);
            const params = new FormData()
            
            params.append('_token', _token);
            params.append('photo_id', args.id);

            axios.post(_delPhotos, params, {
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
		
		/* Get Photos */

		getPhotos(){
			axios.get(_getPhotos, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                if(response.data.logo.length == 1){
                    this.cover = false;
                }
                
                this.photo_cover = response.data.logo;
                //console.log(response)
            }).catch((error) => {
                //console.log(error)
            });
		},
    },
    mounted() {
		this.getPhotos();
    },
});