<template>
  <div class="content">
    <div class="md-layout">
      <div @click="getUserList(tenant)" class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-20 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >person</md-icon>
          </template>
          <template slot="content">
            <p class="category">Arrendatarios</p>
            <h3 class="title">{{ descriptors.tenants }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="getUserList(owner)" class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-20 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >person</md-icon>
          </template>
          <template slot="content">
            <p class="category">Arrendadores</p>
            <h3 class="title">{{ descriptors.owners }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="getUserList(agent)" class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-20 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >person</md-icon>
          </template>
          <template slot="content">
            <p class="category">Agentes</p>
            <h3 class="title">{{ descriptors.agents }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="getUserList(service)" class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-20 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >person</md-icon>
          </template>
          <template slot="content">
            <p class="category">Servicios</p>
            <h3 class="title">{{ descriptors.services }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="getUserList(aval)" class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-20 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >person</md-icon>
          </template>
          <template slot="content">
            <p class="category">Aval</p>
            <h3 class="title">{{ descriptors.collateral }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <v-card>
          <v-card-title style="background-color: #111; color: white;">
            Usuarios
            <v-spacer></v-spacer>
            <v-text-field
              dark
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>

            <download-excel
              :data="users"
              :fields="json_fields"
              name="excelName"
              worksheet="Usuarios"
              class="ml-5"
              >
              <img src="/images/admin/excel.png" alt="Exportar" width="50" style="cursor: pointer"/>
            </download-excel>
          </v-card-title>

          <v-data-table
            :headers="headers"
            :items="users"
            :search="search"
            :loading="loading ? '' : false"
            :rows-per-page-items="rowsPerPageItems"
            :expand="expand"
            item-key="id"
          >
            <template v-slot:items="props">
              <tr>
                <td class="px-0">
                  <v-menu>
                    <template #activator="{ on: menu }">
                      <v-tooltip bottom>
                        <template #activator="{ on: tooltip }">
                          <v-btn 
                            icon small
                            v-on="{ ...tooltip, ...menu }"
                          >
                            <v-icon color="primary">more_vert</v-icon>
                          </v-btn>
                        </template>
                        <span>Operaciones</span>
                      </v-tooltip>
                    </template>
                    <v-list>
                      <v-list-tile
                        :to="{path: '/users/' + props.item.id + '/edit'}"
                      >
                        <v-list-tile-avatar>
                          <v-icon>edit</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-title>Editar</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile
                        :to="{path: '/users/' + props.item.id + '/delete'}"
                      >
                        <v-list-tile-avatar>
                          <v-icon color="error">delete</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-title>Eliminar</v-list-tile-title>
                      </v-list-tile>
                    </v-list>
                  </v-menu>                  
                </td>
                <td class="px-0">
                  <v-btn 
                    icon small
                    @click="props.expanded = !props.expanded"
                  >
                    <v-icon v-text="props.expanded ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"></v-icon>
                  </v-btn>
                </td>
                <td>{{ props.item.id }}</td>
                <td class="text-xs-center">
                  {{ props.item.lastname }}
                </td>
                <td class="text-xs-center">
                  {{ props.item.firstname }}
                </td>
                <td class="text-xs-right">{{ props.item.email }}</td>
                <td class="text-xs-right">
                  <ul>
                    <div v-for="(membership,i) in props.item.memberships"  >
                        <li style="text-align:left;">
                          <router-link :to="{path: '/memberships/' + membership.id + '/users/' + props.item.id }">
                            <span v-if="membership.name == 'Default'" >
                              {{ membership.role.name }} - Perfil Incompleto
                            </span>
                            <span v-else >
                              {{ membership.role.name }} - {{ membership.name }}
                            </span>
                          </router-link>
                        </li>
                    </div>
                  </ul>
                </td>
                <td class="text-xs-right" v-if="userIsAdmin">
                  <v-btn icon :to="{path: '/users/' + props.item.id + '/edit'}">
                    <v-icon color="success">edit</v-icon>
                  </v-btn>
                  <v-btn icon :to="{path: '/users/' + props.item.id + '/delete'}">
                    <v-icon color="red">delete</v-icon>
                  </v-btn>
                </td>
              </tr>
            </template>
            <template v-slot:expand="props">
              <v-layout row wrap class="pa-4">
                <v-flex xs12 sm4>
                  <h4 class="caption font-weight-bold">{{ props.item.lastname+', '+props.item.firstname }}</h4>
                  <v-avatar
                    v-if="props.item.photo"
                    tile
                    size="22"
                    color="grey lighten-4"
                  >
                    <img :src="props.item.photo" alt="avatar">
                  </v-avatar>
                  <ul>
                    <li>ID: <b>{{ props.item.id }}</b></li>
                    <li>Document: <b>{{ props.item.document_type+': '+props.item.document_number }}</b></li>
                    <li v-if="props.item.phone">TEL: <b>{{ '('+props.item.phone_code + ') '+props.item.phone }}</b></li>
                  </ul>
                </v-flex>
                <v-flex xs12 sm4>                  
                  <h5>Archivos: </h5>
                  <ol v-if="props.item.files.length > 0">
                    <li
                      v-for="(f,i) in props.item.files"
                      :key="f.id"
                    >
                      <a @click="modalFile(f)">{{f.name}}</a>
                    </li>                    
                  </ol>
                </v-flex>
                <v-flex xs12 sm4>
                  <h5>Pagos:</h5>
                  <ol v-if="props.item.payments.length > 0">
                    <li
                      v-for="(p,i) in props.item.payments"
                      :key="p.id"
                    >
                      {{ p.details }} <small>({{ p.created_at }})</small>
                    </li>                    
                  </ol>
                </v-flex>
              </v-layout>
            </template>
            <template v-slot:no-results>
              <v-alert :value="true" color="error" icon="warning">
                No se encontraron resultados para: "{{ search }}"
              </v-alert>
            </template>
          </v-data-table>
        </v-card>
      </div>
    </div>
    
    <file-dialog v-bind:showDialog="showDialog" v-bind:files="files" @hideDialog="hideDialog"></file-dialog>

    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Agregar Usuario" to="/users/create">
      <md-icon>add</md-icon>
      <md-tooltip md-direction="top">Nuevo Usuario</md-tooltip>
    </md-button>
  </div>
</template>

<script>
  const user_role = window.ROLE;

import axios from 'axios'
const usersUrl = '/adm/users';
import { UsersTable, StatsCard, FileDialog } from "../../components";

export default {
  components: {
    UsersTable,
    StatsCard,
    FileDialog
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    },
    excelName() {
      return 'usuarios_'+new Date().toJSON().slice(0,10)+'.xls'
    }
  },
  data: () => ({
    dato: false,
    showDialog: false,
    expand: false,
    bottomPosition: 'md-bottom-right',
    files: {},
    headers: [
      { text: '', sortable: false },
      { text: '', sortable: false },
      { text: 'ID', align: 'center', sortable: false, value: 'id' },
      { text: 'Apellido', value: 'lastname', sortable: true, },
      { text: 'Nombre', value: 'firstname', sortable: true, },
      { text: 'Email', value: 'email' },
      { text: 'Roles', value: 'role' },
      { text: '', value: '' }
    ],
    rowsPerPageItems: [25,50,100,{"text":"Filas por página","value":-1}],
    descriptors:{
      tenants:0,
      owners:0,
      agents:0,
      services:0
    },
    search: '',
    users: undefined,
    loading: true,
    tenant: 1,
    owner: 2,
    agent: 3,
    service: 4,
    aval: 5,
    json_fields: {
      'ID': 'id',
      'Apellido': 'lastname',
      'Nombre': 'firstname',
      'Email' : 'email',
      'Codigo tel': 'phone_code',
      'Telefono': 'phone',
      'Tipo': 'document_type',
      'Documento': 'document_number',
      'Dirección': 'address',
      'Primer role': 'once_role',
      'memberships' : {
        field: 'memberships',
        callback: (m) => {
          var memberships = ''
          for (var i = m.length - 1; i >= 0; i--) {
            if (m[i].name == 'Default') {
              memberships+='|'+m[i].role.name+': incompleto|'
            } else {
              memberships+='|'+m[i].role.name+'|'
            }
          }
          return memberships;
        }
      },
    },
  }),
  methods: {
    fetch: function () {
      this.loading = true;
      axios.get(usersUrl)
      .then((response) => {
        this.loading = false
        this.users = response.data.records
        console.log(this.users)
      });
    },
    getUsersDescriptors(){
      const vm = this;
      this.loading = true

      var url = '/adm/users/descriptors'
      axios.get(url)
      .then((response) => {
        vm.loading = false
          vm.descriptors = response.data
          console.log(vm.descriptors)
      });
    },
    getUserList(role){
      const vm = this;
      this.loading = true
      var url = '/adm/users/role/'+role
      axios.get(url)
      .then((response) => {
        console.log(response)
        this.loading = false
        this.users = response.data.records
      });
    },
    modalFile(value){
      this.files = {
        id: value.id,
        name: value.name,
        verified: Boolean(Number(value.verified)),
        verified_ocr: Boolean(Number(value.verified_ocr)),
        val_date: Boolean(Number(value.val_date)),
        factor: value.factor,
        path: value.path
      },
      this.showDialog = true
    },
    hideDialog(value){
      this.showDialog = false
    }
  },
  created(){
    this.getUsersDescriptors()
    this.fetch();
  }
};
</script>
<style media="screen" lang="css">

  .cursor-pointer {
    cursor: pointer;
  }

.md-button.md-fab.flotante {
    right: 30px;
    bottom: 30px;
    position: fixed;
    z-index: 4;
    height: 55px;
    min-width: 55px;
    width: 55px;
    line-height: 55px;
}
.md-dialog {
    max-width: 768px;
  }
.md-switch {
    display: flex;
  }
</style>
