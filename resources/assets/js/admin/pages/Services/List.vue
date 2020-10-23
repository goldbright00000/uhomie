<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Listado de Servicios</h4>
            <p class="category">Listado de todos los servicios registrados</p>
          </md-card-header>
          <md-card-content>
            <services-table table-header-color="black"></services-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import ServicesTable from "../../components/Services/ServicesTable";

export default {
  components: {
    ServicesTable
  }
};
</script>
