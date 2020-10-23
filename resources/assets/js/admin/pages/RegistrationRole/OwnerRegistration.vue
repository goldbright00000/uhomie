<template>
  <div class="content">
    <md-tabs class="md-primary" md-alignment="fixed">
      <md-tab id="step-1" md-label="Datos Personales" md-icon="code">
        <owner-first-step></owner-first-step>
      </md-tab>
    </md-tabs>
  </div>
</template>
<script>
import {
  OwnerFirstStep
} from "../../components";
export default {
  components: {
    OwnerFirstStep
  }
};
</script>
