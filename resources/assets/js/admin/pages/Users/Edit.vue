<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <edit-user-form data-background-color="green">

        </edit-user-form>
      </div>
    </div>
  </div>
</template>

<script>
import { EditUserForm,AgentFirstStep,AgentSecondStep } from "../../components";
export default {
  components: {
    EditUserForm,
    AgentFirstStep,
    AgentSecondStep
  }
};
</script>
