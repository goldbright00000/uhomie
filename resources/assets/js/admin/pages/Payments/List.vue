<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Listado de Pagos uHomie</h4>
            <p class="category">Listado de todos los pagos realizados</p>
          </md-card-header>
          <md-card-content>
            <payments-table table-header-color="black"></payments-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import { PaymentsTable } from "../../components";

export default {
  components: {
    PaymentsTable
  }
};
</script>
