<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Listado de Contratos</h4>
            <p class="category">Listados de todos los contratos realizados</p>
          </md-card-header>
          <md-card-content>
            <contracts-table table-header-color="black"></contracts-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import { ContractsTable } from "../../components";

export default {
  components: {
    ContractsTable
  }
};
</script>
