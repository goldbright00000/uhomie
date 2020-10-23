<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <delete-user-form data-background-color="green">

        </delete-user-form>
      </div>
    </div>
  </div>
</template>
<script>
import { DeleteUserForm } from "../../components";
export default {
  components: {
    DeleteUserForm
  }
};
</script>
