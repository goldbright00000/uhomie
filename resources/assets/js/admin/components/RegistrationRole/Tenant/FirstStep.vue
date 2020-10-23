<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <h3 class="md-title">Datos Personales</h3>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <personal-data-form :saveDataUrl="personalDataFormUrl" />
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <location-form :saveDataUrl="locationFormUrl" />
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <collateral-data-form />
      </div>
    </div>
  </div>
</template>
<script>
  import { CollateralDataForm } from '../Tenant'
  import {
    PersonalDataForm,
    LocationForm
  } from '../CommonForms'
  export default {
    name: "first-step",
    components:{
      PersonalDataForm,
      LocationForm,
      CollateralDataForm
    },
    data() {
      return {
        locationFormUrl: '/adm/users/tenant-registration/location-data',
        personalDataFormUrl: '/adm/users/tenant-registration/personal-data'
      }
    }
  }
</script>
