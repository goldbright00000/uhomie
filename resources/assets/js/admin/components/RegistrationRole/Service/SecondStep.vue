<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <h3 class="md-title">Informacion de la Empresa</h3>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-50">
        <company-data-form :saveDataUrl="companyDataFormUrl" :companyType="companyType" />
      </div>
    </div>
  </div>
</template>
<script>
  import { CompanyDataForm } from '../CommonForms'
  export default {
    name: "second-step",
    components:{
      CompanyDataForm,
    },
    data() {
      return {
        companyDataFormUrl: '/adm/users/service-registration/company-data',
        companyType: 'service'
      }
    }
  }
</script>
