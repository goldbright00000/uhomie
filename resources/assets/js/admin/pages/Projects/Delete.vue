<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <delete-project-form data-background-color="green">

      </delete-project-form>
      </div>
    </div>
  </div>
</template>
<script>
import { DeleteProjectForm } from "../../components";
export default {
  components: {
    DeleteProjectForm
  }
};
</script>
