<template>
  <div class="content">
    <md-tabs class="md-primary" md-alignment="fixed">
      <md-tab id="step-1" md-label="Datos Principales" md-icon="assignment">
        <div class="md-layout">
          <div class="md-layout-item md-medium-size-100 md-size-100">
            <h3 class="md-title">Datos Basicos</h3>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Datos Basicos -->
            <basic-data-form :isProject="isProject" saveDataUrl="/adm/properties/basic-data" :edit="true" />
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Direccion -->
            <location-form saveDataUrl="/adm/properties/location-data"/>
          </div>
        </div>
      </md-tab>
      <md-tab id="step-2" md-label="Fotografias" md-icon="collections">
        <div class="md-layout">
          <div class="md-layout-item md-medium-size-100 md-size-100">
            <h3 class="md-title">Fotografias</h3>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Fotografias  -->

            <photographs-form saveDataUrl="/adm/properties/photographs"/>
          </div>
        </div>
      </md-tab>
      <md-tab id="step-3" md-label="Detalles de Propiedad" md-icon="description">
        <div class="md-layout">
          <div class="md-layout-item md-medium-size-100 md-size-100">
            <h3 class="md-title">Detalles de Propiedad</h3>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Detalles de Propiedad  -->
            <property-details-form saveDataUrl="/adm/properties/property-details"/>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Servicios  -->
            <files-form saveDataUrl="/adm/properties/verified"></files-form>
          </div>
        </div>
      </md-tab>
      <md-tab 
        v-if="!isProject" id="step-4" md-label="Preferencias de Arriendo" md-icon="playlist_add_check">
        <div class="md-layout">
          <div class="md-layout-item md-medium-size-100 md-size-100">
            <h3 class="md-title">Preferencias de Arriendo</h3>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Preferencias de Arriendo  -->
            <tenanting-conditions-form saveDataUrl="/adm/properties/tenanting-conditions"/>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Preferencias de visita  -->
            <visit-preferences-form saveDataUrl="/adm/properties/visit-preferences"/>
          </div>
        </div>
      </md-tab>
      <md-tab 
        v-if="!isProject"
        id="step-5" md-label="Arrendatario" md-icon="person">
        <div class="md-layout">
          <div class="md-layout-item md-medium-size-100 md-size-100">
            <h3 class="md-title">Arrendatario</h3>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Arrendatario  -->
            <features-tenant-form saveDataUrl="/adm/properties/features-tenant"/>
          </div>
          <div class="md-layout-item md-medium-size-100 md-size-50">
            <!-- Ejecutivo  -->
            <executive-form saveDataUrl="/adm/properties/assing-execute"/>
          </div>
        </div>
      </md-tab>
    </md-tabs>
  </div>
</template>

<script>
  import {
    BasicDataForm,
    LocationForm,
    PropertyDetailsForm,
    TenantingConditionsForm,
    VisitPreferencesForm,
    FeaturesTenantForm,
    PhotographsForm,
    FilesForm,
    ExecutiveForm
  } from '../Properties'
  export default {
    props: [
      'isProject'
    ],
    name: "property-data",
    components:{
      BasicDataForm,
      LocationForm,
      PropertyDetailsForm,
      TenantingConditionsForm,
      VisitPreferencesForm,
      FeaturesTenantForm,
      PhotographsForm,
      FilesForm,
      ExecutiveForm
    }
  }
</script>
