<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Scoring</h4>
            <p class="category">Listado de Scoring Registrados</p>
          </md-card-header>
          <md-card-content>
            <scoring-table table-header-color="black"></scoring-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Agregar un Scoring" to="/scoring/create">
      <md-icon>add</md-icon>
      <md-tooltip md-direction="top" >Nuevo Scoring</md-tooltip>
    </md-button>
  </div>
</template>

<script>
import { ScoringTable } from "../../components";

export default {
  components: {
    ScoringTable
  }
};
</script>
