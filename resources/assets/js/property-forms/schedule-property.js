const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const _property = document.getElementById('property_id').value;
const _visit = document.getElementById('visit_schedule').value;

import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';;

// Use v-calendar & v-date-picker components
Vue.use(VCalendar, {
  componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
  //...,// ...other defaults
});

const schedule = new Vue({
    el: '#schedule',
    name: 'MyformSchedule',
    props: {
        editing: Boolean,
        info: Object
    },
    components:{
    },
    data () {
        return {
            schedule_dates: [],
            schedule_range: null,
            morning: [],
            noon: [],
            afternoon: [],
            morningC: [],
            noonC: [],
            afternoonC: [],
            menu: false,
            now: new Date(),
            mode: 'multiple',
            selectedDate: null,
            fecha: [new Date()],
            attributes: [
                {
                    key: 'today',
                    highlight: true,
                    dates: new Date()
                }
            ],
            attributesM: [],
            attributesN: [],
            attributesA: [],
            attributesC: [],
            visit: _visit
        }
    },
    methods: {
        alterScheduleDate(){

            var morningFormat = [];
            this.morning.forEach(element => {
                morningFormat.push(moment(element).format("YYYY-MM-DD"));
            });
            var noonFormat = [];
            this.noon.forEach(element => {
                noonFormat.push(moment(element).format("YYYY-MM-DD"));
            });
            var afternoonFormat = []
            this.afternoon.forEach(element => {
                afternoonFormat.push(moment(element).format("YYYY-MM-DD"));
            });

            this.schedule_dates = JSON.stringify({
                "morning": morningFormat,
                "noon": noonFormat,
                "afternoon": afternoonFormat });

            this.attributesM = [
                {
                    highlight: true,
                    dates: this.morning
                }
            ];
            this.attributesN = [
                {
                    highlight: true,
                    dates: this.noon
                }
            ];
            
            this.attributesA = [
                {
                    highlight: true,
                    dates: this.afternoon
                }
            ];

        },
        getSchedule(){
            axios.get('/schedules/get-shedule-property/'+_property, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                this.schedule_dates = JSON.parse(response.data.schedule_dates);
                var morningFormat = JSON.parse(response.data.schedule_dates).morning;
                morningFormat.forEach(element => {
                    this.morning.push(moment(element)['_d']);
                    this.morningC.push(element);
                });
                var noonFormat = JSON.parse(response.data.schedule_dates).noon;
                noonFormat.forEach(element => {
                    this.noon.push(moment(element)['_d']);
                    this.noonC.push(element);
                });

                this.noonC = noonFormat;

                this.noonC = noonFormat;
                var afternoonFormat = JSON.parse(response.data.schedule_dates).afternoon;
                afternoonFormat.forEach(element => {
                    this.afternoon.push(moment(element)['_d']);
                    this.afternoonC.push(element);
                });

                this.attributesM = [
                    {
                        highlight: true,
                        dates: this.morningC
                    }
                ];

                this.attributesN = [
                    {
                        highlight: true,
                        dates: this.noonC
                    }
                ];
                this.attributesA = [
                    {
                        highlight: true,
                        dates: this.afternoonC
                    }
                ];
                
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getSchedule(); 
    },
    computed: {
    }
});





/*const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
import 'vuetify/dist/vuetify.min.css';

const schedule = new Vue({
    el: '#schedule',
    components : {
    },
    data() {
        return {
            morning: [],
            noon: [],
            afternoon: [],
            visit: 0,
            menu: false,
            now: new Date().toISOString().split('T')[0],
            property: document.getElementById('property_id').value
        }
    },
    methods: {
        saveSchedule(){
            const params = new FormData()
            
            params.append('_token', _token);
            params.append('morning', this.morning);
            params.append('noon', this.noon);
            params.append('afternoon', this.afternoon);

            axios.post('/schedules/save-shedule-property/'+this.property, params, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                toastr.success('Se ha almacenado la información correctamente.');
                
            }).catch((error) => {
                toastr.error('Ha ocurrido un error');
            });
        },
        getSchedule(){
            axios.get('/schedules/get-shedule-property/'+this.property, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                this.morning = JSON.parse(response.data.visit_from_date).morning;
                this.noon = JSON.parse(response.data.visit_from_date).noon;
                this.afternoon = JSON.parse(response.data.visit_from_date).afternoon;
                
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getSchedule(); 
    },
})*/