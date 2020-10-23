import vueSlider from 'vue-slider-component';

const publish = new Vue({
	el: '#publish',
	components: {
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
		comision() {
			var comision = 0.06
			comision = this.calculate < 6500000 ? 0.07 : comision
			comision = this.calculate < 4500000 ? 0.08 : comision
			comision = this.calculate < 3500000 ? 0.09 : comision
			comision = this.calculate < 2950000 ? 0.1 : comision
			comision = this.calculate < 2200000 ? 0.11 : comision
			comision = this.calculate < 1550000 ? 0.12 : comision
			comision = this.calculate < 1250000 ? 0.13 : comision
			comision = this.calculate < 851000 ? 0.14 : comision
			comision = this.calculate < 550000 ? 0.15 : comision

			return comision
		},
		totalWithUhomieNumber() {
			return Math.round(this.calculate * this.comision)
		},
		totalWithCorredoresNumber() {
			return Math.round(this.calculate*0.5 + (this.calculate*0.5)*0.19)
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
		
	}
});