import Property from './components/Property.vue';
import Axios from "axios";

import vueSlider from 'vue-slider-component';

const imagesDir = document.getElementById('images-dir').value;

const postulate = new Vue({
	el: '#postulate',
	components: {
		Property,
		vueSlider,
	},
	data() {
		return {
			properties: [],
			calculate: 500000,
			calculateString: '500.000',
			marks2: {
				 '100000': '100.000',
				 '500000': '500.000',
				 '1000000': '1.000.000',
				 '1500000': '1.500.000',
				 '2000000': '2.000.000',
				 '2500000': '2.500.000',
				 '3000000': '3.000.000',
			},
			marks: [500000,2000000],
			processStyle: {
        'backgroundColor': '#00a2ff'
      },
      labelStyle: {
      	'backgroundColor': '#00a2ff'
      }
		};
	},
	watch: {
		calculate() {
			this.calculateString = this.calculate.toLocaleString()
		}
	},
	mounted(){
		this.getRecommendedProperties();
	},
	computed: {
		totalWithUhomieNumber() {
			return Math.round(this.calculate*2 + this.calculate * 0.11)
		},
		totalWithCorredoresNumber() {
			return Math.round(this.calculate*2 + this.calculate*0.5 + (this.calculate*0.5)*0.19)
		},
		totalWithUhomie() {

			return this.totalWithUhomieNumber.toLocaleString()
		},
		totalWithCorredores() {

			return this.totalWithCorredoresNumber.toLocaleString()
		},
		totalResult() {
			return (this.totalWithCorredoresNumber - this.totalWithUhomieNumber).toLocaleString()
		}
	},
	methods: {
		getRecommendedProperties: function(){
			let vm = this;
			Axios.get("/explorar/get-recommended-properties/8")
			  .then(response => {
				vm.properties = response.data;
				if(response.data.length > 0) vm.logged = true;
				//console.log(vm.recommendedProperties);
			  })
			  .catch(e => {
				console.log("Ha ocurrido un error...");
			  });
		  },
	}
});