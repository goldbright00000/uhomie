const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const _getPhotos = document.getElementById('photo_uri').value;
const _putSpaces = document.getElementById('change_space_uri').value;

import UploadPhotosProperty from "../components/pages/UploadPhotosProperty.vue";
import UploadPhotosSpaceProperty from "../components/pages/UploadPhotosSpaceProperty.vue";
import ImgCoverProperty from "../components/pages/ImgCoverProperty.vue";
//const imagesDir = document.getElementById("images-dir").value;
const photos = new Vue({
    el: '#photos',
    components: {
        UploadPhotosProperty,
		ImgCoverProperty,
		UploadPhotosSpaceProperty
    },
    computed: {
        submit(){
			var space_cover = this.photo_cover.filter(photo_cover => photo_cover.space_id == null);
			var space_photo = this.photo_spaces.filter(photo_spaces => photo_spaces.space_id == null);
			if((this.photo_cover.length == 1 && this.photo_spaces.length > 2) && (space_cover.length == 0 && space_photo.length == 0)) {
				return false;
			} else {
				return true;
			}
		}
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
            const params = new FormData()
            
            params.append('_token', _token);
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
		/* get Spaces */
		getSpaces(){
            axios.get('/get-spaces', {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                this.spaces = response.data.spaces
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
				if(response.data.cover.length > 0){
					this.cover = false;
				}
				if(response.data.photos.length == 0){
					this.items = [];
					this.items.push(
						{id: '1Dropzone', active : true},
						{id: '2Dropzone', active : true},
						{id: '3Dropzone', active : true});
				}
				if(response.data.photos.length == 1){
					this.items.push(
						{id: '1Dropzone', active : true},
						{id: '2Dropzone', active : true});
				}
				if(response.data.photos.length >= 2){
					this.items.push(
						{id: '1Dropzone', active : true});
				}

				this.photo_cover = response.data.cover;
				this.photo_spaces = response.data.photos;
                //console.log(response)
            }).catch((error) => {
                //console.log(error)
            });
		},

		/*Methods Spaces */
		spaceUpdate(args){
            this.space = args.value;
		},
		changeSpace(space){
			axios.get(_putSpaces, {
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
			if(this.photo_spaces.length >= 3){
				this.items.push(
					{id: '4Dropzone', active : true});
			}
			else if(item.length == 0){
				this.items.push(
					{id: '4Dropzone', active : true});
			}
		},
		delPhotoSpace(args){
            const params = new FormData()
            
            params.append('_token', _token);
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
    },
    mounted() {
		this.getSpaces();
		this.getPhotos();
    },
});

import $ from 'jquery';
import jQuery from 'jquery';

var tips = [
	"Toma foto con luz natural, abre las ventanas y persianas.",
	"Toma fotos de al menos 1024 x 683 px, cuanto más grande sean las fotos mejor.",
	"Saca fotos con orientación horizontal: cuando la orientación es vertical, siempre es más difícil apreciar cómo es el espacio.",
	"Destaca las zonas y espacios más relevantes de tu Propiedad. Tu Jardin, Tu espaciosa habitación principal.",
	"Evita tomar fotos de tus espacios con objetos, mascotas u otros aspectos que luzcan desordenados.",
	"Cuanto más fotos agregas mejor. Al menos 1 por cada espacio de tu propiedad. Muestra todos las zonas, cada detalle importa"
]

$("#btn-next-tip").click(function(){

	switch ($('input#current-step').val()) {

	  case '0':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[1]);
	  	$('input#current-step').val(1)
	    break;
	  case '1':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[2]);
	  	$('input#current-step').val(2)
	    break;
	  case '2':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[3]);
	    $('input#current-step').val(3)
	    break;
	  case '3':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[4]);
	    $('input#current-step').val(4)
	    break;
	  case '4':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[5]);
	    $('input#current-step').val(5)
	    break;
	  case '5':
	    $('#modal-text').text("");
	    $('input#current-step').val(0)
	    $('.modal-tip').removeClass('is-active');
	    break;
	}
})

$("#btn-back-tip").click(function(){

	switch ($('input#current-step').val()) {

	  case '1':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[0]);
	  	$('input#current-step').val(0)
	    break;
	  case '2':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[1]);
	  	$('input#current-step').val(1)
	    break;
	  case '3':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[2]);
	    $('input#current-step').val(2)
	    break;
	  case '4':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[3]);
	    $('input#current-step').val(3)
	  break;
	  case '5':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[4]);
	    $('input#current-step').val(4)
	  break;
	}
})

// $(".btn-close").click(function(){
// 	$('.modal-tip').removeClass('is-active');
// })

$(function(){
	
	$('.modal-tip').addClass('is-active');
	$('#modal-text').text("");
	$('#modal-text').text(tips[0]);
	$('input#current-step').val(0)
	
})
