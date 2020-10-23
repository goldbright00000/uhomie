<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <edit-project-form data-background-color="green">

        </edit-project-form>
      </div>
    </div>
  </div>
</template>

<script>
import { EditProjectForm } from "../../components";
export default {
  components: {
    EditProjectForm
  }
};
</script>
