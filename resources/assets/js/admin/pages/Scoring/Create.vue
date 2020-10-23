<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <create-scoring-form data-background-color="green">

        </create-scoring-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
    </div>
  </div>
</template>

<script>
import { CreateScoringForm } from "../../components";
export default {
  components: {
    CreateScoringForm
  }
};
</script>
