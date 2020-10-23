<template>
  <div class="content">
    <md-tabs class="md-primary" md-alignment="fixed">
      <md-tab id="step-1" md-label="Datos Personales" md-icon="code">
        <tenant-first-step></tenant-first-step>
      </md-tab>
      <md-tab id="step-2" md-label="Datos Laborales" md-icon="code">
        <tenant-second-step></tenant-second-step>
      </md-tab>
      <md-tab id="step-3" md-label="Preferencias de Arriendo" md-icon="code">
        <tenant-third-step></tenant-third-step>
      </md-tab>
    </md-tabs>
  </div>
</template>
<script>
import {
  TenantFirstStep,
  TenantSecondStep,
  TenantThirdStep
} from "../../components";
export default {
  components: {
    TenantFirstStep,
    TenantSecondStep,
    TenantThirdStep
  },
  data(){
    return { }
  }
};
</script>
