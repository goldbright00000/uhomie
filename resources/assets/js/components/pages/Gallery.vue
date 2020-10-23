<template>
  <div class="gallery-container" ref="gallery-container">
    <div :id="id" class="gallery-pictures" ref="gallery">
      <div
        class="picture"
        v-for="(picture, index) in pictures"
        :key="index"
        v-show="index < max_photos"
      >
        <img :src="picture.src" :alt="picture.name" width="100%">
        <h1 v-if="picture.name">{{ picture.name }}</h1>
      </div>
    </div>
    <a
      v-show="pictures.length > max_photos "
      class="show-more"
      @click.prevent="viewer.show()"
    >ver más</a>
  </div>
</template>

<script>
import Viewer from "viewerjs";
export default {
  props: {
    pictures: {
      type: Array,
      default: []
    },
    id: {
      type: String,
      default: "gallery-pictures"
    },
    max_photos: {
      type: Number,
      default: 6
    }
  },
  data: function() {
    return {
      viewer: null
    };
  },
  mounted: function() {
    this.loadViewer();
  },
  updated: function() {
    this.loadViewer();
  },
  methods: {
    loadViewer: function() {
      let vm = this;
      if (vm.viewer) vm.viewer.destroy();
      if (!vm.pictures || vm.pictures.length < 1) return false;

      vm.viewer = new Viewer(vm.$refs["gallery"], {
        title: img => img.getAttribute("alt")
      });
    }
  }
};
</script>