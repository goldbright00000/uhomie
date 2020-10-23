<template>
  <div
    class="carousel carousel-animated carousel-animate-slide banner-gallery"
    ref="bannerGallery"
    style="background-color: #f5f5f5;"
    >
    <div class="carousel-container" id="carousel-gallery">
      <div
        v-for="(photo, key) in photos"
        :key="key"
        :class="'carousel-item has-background ' + (key == 0 ? 'is-active' : '') "
      >
        <img class="is-background" :src="photo.src" :alt="photo.name">
      </div>
    </div>
    <div class="carousel-navigation is-overlay">
      <div class="carousel-nav-left">
        <i class="fa fa-angle-left" aria-hidden="true"></i>
      </div>
      <div class="carousel-nav-right">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
      </div>
    </div>
    <div class="carousel-explore is-overlay">
      <span>Explorar alojamiento</span>
      <img
        class="btn-show-gallery"
        :src="getAbsUrl('images/explore-details/explorar_alojamiento.png')"
      >
    </div>
    <div class="carousel-share is-overlay">
      <div class="is-favorite" role="button">
        <img v-if="favorite" :src="getAbsUrl('images/explore-details/corazon_full.png')">
        <img v-else :src="getAbsUrl('images/explore-details/corazon_empty.png')">
      </div>
      <div role="button">
        <img @click="toShare" :src="getAbsUrl('images/explore-details/ico_share.png')" id="branchButton">
      </div>
    </div>
    <div  :class="{'is-active': activeModal, 'modal': true}">
      <div class="modal-background"></div>
      <div class="modal-content">
        <!-- Sharingbutton Facebook -->
        <a class="resp-sharing-button__link" :href="'https://facebook.com/sharer/sharer.php?u='+this.getAbsUrl('explorar/'+this.property_id+'/'+this.slug)" target="_blank" rel="noopener" aria-label="Facebook">
          <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
        </a>

        <!-- Sharingbutton Twitter -->
        <a class="resp-sharing-button__link" :href="'https://twitter.com/intent/tweet/?text=%C3%89chale%20un%20vistazo%20a%20esta%20propiedad%20en%20uHomie.cl&amp;url='+this.getAbsUrl('explorar/'+this.property_id+'/'+this.slug)" target="_blank" rel="noopener" aria-label="Twitter">
          <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
        </a>

        <!-- Sharingbutton E-Mail -->
        <a class="resp-sharing-button__link" :href="'mailto:?subject=%C3%89chale%20un%20vistazo%20a%20esta%20propiedad%20en%20uHomie.cl&amp;body='+this.getAbsUrl('explorar/'+this.property_id+'/'+this.slug)" target="_self" rel="noopener" aria-label="E-Mail">
          <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg></div>E-Mail</div>
        </a>

        <!-- Sharingbutton WhatsApp -->
        <a class="resp-sharing-button__link" :href="'whatsapp://send?text=%C3%89chale%20un%20vistazo%20a%20esta%20propiedad%20en%20uHomie.cl%20'+this.getAbsUrl('explorar/'+this.property_id+'/'+this.slug)" target="_blank" rel="noopener" aria-label="WhatsApp">
          <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg></div>WhatsApp</div>
        </a>

        <!-- Sharingbutton Telegram -->
        <a class="resp-sharing-button__link" :href="'https://telegram.me/share/url?text=%C3%89chale%20un%20vistazo%20a%20esta%20propiedad%20en%20uHomie.cl&amp;url='+this.getAbsUrl('explorar/'+this.property_id+'/'+this.slug)" target="_blank" rel="noopener" aria-label="Telegram">
          <div class="resp-sharing-button resp-sharing-button--telegram resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M.707 8.475C.275 8.64 0 9.508 0 9.508s.284.867.718 1.03l5.09 1.897 1.986 6.38a1.102 1.102 0 0 0 1.75.527l2.96-2.41a.405.405 0 0 1 .494-.013l5.34 3.87a1.1 1.1 0 0 0 1.046.135 1.1 1.1 0 0 0 .682-.803l3.91-18.795A1.102 1.102 0 0 0 22.5.075L.706 8.475z"/></svg></div>Telegram</div>
        </a>

      </div>
      <button class="modal-close is-large" aria-label="close" @click="activeModal = false"></button>
    </div>
  </div>
</template>
<script>
import bulmaCarousel from "bulma-carousel/dist/js/bulma-carousel.min";
import Viewer from "viewerjs";
export default {
  props: {
    slug: String,
    property_id: String,
    id: {
      type: String,
      default: "carousel-gallery"
    },
    photos: {
      type: Array,
      required: true,
      default: function() {
        return [];
      }
    },
    favorite: {
      type: Boolean,
      default: null
    },
    imagesDir: {
      type: String,
      required: true
    }
  },
  data: function() {
    return {
      viewer: null,
      activeModal: false,
    };
  },
  methods: {
    toShare: function(){
      this.activeModal = true;
    },
    getAbsUrl: function(image_url) {
      return document.querySelector("meta[name='assets-url']").getAttribute("content") + image_url;
    },
    loadViewer: function() {
      let vm = this;

      if (vm.viewer) {
        vm.viewer.destroy();
      }

      if (!vm.photos.length || vm.photos.length < 1) return false;

      vm.viewer = new Viewer(document.getElementById(vm.id), {
        title: img => img.getAttribute("alt")
      });
      bulmaCarousel.attach();
    }
  },
  mounted: function() {
    let vm = this;
    this.loadViewer();

    // Set event handler
    this.$refs["bannerGallery"].querySelector(
      ".btn-show-gallery"
    ).onclick = () => vm.viewer.show();
    
   
      
      
      
    

  },
  updated: function() {
    this.loadViewer();
  }
};
</script>

<style lang="scss" scoped>
  .banner-gallery {
    min-height: 500px;
    max-height: 90vh;
  }
  .carousel .carousel-item {
    height: inherit;
  }
</style>
