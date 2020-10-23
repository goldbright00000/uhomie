<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Listado de Proyectos</h4>
            <p class="category">Listado de todos los proyectos registrados</p>
          </md-card-header>
          <md-card-content>
            <projects-table table-header-color="black"></projects-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import { ProjectsTable } from "../../components";

export default {
  components: {
    ProjectsTable
  }
};
</script>
