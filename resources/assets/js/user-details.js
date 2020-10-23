import Axios from "axios";
import moment from 'moment';
import Property from './components/Property.vue';

const imagesDir = document.getElementById("images-dir").value;

Vue.prototype.moment = moment;

const user = new Vue({
    el: '#user-details',
    components: {
        Property
    },
    name: 'User',
    delimiters: ["{{", "}}"],
    data() {
        return {
            user: {
                id: function() {
                    return window.location.pathname.split("/")[3]; 
                },
                data: {},
                
            },
            imagesDir: imagesDir
        }
    },
    methods:{
        getData: function() {
            this.isLoading = true
            let vm = this;
            // Get properties self data
            Axios.get("/users/info/" + this.user.id())
                .then(response => {
                    this.isLoading = false
                    vm.$set(vm.user, "data", response.data.data); 
                })
                .catch(error => {
                    this.isLoading = false
                    console.log(error);
                });
        },
        membershipOwnerMask: function(name) {
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
        membershipOwnerLogo: function(name) {
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
    },
    mounted(){
        this.getData();
    }


});