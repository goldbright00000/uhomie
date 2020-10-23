<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <add-scoring-category-form data-background-color="green">

        </add-scoring-category-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
    </div>
  </div>
</template>

<script>
import { AddScoringCategoryForm } from "../../components";
export default {
  components: {
    AddScoringCategoryForm
  }
};
</script>
