<template>
  <div class="content">
    <md-tabs class="md-primary" md-alignment="fixed">
      <md-tab id="step-1" md-label="Datos Personales" md-icon="code">
        <agent-first-step></agent-first-step>
      </md-tab>
      <md-tab id="step-2" md-label="Informacion de Empresa" md-icon="code">
        <agent-second-step></agent-second-step>
      </md-tab>
    </md-tabs>
  </div>
</template>
<script>
import {
  AgentFirstStep,
  AgentSecondStep
} from "../../components";
export default {
  components: {
    AgentFirstStep,
    AgentSecondStep
  }
};
</script>
