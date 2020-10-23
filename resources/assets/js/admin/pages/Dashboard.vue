<template>
  <div class="content">
    <div class="md-layout">
      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-33">

        <chart-card
          :chart-data="propertiesForDaysChart.data"
          :chart-options="propertiesForDaysChart.options"
          :chart-type="'Line'"
          data-background-color="black">
          <template slot="content">
            <h4 class="title">Propiedades Publicadas por Dia</h4>
          </template>

          <template slot="footer">

          </template>
        </chart-card>
      </div>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-33">
        <chart-card
          :chart-data="newsLettersSubscriptionChart.data"
          :chart-options="newsLettersSubscriptionChart.options"
          :chart-responsive-options="newsLettersSubscriptionChart.responsiveOptions"
          :chart-type="'Bar'"
          data-background-color="black">
          <template slot="content">
            <h4 class="title">Subscripciones Mensuales (Newsletter)</h4>

          </template>

          <template slot="footer">

          </template>
        </chart-card>
      </div>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-33">
        <chart-card

          :chart-data="usersChart.data"
          :chart-options="usersChart.options"
          :chart-type="'Line'"
          data-background-color="black">
          <template slot="content">
            <h4 class="title">Usuarios registrados en la última semana</h4>
          </template>

          <template slot="footer">

          </template>
        </chart-card>
      </div>      
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-50">
        <router-link :to="{ name: 'Propiedades'}">
          <stats-card data-background-color="black">
            <template slot="header">
              <md-icon >store</md-icon>
            </template>

            <template slot="content">
              <p class="category">Propiedades activas</p>
              <h3 class="title">
                {{ totalProperties }}
              </h3>
              <div style="overflow-x:auto;">
                <h6 style="text-align: left;">Últimas 5 añadidas:</h6>
                <table>
                  <tr>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                  </tr>              
                  <tr
                    v-for="(p,i) in lastProperties"
                    :key="i"
                  >
                    <td>{{ p.id }}</td>
                    <td><a :href="'/explora/'+p.id+'/'+p.slug" target="_BLANK"><small>{{ p.name }}</small></a></td>
                    <td>{{ p.status }}</td>
                  </tr>
                </table>
              </div>
            </template>

            <template slot="footer">

            </template>
          </stats-card>
        </router-link>
      </div>

      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-50">
        <router-link :to="{ name: 'Usuarios'}">
          <stats-card data-background-color="black">
            <template slot="header">
              <md-icon >person</md-icon>
            </template>

            <template slot="content">
              <p class="category">Usuarios activos</p>
              <h3 class="title">
                {{ totalUsuarios }}
              </h3>
              <div style="overflow-x:auto;">
                <h6 style="text-align: left;">Últimos 5 registrados:</h6>
                <table>
                  <tr>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                  </tr>              
                  <tr
                    v-for="(u,i) in lastUsers"
                    :key="i"
                  >
                    <td>{{ u.id }}</td>
                    <td>{{ u.lastname }}, {{ u.username }} </td>
                    <td>{{ u.email }}</td>
                  </tr>
                </table>
              </div>
            </template>

            <template slot="footer">

            </template>
          </stats-card>
        </router-link>
      </div>
      

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import {
  StatsCard,
  ChartCard,
  NavTabsCard,
  NavTabsTable,
  OrderedTable
} from "../components";

const descriptorsUrl = '/adm/dashboard/descriptors';

