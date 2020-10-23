import bulmaCarousel from "bulma-carousel/dist/js/bulma-carousel.min";
import bulmaAccordion from "bulma-accordion/dist/js/bulma-accordion.min";
import Viewer from "viewerjs";
import StarRating from "vue-star-rating";
import Agent from "./components/Agent.vue";
import MapDetail from "./components/MapDetail.vue";
import BannerGallery from "./components/pages/BannerGallery.vue";
import Gallery from "./components/pages/Gallery.vue";
import Axios from "axios";
import Calendar from './components/pages/Calendar.vue';

const imagesDir = document.getElementById("images-dir").value;



const exploreDetails = new Vue({
  el: "#explore-details",
  components: {
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
      similarAgents: [],
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
      project: {
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
    unitAmenities: function() {
      return this.project.amenities.filter(amenitie => amenitie.type == 0);
    },
    communAmenities: function() {
      return this.project.amenities.filter(amenitie => amenitie.type == true);
    },
    projectPosition: function() {
      return this.project.latitude && this.project.longitude
        ? { lat: this.project.latitude, lng: this.project.longitude }
        : {};
    },
    projectPhotos: function() {
      let vm = this;
      let photos = [];
      if (typeof vm.project.photos.photos == "undefined") return photos;

      if (vm.project.photos.cover == null) return photos;

      photos = vm.project.photos.photos;
      photos.unshift(vm.project.photos.cover[0]);

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
    isFavouriteProject: function() {
      let vm = this;

      return typeof vm.project.data.favourite != "undefined"
        ? vm.project.data.favourite
        : false;
    },
    projectLocation: function() {
      let vm = this;
      return vm.project.data.longitude && vm.project.data.latitude
        ? {
            lat: vm.project.data.latitude,
            lng: vm.project.data.longitude
          }
        : {};
    },
    
  },
  beforeMount: function() {
    this.getData();
    this.getAgent();
    this.getAmenities();
    this.getPhotos();

    // Get related data
    this.getSimilarProjects();
    this.getOtherProjects();
  },
  mounted: function() {
  },
  methods: {
    getData: function() {
      let vm = this;
      // Get projects self data
      Axios.get("/projects/" + vm.project.id)
        .then(response => {
          vm.$set(vm.project, "data", response.data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    getAgent: function() {
      let vm = this;
      Axios.get("/projects/" + vm.project.id + "/agent").then(response => {
        vm.$set(vm.project, "agent", response.data);
        vm.$set(vm.starRating, "value", vm.number(vm.project.agent.rating));
      });
    },
    getPhotos: function() {
      let vm = this;
      Axios.get("/projects/" + this.project.id + "/photos").then(response => {
        console.log(response.data);
        vm.$set(vm.project, "photos", response.data);

        console.log(response.data);
        if (typeof vm.project.photos.cover === undefined)
          vm.project.photos.cover = null;

        // Set cover image if null
        if (vm.project.photos.cover === null) {
          if (vm.project.photos.photos.length > 0) {
            vm.project.photos.cover = vm.project.photos.photos.shift();
          }
        }
      });
    },
    getAmenities: function() {
      let vm = this;
      Axios.get("/projects/" + vm.project.id + "/amenities").then(response =>
        vm.$set(vm.project, "amenities", response.data)
      );
    },
    getSimilarProjects: function() {
      let vm = this;
      Axios.get("/agentes/get-projects?company_id="+vm.project.id)
        .then(response => {
          if (response.data.projects && response.data.projects.length > 0) {
            for (let i = 0; i < response.data.projects.length; i++) {
              response.data.projects[i].imagesDir = imagesDir;
              response.data.projects[i].item = i+1;
            }

            vm.similarAgents = response.data.projects;
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
    membershipAgentLogo: function(name) {
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
    membershipAgentMask: function(name) {
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
    membershipAgentButtom: function(name) {
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
    conditionProyect(value){
      switch (value) {
        case 0:
          return 'En Obra';
          break;
        case 1:
          return 'Terminado';
          break;
        case 2:
          return 'Otro';
          break;

      
        default:
          return 'Otro';
          break;
      }
    }
  }
});

var accordions = bulmaAccordion.attach();
