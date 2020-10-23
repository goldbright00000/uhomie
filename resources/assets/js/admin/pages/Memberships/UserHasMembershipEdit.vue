<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-10"></div>
      <div class="md-layout-item md-medium-size-100 md-size-80">
        <edit-user-has-membership-form data-background-color="green">
        </edit-user-has-membership-form>
      </div>
    </div>
  </div>
</template>

<script>
import { EditUserHasMembershipForm } from "../../components";
export default {
  components: {
    EditUserHasMembershipForm
  }
};
</script>