export default {
  components: {
    StatsCard,
    ChartCard,
    Loading
  },
  data() {
    return {
      propertiesForDaysChart: {
        data: {
          labels: [],
          series: [[0, 0, 0, 0, 0, 0, 0]]
          },
        options: {
          lineSmooth: this.$Chartist.Interpolation.cardinal({
            tension: 0
          }),
          low: 0,
          high: 5,
          chartPadding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
          },
          autoScaleAxis: true          
        },        
      },
      days: ['Do','Lu','Ma', 'Mi','Ju','Vi','Sa'],
      lastProperties: undefined,
      lastUsers: undefined,
      usersChart: {
        data: {
          labels: [],
          series: [[0,0,0,0,0,0,0]]
        },

        options: {
          lineSmooth: this.$Chartist.Interpolation.cardinal({
            tension: 0
          }),
          low: 0,
          high: 5, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
          chartPadding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
          }
        }
      },
      newsLettersSubscriptionChart: {
        data: {
          labels: [
            "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago","Sep","Oct","Nov","Dec"
          ],
          series: [[0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]]
        },
        options: {
          axisX: {
            showGrid: false
          },
          low: 0,
          high: 10,
          chartPadding: {
            top: 0,
            right: 5,
            bottom: 0,
            left: 0
          }
        },
        responsiveOptions: [
          [
            "screen and (max-width: 640px)",
            {
              seriesBarDistance: 5,
              axisX: {
                labelInterpolationFnc: function(value) {
                  return value[0];
                }
              }
            }
          ]
        ]
      },
      totalProperties: undefined,
      totalUsuarios: undefined,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"
    };
  },
  methods: {
    fetchDescriptors(){
      const self = this
      self.isLoading = true;
      axios.get(descriptorsUrl)
      .then((response) => {
        self.updatePropertiesChart(self,response.data.properties)
        self.updateNewsLettersChart(self,response.data.newsletters)
        self.updateUsersChart(self, response.data.users)
        self.totalProperties = response.data.totalProperties
        self.totalUsuarios = response.data.totalUsuarios
        self.lastProperties = response.data.last_properties
        self.lastUsers = response.data.last_users
        self.isLoading = false
      });
    },
    updatePropertiesChart(self,properties){
      var max = 0
      for (var i = 0; i < properties.length; i++) {
        max = properties[i].quantity > max ? properties[i].quantity * 1.5 : max
        self.propertiesForDaysChart.data.series[0][properties.length-i] = properties[i].quantity
        self.propertiesForDaysChart.data.labels[properties.length-i] = properties[i].day
      }
      self.propertiesForDaysChart.options.high = max
      var element_id = document.getElementsByClassName('ct-chart')[0].getAttribute('id');
      var chartIdQuery = `#${element_id}`;
      self.$Chartist["Line"](
        chartIdQuery,
        self.propertiesForDaysChart.data,
        self.propertiesForDaysChart.options
      );
    },
    updateUsersChart(self,users){
      var max = 0
      for (var i = 0; i < users.length; i++) {
        max = users[i].quantity > max ? users[i].quantity * 1.5 : max
        self.usersChart.data.series[0][users.length - i] = users[i].quantity
        self.usersChart.data.labels[users.length - i] = users[i].day
      }
      self.usersChart.options.high = max
      var element_id = document.getElementsByClassName('ct-chart')[2].getAttribute('id');
      var chartIdQuery = `#${element_id}`;
      self.$Chartist["Line"](
        chartIdQuery,
        self.usersChart.data,
        self.usersChart.options
      );
    },
    updateNewsLettersChart(self,newsletters){
      var max = 0
      for (var i = 0; i < newsletters.length; i++) {
        max = newsletters[i].quantity_nl > max ? newsletters[i].quantity_nl * 2 : max
        self.newsLettersSubscriptionChart.data.series[0][newsletters[i].month_nl - 1] = newsletters[i].quantity_nl
      }
      self.newsLettersSubscriptionChart.options.high = max
      var element_id = document.getElementsByClassName('ct-chart')[1].getAttribute('id');
      var chartIdQuery = `#${element_id}`;
      self.$Chartist["Bar"](
        chartIdQuery,
        self.newsLettersSubscriptionChart.data,
        self.newsLettersSubscriptionChart.options
      );
    }
  },
  mounted: function(){
  },
  created() {
    //do something after creating vue instance
    this.fetchDescriptors()
  }
};
</script>

<style lang="scss" scoped>
  table {
    width: 100%;
  }
  th,td {
    min-width: 100px;
    text-align: center; 
    border-bottom: 1px solid #f1f1f1;
    font-size: 0.8rem;
    padding: 0;
  }
  td a {
    color: black !important;
  }
  td a:hover {
    text-decoration: underline !important;
  }
</style>
