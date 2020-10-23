<template>
  <div>
    <v-card>
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
        <download-excel
          :data="properties"
          :fields="json_fields"
          :name="excelName"
          worksheet="Usuarios"
          class="ml-5"
          >
          <img src="/images/admin/excel.png" alt="Exportar" width="50" style="cursor: pointer"/>
        </download-excel>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="properties"
        class="elevation-1"
        :loading="loading"
        :search="search"
        rows-per-page-text="Propiedades por página"
        :rows-per-page-items='[25, 50, 100, {"text":"Todas","value":-1}]'
      >
        <template v-slot:items="props">
          <td>{{ props.item.id }}</td>
          <td>
            <a :href="'/explorar/'+props.item.id+'/'+props.item.slug" target="_BLANK">
              {{ props.item.name }}
            </a>
          </td>
          <td>
            <a @click="$router.push('/users/'+props.item.owner_id+'/show')">
              {{ props.item.owner_name }}
            </a>
          </td>
          <td class="text-xs-center">
            {{ props.item.status_name }}
            <small style="color: red;" v-if="props.item.active == 0"> | -no activa-</small>
          </td>
          <td class="text-xs-center">{{ props.item.views }}</td>
          <td class="text-xs-center caption">
            {{ props.item.updated_at }}
          </td>
          <td class="text-xs-center">
            <v-menu offset-y v-if="user_role != 3">
              <template v-slot:activator="{ on }">
                <v-btn                  
                  icon
                  flat
                  v-on="on"
                >
                  <v-icon>more_vert</v-icon>
                </v-btn>
              </template>

              <v-list>
                <v-list-tile 
                  v-if="props.item.status == 1"
                  @click="publish(props.item)">
                  <v-list-tile-avatar>
                    <v-icon>play_circle_filled</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>Publicar</v-list-tile-title>
                </v-list-tile>
                <v-list-tile 
                  v-else
                  @click="pause(props.item)">
                  <v-list-tile-avatar>
                    <v-icon>pause</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>Pausar</v-list-tile-title>
                </v-list-tile>
                <v-list-tile :to="{path: '/properties/' + props.item.id + '/edit'}">
                  <v-list-tile-avatar>
                    <v-icon>edit</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>Editar</v-list-tile-title>
                </v-list-tile>
                <v-list-tile :to="{path: '/properties/' + props.item.id + '/delete'}">
                  <v-list-tile-avatar>
                    <v-icon>delete</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>Eliminar</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>          
          </td>
        </template>
      </v-data-table>
    </v-card>

    <md-snackbar 
      md-position="center"
      :md-duration="3000" 
      :md-active.sync="showSnackbar" md-persistent>
      <span>{{ snackbarText}}</span>
      <md-button class="md-primary" @click="showSnackbar = false">x</md-button>
    </md-snackbar>
  </div>
</template>
<script>
  const user_role = window.ROLE;

import axios from 'axios'

export default {
  name: "properties-table",
  props: ["tableHeaderColor", "propertiesList"],
  data() {
    return {
      user_role: user_role,
      showSnackbar: false,
      snackbarText: '',
      search: '',
      properties: [],
      loading: false,
      headers: [
        { text: 'ID', value: 'id', align: 'center' },
        { text: 'Nombre', align: 'left', value: 'name' },
        { text: 'Propietario', value: 'owner_name' },
        { text: 'Estado', value: 'status' },
        { text: 'Visitas', value: 'viws' },
        { text: 'Última act.', value: 'updated_at' },
        { text: 'Acciones', value: '' }
      ],
      json_fields: {
        'ID': 'id',
        'Propertie': 'name',
        'Owner_id': 'owner_id',
        'Owner': 'owner_name',
        'Owner_email': 'owner_email',
        'Status': 'status_name',
        'Active' : {
          field: 'active',
          callback: (a) => {
            return a > 0 ? 'activa' : 'no-activa'
          }
        },
        'Updated_at': 'updated_at'        
      },
    };
  },
  watch: {
    propertiesList: function() {
      this.properties = this.propertiesList
    }
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    },
    excelName() {
      return 'properties_'+new Date().toJSON().slice(0,10)+'.xls'
    }

  },
  methods: {
      getProperties() {
        const propertiesUrl = '/adm/properties';
        const vm = this
        this.loading = true
        axios.get(propertiesUrl)
        .then((response) => {
          vm.properties = response.data.records
          vm.loading = false
          console.log(vm.properties)
        });
      },
      pause(property) {
        const vm = this;
        this.loading = true
        const url = '/adm/properties/'+ property.id +'/pause';
        axios.post(url, {
          propertyId : property.id
        })
        .then(function(response) {
          vm.loading = false
          property.status = response.data.status
          property.status_name = response.data.status_name
          vm.snackbarText = 'Propiedad: '+property.id+', pausada con éxito.'
          vm.showSnackbar = true
        })
        .catch(function(error) {
          vm.loading = false
          vm.snackbarText = 'Hubo un error al intentar publicar la propiedad, error.'
          console.log(error)
          vm.showSnackbar = true
        });
      },
      publish(property) {
        const vm = this;
        this.loading = true
        const url = '/adm/properties/'+ property.id +'/publish';
        axios.post(url, {
          propertyId : property.id
        })
        .then(function(response) {
          vm.loading = false
          property.status = response.data.status
          property.status_name = response.data.status_name
          vm.snackbarText = 'Propiedad: '+property.id+', publicada con éxito.'
          vm.showSnackbar = true
        })
        .catch(function(error) {
          vm.loading = false
          vm.snackbarText = 'Hubo un error al intentar publicar la propiedad, error.'
          console.log(error)
          vm.showSnackbar = true
        });
      },
  },
  created() {
    this.getProperties();
  }
};
</script>
