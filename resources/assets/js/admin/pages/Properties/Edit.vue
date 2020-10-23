<template>
  <property-data :isProject="isProject" />
</template>

<script>
import { PropertyData } from "../../components";
export default {
  props: [
    'isProject'
  ],
  components: {
    PropertyData
  }
};
</script>


<!-- <template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <edit-property-form data-background-color="green">

        </edit-property-form>
      </div>
    </div>
  </div>
</template>

<script>
import { EditPropertyForm } from "../../components";
export default {
  components: {
    EditPropertyForm
  }
};
</script> -->
