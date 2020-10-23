<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-10"></div>
      <div class="md-layout-item md-medium-size-100 md-size-80">
        <edit-membership-form data-background-color="green" :membershipId="membershipId">

        </edit-membership-form>
      </div>
    </div>
  </div>
</template>

<script>
import { EditMembershipForm } from "../../components";
export default {
  components: {
    EditMembershipForm
  },
  data(){
    return {
      membershipId:this.$route.params.membershipId
    }
  }
};
</script>