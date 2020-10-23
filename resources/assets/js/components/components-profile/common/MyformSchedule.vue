<template>
    <div class="columns">
        <!--<div class="column">
            <div class="field">
                <div class="label">
                    <span>¿Deseas coordinar visitas de potenciales compradores?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="visit" value="1" :checked="info.visit" v-model="info.visit" :disabled="!editing" required>
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="visit" value="0" :checked="!info.visit" v-model="info.visit" :disabled="!editing" required>
                        No
                    </label>
                </div>
            </div>
            <div v-if="info.visit == 1">
                <div class="field">
                    <div class="label">
                        <span>Selecione dias disponibles para que potenciales compradores visiten tu proyecto</span>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-half">
                        <div class="field">
                            <div class="label">
                                <span>Desde</span>
                            </div>
                            <div class="control">
                                <input class="input date" v-model="info.visit_from_date" type="date" :disabled="!editing" format="dd-MM-yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="column is-half">
                        <div class="field">
                            <div class="label">
                                <span>Hasta</span>
                            </div>
                            <div class="control">
                                <input class="input date" v-model="info.visit_to_date" type="date" :disabled="!editing" format="dd-MM-yyyy">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="label-field">
                        <span>Elige un rango de horario</span>
                    </div>
                </div>
                <div class="field">
                    <label class="radio">
                        <input type="radio" :checked="info.schedule_range == '9-12'" v-model="info.schedule_range" :disabled="!editing" value="9-12" required>
                        Mañana 09 am - 12 m
                    </label>
                    <label class="radio">
                        <input type="radio" :checked="info.schedule_range == '12-3'" v-model="info.schedule_range" :disabled="!editing" value="12-3" required>
                        Mediodia 12 m - 03 pm
                    </label>
                    <label class="radio">
                        <input type="radio"  :checked="info.schedule_range == '3-7'" v-model="info.schedule_range" :disabled="!editing" value="3-7" required >
                        Tarde 03 pm - 07 pm
                    </label>
                </div>
            </div>
            <input type="hidden" v-model="info.id">
        </div>-->
        <div class="column">
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <div class="label">
                            <span>¿Deseas coordinar visitas de potenciales clientes?</span>
                        </div>
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="visit" value="1" :checked="info.visit" v-model="info.visit" :disabled="!editing">
                                Si
                            </label>
                            <label class="radio">
                                <input type="radio" name="visit" value="0" :checked="!info.visit" v-model="info.visit" :disabled="!editing">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="info.visit == 1">
                <div class="columns">
                    <div class="column">
                        <label>Elige un rango horario</label>
                        <div class="options-wrapper">
                            <div class="option" style="border: 0">
                                <label>
                                <input
                                    v-model="schedule_range"
                                    type="radio"
                                    name="schedule_range"
                                    value="9-12"
                                    @click="schedule_range = '9-12'"
                                    required
                                    :disabled="!editing"
                                >
                                <div class="text is-basic">
                                    <span>Mañana</span>
                                    <span>09-12</span>
                                </div>
                                </label>
                            </div>
                            <div class="option" style="border: 0">
                                <label>
                                <input
                                    v-model="schedule_range"
                                    type="radio"
                                    name="schedule_range"
                                    value="12-3"
                                    @click="schedule_range = '12-3'"
                                    required
                                    :disabled="!editing"
                                >
                                <div class="text is-basic">
                                    <span>Mediodía</span>
                                    <span>12-15</span>
                                </div>
                                </label>
                            </div>
                            <div class="option" style="border: 0">
                                <label>
                                <input
                                    v-model="schedule_range"
                                    type="radio"
                                    name="schedule_range"
                                    value="3-7"
                                    @click="schedule_range = '3-7'"
                                    required
                                    :disabled="!editing"
                                >
                                <div class="text is-basic">
                                    <span>Tarde</span>
                                    <span>15-19</span>
                                </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns" v-if="editing && schedule_range == '9-12'">
                    <div class="column">
                        <v-date-picker
                            v-model="morning" 
                            :min-date="now" 
                            :mode="mode"
                            is-inline
                            is-expanded
                            color="blue"
                            :attributes="attributesC"
                            :dayclick="alterScheduleDate()"
                            >
                        </v-date-picker>
                    </div>
                </div>
                <div class="columns" v-if="editing && schedule_range == '12-3'">
                    <div class="column">
                        <v-date-picker
                            v-model="noon" 
                            :min-date="now" 
                            :mode="mode"
                            is-inline
                            is-expanded
                            color="purple"
                            :attributes="attributesC"
                            :dayclick="alterScheduleDate()"
                            >
                        </v-date-picker>
                    </div>
                </div>
                <div class="columns" v-if="editing && schedule_range == '3-7'">
                    <div class="column">
                        <v-date-picker
                            v-model="afternoon" 
                            :min-date="now" 
                            :mode="mode"
                            is-inline
                            is-expanded
                            color="pink"
                            :attributes="attributesC"
                            :dayclick="alterScheduleDate()"
                            >
                        </v-date-picker>
                    </div>
                </div>
                <div class="columns" v-if="!editing">
                    <div class="column">
                        <v-calendar
                            :min-date="now"
                            is-expanded
                            :attributes="attributesC"
                        ></v-calendar>
                    </div>
                </div>
                <input type="hidden" v-model="info.schedule_dates"/>
                <input type="hidden" v-model="info.id">
            </div>
        </div>
    </div>
</template>
<script>

const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export default {
    name: 'MyformSchedule',
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
            var noonFormat = [];
            this.noon.forEach(element => {
                noonFormat.push(moment(element).format("YYYY-MM-DD"));
            });
            var afternoonFormat = []
            this.afternoon.forEach(element => {
                afternoonFormat.push(moment(element).format("YYYY-MM-DD"));
            });

            this.info.schedule_dates = JSON.stringify({
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
        saveSchedule(){

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
            const params = new FormData()
            
            params.append('_token', _token);
            params.append('morning', morningFormat);
            params.append('noon', noonFormat);
            params.append('afternoon', afternoonFormat);

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
                ]

                this.attributesN = [
                    {
                        highlight: true,
                        dates: this.noonC
                    }
                ]
                this.attributesA = [
                    {
                        highlight: true,
                        dates: this.afternoonC
                    }
                ]
                
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getSchedule(); 
    },
    computed: {
        attributesC() {
            if(JSON.parse(this.info.schedule_dates)){
                var morning = [];
                var morningFormat = JSON.parse(this.info.schedule_dates).morning;
                morningFormat.forEach(element => {
                    morning.push(element);
                });
                var noon = []
                var noonFormat = JSON.parse(this.info.schedule_dates).noon;
                noonFormat.forEach(element => {
                    noon.push(element);
                });
                var afternoon = []
                var afternoonFormat = JSON.parse(this.info.schedule_dates).afternoon;
                afternoonFormat.forEach(element => {
                    afternoon.push(element);
                });

                return [
                    {
                        dot: 'blue',
                        dates: morning,
                        popover: {
                            label: 'Mañana 9 a.m - 12 m',
                        }

                    },
                    {
                        dot: 'purple',
                        dates: noon,
                        popover: {
                            label: 'Mediodia 12 m - 15 p.m',
                        }
                    },
                    {
                        dot: 'pink',
                        dates: afternoon,
                        popover: {
                            label: 'Tarde 15 p.m - 19 p.m',
                        }
                    }
                ];
            } else {
                return [];
            }
            
        }
        
    }
}
</script>
<style scoped>
.vc-rounded-lg {
    border-radius: 0;
}
</style>