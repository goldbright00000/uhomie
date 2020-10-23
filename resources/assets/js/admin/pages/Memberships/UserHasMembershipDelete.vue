<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-10"></div>
      <div class="md-layout-item md-medium-size-100 md-size-80">
        <delete-user-has-membership-form data-background-color="green" :user="user">
        </delete-user-has-membership-form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { DeleteUserHasMembershipForm } from "../../components";


export default {
  components: {
    DeleteUserHasMembershipForm
  },
  data(){
    return {
      user:null
    }
  },
  methods: {
    getData() {
      const vm = this;
      const membershipId = JSON.parse(vm.$route.params.membershipId);
      const userId = JSON.parse(vm.$route.params.userId);
      const getDataUrl = '/adm/memberships/'+membershipId+'/users/'+ userId
      axios.get(getDataUrl)
      .then((response) => {
        vm.user = response.data.user_has_membership
      });
    }
  },
  mounted() {
    //do something after creating vue instance
    this.getData()
  }
};
</script>
