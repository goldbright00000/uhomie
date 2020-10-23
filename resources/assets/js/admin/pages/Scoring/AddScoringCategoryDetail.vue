<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <add-scoring-category-detail-form data-background-color="green">

        </add-scoring-category-detail-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
    </div>
  </div>
</template>

<script>
import { AddScoringCategoryDetailForm } from "../../components";
export default {
  components: {
    AddScoringCategoryDetailForm
  }
};
</script>
