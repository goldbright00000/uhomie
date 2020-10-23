<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <h3 class="md-title"> Preferencias para Arrendar </h3>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <payment-preferences-form/>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <tenanting-preferences-form/>
      </div>
    </div>
  </div>
</template>
<script>
  import {
    PaymentPreferencesForm,
    TenantingPreferencesForm
  } from '../Tenant'
  export default {
    name: "third-step",
    components:{
      PaymentPreferencesForm,
      TenantingPreferencesForm
    }
  }
</script>
