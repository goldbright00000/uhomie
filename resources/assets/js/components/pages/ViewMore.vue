<template>
  <div class="readmore">
    <div class="readmore__content" ref="content">
      {{ contentText }}
      <p>
        <a
          href="#"
          @click.prevent="toggleContent()"
          v-if="can_expand"
          :style="{ marginTop: '1rem' }"
        >{{ textButton }}</a>
      </p>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    content: {
      type: String,
      default: ""
    }
  },
  data: function() {
    return {
      is_expanded: false,
      can_expand: false,
      maxLength: 300
    };
  },
  computed: {
    contentText: function() {
      if (this.content.length <= this.maxLength) {
        return this.content;
      }

      // The content cand expand
      this.can_expand = true;

      if (this.is_expanded) {
        return this.content;
      }

      return this.content.substring(0, this.maxLength) + "...";
    },
    textButton: function() {
      return this.is_expanded ? "Ver menos" : "Leer más";
    }
  },
  methods: {
    toggleContent: function() {
      let vm = this;

      vm.is_expanded = !vm.is_expanded;
    }
  }
};
</script>