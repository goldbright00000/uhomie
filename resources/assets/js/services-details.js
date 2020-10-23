import bulmaCarousel from "bulma-carousel/dist/js/bulma-carousel.min";
import bulmaAccordion from "bulma-accordion/dist/js/bulma-accordion.min";
import Viewer from "viewerjs";
import vueSlider from "vue-slider-component";
import StarRating from "vue-star-rating";
import Agent from "./components/Agent.vue";
import MapDetail from "./components/MapDetail.vue";
import BannerGallery from "./components/pages/BannerGallery.vue";
import Gallery from "./components/pages/Gallery.vue";
import Axios from "axios";
import Calendar from './components/pages/Calendar.vue';

const imagesDir = document.getElementById("images-dir").value;



const exploreDetails = new Vue({
  el: "#services-details",
  components: {
    vueSlider,
    StarRating,
    Agent,
    MapDetail,
    BannerGallery,
    Gallery,
    bulmaAccordion,
    bulmaCarousel,
    Calendar
  },
  data() {
    return {
      similarServices: [],
      otherAgents: [],
      scoringSlider: {
        value: 0,
        min: 0,
        max: 1000,
        height: 5,
        tooltip: "always",
        piecewise: true,
        interval: 250,
        bgStyle: {
          background:
            "linear-gradient(90deg, rgba(241,62,13,1) 0%, rgba(232,245,11,1) 50%, rgba(30,181,6,1) 100%)"
        },
        processStyle: {
          background: "transparent"
        },
        piecewiseStyle: {
          visibility: "visible",
          width: "12px",
          height: "12px"
        },
        sliderStyle: {
          background: "transparent",
          boxShadow: "none"
        }
      },
      starRating: {
        value: this.rating,
        options: {
          starSize: 15,
          showRating: false,
          borderWidth: 3,
          borderColor: "hsl(51, 100%, 50%)",
          inactiveColor: "#fff",
          activeColor: "hsl(51, 100%, 50%)"
        }
      },
      service: {
        id: window.location.pathname.split("/").pop(),
        data: {},
        agent: {},
        amenities: [],
        photos: []
      }
    };
  },
  computed: {
    imagesDir: function() {
      return document.getElementById("images-dir").value;
    },
    servicePhotos: function() {
      let vm = this;
      let photos = [];
      if (typeof vm.service.photos == "undefined") return photos;

      photos = vm.service.data.photos;

      photos = photos.map(photo => {
        let new_photo = {};

        new_photo.name =
          typeof photo.space == "undefined" || !photo.space
            ? null
            : photo.space.name;

        new_photo.src = photo.path;

        return new_photo;
      });

      return photos;
    },
    isFavouriteservice: function() {
      let vm = this;

      return typeof vm.service.data.favourite != "undefined"
        ? vm.service.data.favourite
        : false;
    },
    serviceLocation: function() {
      let vm = this;
      return vm.service.data.longitude && vm.service.data.latitude
        ? {
            lat: vm.service.data.latitude,
            lng: vm.service.data.longitude
          }
        : {};
    },
    
  },
  beforeMount: function() {
    this.getData();
    //this.getPhotos();

    // Get related data
    this.getSimilarServices();
    this.getOtherProjects();
  },
  mounted: function() {
  },
  methods: {
    getData: function() {
      let vm = this;
      // Get services self data
      Axios.get("/servicios/get/" + vm.service.id)
        .then(response => {
          vm.$set(vm.service, "data", response.data.data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    /*getPhotos: function() {
      let vm = this;
      Axios.get("/servicios/" + this.service.id + "/photos").then(response => {
        console.log(response.data);
        vm.$set(vm.service, "photos", response.data);

        console.log(response.data);
        if (typeof vm.service.photos.cover === undefined)
          vm.service.photos.cover = null;

        // Set cover image if null
        if (vm.service.photos.cover === null) {
          if (vm.service.photos.photos.length > 0) {
            vm.service.photos.cover = vm.service.photos.photos.shift();
          }
        }
      });
    },*/
    getSimilarServices: function() {
      let vm = this;
      Axios.get("/servicios/get-services?company_id="+vm.service.id)
        .then(response => {
          if (response.data.services && response.data.services.length > 0) {
            for (let i = 0; i < response.data.services.length; i++) {
              response.data.services[i].imagesDir = imagesDir;
              response.data.services[i].item = i+1;
            }

            vm.similarServices = response.data.services;
          }
        })
        .catch(e => console.log("Ha ocurrido un error", e));
    },
    getOtherProjects: function() {
      let vm = this;
      Axios.get("/agentes/get-projects?offset=1")
        .then(response => {
          vm.otherAgents = response.data.projects;
        })
        .catch(e => console.log("Ha ocurrido un error", e));
    },
    moneyFormat: function(n, c, d, t) {
      var c = isNaN((c = Math.abs(c))) ? 0 : c,
        d = d == undefined ? "," : d,
        t = t == undefined ? "." : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
        j = (j = i.length) > 3 ? j % 3 : 0;

      return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
        (c
          ? d +
            Math.abs(n - i)
              .toFixed(c)
              .slice(2)
          : "")
      );
    },
    number: function(a) {
      return typeof a != "number" ? 0 : a;
    },
    dateFormat: function(date) {
      return date
        ? date
            .split("-")
            .reverse()
            .join("/")
        : "";
    },
    imageDir: function(img_path) {
      return "/" + img_path;
    },
    membershipServiceLogo: function(name) {
      switch (name) {
        case 'Basic':{
            return '/explore-details/basic.png';}
        case 'Select':{
            return '/explore-details/select.png';}
        case 'Premium':{
            return '/explore-details/premium.png';}
      
        default:{
            return '/explore-details/basic.png';}
      }
    },
    membershipServiceMask: function(name) {
      switch (name) {
        case 'Basic':{
            return '/explore-details/mascara_img_basic.png';}
        case 'Select':{
            return '/explore-details/mascara_img_select.png';}
        case 'Premium':{
            return '/explore-details/mascara_img_premium.png';}
      
        default:{
            return '/explore-details/mascara_img_basic.png';}
      }
    },
    membershipServiceButtom: function(name) {
      switch (name) {
        case 'Basic':{
            return 'is-basic';}
        case 'Select':{
            return 'is-select';}
        case 'Premium':{
            return 'is-premium';}
      
        default:{
            return 'is-basic';}
      }
    },
  }
});

var accordions = bulmaAccordion.attach();