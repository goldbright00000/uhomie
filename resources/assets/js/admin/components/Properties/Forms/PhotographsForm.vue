<template>
	<div>
		<div>
	    <md-table>
	      <md-table-row>
	        <md-table-head>Foto</md-table-head>
          <md-table-head>Espacio</md-table-head>         
	        <md-table-head>Cover</md-table-head>
	        <md-table-head></md-table-head>
	      </md-table-row>

	      <md-table-row
	      	v-for="(f,i) in photos"
	      	:key="i"
	      >
	        <md-table-cell>
	        	<img :src="f.path" :alt="f.name" width="80px;" />
	        </md-table-cell>
          <md-table-cell>{{ f.space ? f.space.name : '-' }}</md-table-cell>
	        <md-table-cell v-html="f.cover > 0 ? 'Cover' : '-'"></md-table-cell>
	        <md-table-cell>
            <md-button  class="md-just-icon md-simple md-success" @click="editPhoto(f)">
              <md-icon>edit</md-icon>
              <md-tooltip md-direction="top">Editar</md-tooltip>
            </md-button>
	          <md-button  class="md-just-icon md-simple md-danger" @click="deletePhoto(f.id)">
	            <md-icon>delete</md-icon>
	            <md-tooltip md-direction="top">Eliminar</md-tooltip>
	          </md-button>
	        </md-table-cell>
	      </md-table-row>
	    </md-table>

      <md-dialog :md-active.sync="newPhotoForm" style="padding: 1rem;">
        <!-- new photo form -->
        <md-dialog-title>Agregar Fotografía</md-dialog-title>

        <div class="cover-item-preview">
            <div class="image-preview main-image-preview">
                <div class="close-btn"></div>
                <md-button 
                  v-if="!imagePreview" class="md-primary" @click="launchFilePicker">ELEJIR FOTO</md-button>
                <div v-else>
                  <img :style="'transform: rotate('+ rotate +'deg)'" :src="imagePreview.path" style="width: 100%; max-width: 300px" @click="launchFilePicker"/>
                </div>
                <input 
                  type="file" 
                  name="cover_image" 
                  id="image-upload" 
                  ref="newPhotoInput" 
                  class="image-upload" 
                  accept="image/*" 
                  v-on:change="photoPreview"
                  style="display: none;"
                  />
                <input type="hidden" class="image-id">
                <input type="hidden" class="cover" value="1">
                <input type="hidden" class="image-name" value="cover_image">
                <md-field>
                  <md-button @click="rotateRight" class="md-just-icon md-simple md-primary">
                    <md-icon>autorenew</md-icon>
                    <md-tooltip md-direction="top">Girar a la derecha</md-tooltip>
                  </md-button>
                  <md-button @click="rotateLeft" class="md-just-icon md-simple md-primary">
                    <md-icon>loop</md-icon>
                    <md-tooltip md-direction="top">Girar a la izquierda</md-tooltip>
                  </md-button>
                </md-field>
                <md-field>
                  <label for="movie">Espacio</label>
                  <md-select 
                    v-if="imagePreview"
                    v-model="newPhotoSpace" 
                    name="space" 
                    id="space"
                    style="margin: 1rem;"
                    >
                    <md-option 
                      v-for="(s,i) in spaces"
                      :key="i"
                      :value="s.id">{{ s.name }}</md-option>
                  </md-select>
                </md-field>
            </div>
            <div class="select-spaces-cover" photo_id="" space_id=""></div>
            <md-checkbox v-if="imagePreview" v-model="newPhotoCover" class="md-primary" style="margin: 1rem;">Cover</md-checkbox>
        </div>

        <md-dialog-actions>
          <md-button class="md-flat" @click="newPhotoForm = false">Cerrar</md-button>
          <md-button class="md-primary" @click="save">Grabar</md-button>
        </md-dialog-actions>
      </md-dialog>

      <md-button class="md-raised md-primary md-dense" @click="addPhotoForm"><md-icon>add</md-icon> Agregar foto</md-button>
	  </div>
	</div>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : csrf_token
  };
  export default {
    name: "photographs-form",
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          propertyId: null,
        },
        photos: [],
        isLoading: false,
        fullPage: true,
        loader: "dots",
        loaderColor: "#1ac036",
        dataSaved: false,
        newPhotoForm: false,
        newPhotoSpace: false,
        newPhotoCover: false,
        photoName: '',
        imagePreview: undefined,
        spaces: [],
        rotate: 0
      }
    },
    mounted() {
      //get-spaces
      const vm = this
      axios.get('/get-spaces')
      .then(function(response) {
        vm.spaces = response.data.spaces    
      })
    },
    methods: {
      launchFilePicker() {
        this.$refs.newPhotoInput.click()
      },
      addPhotoForm() {
        this.newPhotoForm = true
        this.rotate = false
        this.imagePreview = false             
      },
      photoPreview() {
        let file = this.$refs.newPhotoInput.files;

        if(this.imagePreview) {
          this.imagePreview.path = URL.createObjectURL(file[0])
          this.imagePreview.original = file[0]
          this.photoName = 'adm'
        } else {
          this.imagePreview = {
            id: null,
            path: URL.createObjectURL(file[0]),
            original: file[0]
          }
        }        
        console.log(this.imagePreview)
      },
      deletePhoto(idPhoto) {
        const formData = new FormData();
        formData.append('photo_id', idPhoto)

        if(confirm("Desea eliminar esta foto ?")) {
          const vm = this

          axios.post('/adm/properties/delete-photo', formData)
          .then(function(response) {
            console.log(response)
            vm.getPropertyData()
          })
        }
      },
      editPhoto(photo) {
        this.imagePreview = {
          id: photo.id,
          path: photo.path,
          original: null
        }
        this.newPhotoSpace = photo.space_id
        this.newPhotoCover = photo.cover > 0 ? true : false
        this.photoName = photo.name
        this.newPhotoForm = true        
      },
      editSave() {
        //Solo grabo cover o space
        
        const vm = this
        axios.post('/adm/properties/change-meta-photo', {
          photo_id: this.imagePreview.id,
          cover: this.newPhotoCover ? '1': '0',
          space_id: this.newPhotoSpace,
          rotate: this.rotate,
          property_id: this.form.propertyId
        })
        .then(function(response) {
          vm.isLoading = false
          vm.dataSaved = true
          vm.getPropertyData()
          vm.newPhotoForm = false
          vm.imagePreview = undefined
          vm.newPhotoSpace = undefined
          vm.newPhotoCover = false
          vm.rotate = 0
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      save() {
        if(!this.imagePreview.original) {
          //No cambio la imagen
          this.editSave()
          return true
        }
        const vm = this;
        vm.isLoading = true

        const formData = new FormData();
        formData.append('files', this.imagePreview.original, this.imagePreview.original.name);
        formData.append('property_id', vm.form.propertyId)
        formData.append('photo_id', this.imagePreview.id)
        formData.append('cover', this.newPhotoCover ? '1': '0')
        formData.append('space_id', this.newPhotoSpace)
        formData.append('photo_name', this.photoName)
        formData.append('rotate', this.rotate)

        axios.post('/adm/properties/save-prop-photos', formData)
        .then(function(response) {
          vm.isLoading = false
          vm.dataSaved = true
          vm.getPropertyData()
          vm.newPhotoForm = false
          vm.imagePreview = undefined
          vm.newPhotoSpace = undefined
          vm.newPhotoCover = false
          vm.rotate = 0
        })
        .catch(function(error) {
          console.log(error.response);
        });
      },      
      getPropertyData() {
        const vm = this;
        const propertyId = JSON.parse(vm.$route.params.propertyId)
        const url = '/adm/properties/' + propertyId
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.property = response.data.property
          vm.form = {
            propertyId: vm.property.id,
          }
          vm.photos = vm.property.photos
          console.log(vm.photos)
        });        
      },
      rotateRight(){
        this.rotate = this.rotate + 90
      },
      rotateLeft(){
        this.rotate = this.rotate - 90
      }
    },
    created() {
      this.getPropertyData()
    }
  }
</script>

<style scoped>
  .md-dialog {
    max-height: inherit;
  }
</style>