<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <edit-scoring-category-form data-background-color="green">

        </edit-scoring-category-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
    </div>
  </div>
</template>

<script>
import { EditScoringCategoryForm } from "../../components";
export default {
  components: {
    EditScoringCategoryForm
  }
};
</script>
