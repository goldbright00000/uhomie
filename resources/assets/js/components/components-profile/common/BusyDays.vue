<template>
    <div class="columns">
        
        <div class="column">
                <div class="columns" >
                    <div class="column">
                        <v-date-picker
                            v-model="morning" 
                            :min-date="now" 
                            :mode="mode"
                            is-inline
                            is-expanded
                            color="blue"
                            :dayclick="alterScheduleDate()"
                            >
                        </v-date-picker>
                    </div>
                </div>
                <input type="hidden" v-model="info.schedule_dates"/>
                <input type="hidden" v-model="info.id">
            
        </div>
    </div>
</template>
<script>

const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export default {
    name: 'BusyDays',
    props: {
        editing: Boolean,
        info: Object
    },
    components:{
    },
    data () {
        return {
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
            attributesA: []
        }
    },
    methods: {
        alterScheduleDate(){

            var morningFormat = [];
            this.morning.forEach(element => {
                morningFormat.push(moment(element).format("YYYY-MM-DD"));
            });

            this.info.schedule_dates = JSON.stringify(morningFormat);

            

        },
        saveSchedule(){

            var morningFormat = [];
            this.morning.forEach(element => {
                morningFormat.push(moment(element).format("YYYY-MM-DD"));
            });
            const params = new FormData()
            
            params.append('_token', _token);
            params.append('morning', morningFormat);

            axios.post('/schedules/save-shedule-property/'+this.info.id, params, {
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
            axios.get('/schedules/get-shedule-property/'+this.info.id, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                var morningFormat = JSON.parse(response.data.schedule_dates);
                morningFormat.forEach(element => {
                    this.morning.push(moment(element)['_d']);
                    this.morningC.push(element);
                });

                this.attributesM = [
                    {
                        highlight: true,
                        dates: this.morningC
                    }
                ]
                
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getSchedule(); 
    }
}
</script>
<style scoped>
.vc-rounded-lg {
    border-radius: 0;
}
</style>