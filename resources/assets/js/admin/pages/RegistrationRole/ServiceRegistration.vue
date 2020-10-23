<template>
  <div class="content">
    <md-tabs class="md-primary" md-alignment="fixed">
      <md-tab id="step-1" md-label="Datos Personales" md-icon="code">
        <service-first-step></service-first-step>
      </md-tab>
      <md-tab id="step-2" md-label="Informacion de Empresa" md-icon="code">
        <service-second-step></service-second-step>
      </md-tab>
    </md-tabs>
  </div>
</template>
<script>
import {
  ServiceFirstStep,
  ServiceSecondStep
} from "../../components";
export default {
  components: {
    ServiceFirstStep,
    ServiceSecondStep
  }
};
</script>
