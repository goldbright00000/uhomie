<template>
  <form>
    <md-card>
      <md-card-header data-background-color="black">
        <h4 class="title">Datos del Usuario</h4>
        <p class="category">Información general del usuario</p>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Nombres</label>
              <md-input v-model="form.firstname" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Apellidos</label>
              <md-input v-model="form.lastname" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Documento de Identidad</label>
              <md-input v-model="form.document" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Telefono</label>
              <md-input v-model="form.phone" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Correo</label>
              <md-input v-model="form.email" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Ciudad</label>
              <md-input v-model="form.city" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Dirección</label>
              <md-input v-model="form.address" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>N° / Casa / Apto.</label>
              <md-input v-model="form.address_details" type="text" disabled></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-100">
            <div class="md-layout-item md-small-size-100 md-size-50">
              <div class="md-layout-item md-small-size-100 md-size-100">
                <h3>Documentos</h3>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <ul>
                  <li v-for="file in form.files" :key="file.id">
                    <a :href="'/get-file/?path='+file.id" download="true">{{file.name}}</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              
            </div>
          </div>
        </div>

      </md-card-content>
    </md-card>
  </form>
</template>
<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
  name: "show",
  props: {
    dataBackgroundColor: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      form:{
        firstname: null,
        lastname: null,
        email: null,
        phone_code: null,
        phone: null,
        city: null,
        address: null,
        address_details: null,
        document: null,
        files: null
      }
    };
  },
  methods:{
    loadDataUser() {
      const vm = this;
      vm.isLoading = true
      const userId = JSON.parse(vm.$route.params.userId)
      var url =  '/adm/users/' + userId
      axios.get(url)
      .then((response) => {
        vm.isLoading = false
        if (response.data.user !== null) {
          vm.form = {
            userId : response.data.user.id ,
            firstname: response.data.user.firstname,
            lastname: response.data.user.lastname,
            email:response.data.user.email,
            active:response.data.user.active,
            phone: '+'+response.data.user.phone_code+ '-' +response.data.user.phone,
            address: response.data.user.address,
            address_details: response.data.user.address_details,
            city: response.data.user.city.name,
            document: response.data.user.document_type + ' - ' + response.data.user.document_number,
            files: response.data.user.files
          }
        }else{
            vm.$router.push('/users')
        }
      });
    },
  },
  mounted(){
    this.loadDataUser();
  }
};
</script>
<style>
</style>