<!-- <template>
  <property-data />
</template>

<script>
import { PropertyData } from "../../components";
export default {
  components: {
    PropertyData
  }
};
</script> -->
<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <md-card class="md-layout-item md-size-80 md-small-size-100">
          <md-card-header data-background-color="green">
            <h4 class="title">Crear una Propiedad</h4>
            <p class="category">Ingresar datos de la propiedad</p>
          </md-card-header>
          <md-card-content>
            <basic-data-form :saveDataUrl="saveDataUrl" :edit="false"></basic-data-form>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import { BasicDataForm } from "../../components/Properties/";
export default {
  components: {
    BasicDataForm
  },
  data(){
    return {
      saveDataUrl: '/adm/properties/basic-data'
    }
  }
};
</script>
