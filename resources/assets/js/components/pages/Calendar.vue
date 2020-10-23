<template>
    <div id="schedule" class="schedule" ref="schedule-form-container">
        <div class="need-login" v-show="!is_login">Debes iniciar sesion para agendar una visita.</div>
        <form method="POST" @submit.prevent="submitForm">
        <h1 class="title">Agenda una visita</h1>
        <div class="days">
            <h2>Selecciona un día</h2>
            <input type="hidden" v-model="property_id" name="property_id" required>
            <div>
                <v-date-picker
                    is-inline
                    v-model="schedule_date"
                    is-expanded 
                    :attributes="attributes"
                    :available-dates="available_dates"
                    :color="color_calendar"
                    title-position="right"
                    :dayclick="alterScheduleRange()"
                    :columns="$screens({ default: 1, lg: 2 })"
                    >
                </v-date-picker>
            </div>
            <div class="error" v-if="errors && errors.schedule_date">{{ errors.schedule_date[0] }}</div>
        </div>
        <div class="hours">
            <h2>Elige un rango horario</h2>
            <div class="options-wrapper">
            <div class="option">
                <label>
                <input
                    v-model="schedule_range"
                    type="radio"
                    name="schedule_range"
                    value="9-12"
                    @click="schedule_range = '9-12'"
                    required
                    :disabled="morning_range"
                >
                <div :class="'text ' + button">
                    <span>Mañana</span>
                    <span>09-12</span>
                </div>
                </label>
            </div>
            <div class="option">
                <label>
                <input
                    v-model="schedule_range"
                    type="radio"
                    name="schedule_range"
                    value="12-3"
                    @click="schedule_range = '12-3'"
                    required
                    :disabled="noon_range"
                >
                <div :class="'text ' + button">
                    <span>Mediodía</span>
                    <span>12-15</span>
                </div>
                </label>
            </div>
            <div class="option">
                <label>
                <input
                    v-model="schedule_range"
                    type="radio"
                    name="schedule_range"
                    value="3-7"
                    @click="schedule_range = '3-7'"
                    required
                    :disabled="afternoon_range"
                >
                <div :class="'text ' + button">
                    <span>Tarde</span>
                    <span>15-19</span>
                </div>
                </label>
            </div>
            </div>
            <div class="error" v-if="errors && errors.schedule_range">{{ errors.schedule_range[0] }}</div>
        </div>
        <div class="errors">
            <div class="error others">{{ errors.property_id ? errors.property_id[0] : '' }}</div>
            <div class="error others">{{ errors.other ? errors.other[0] : '' }}</div>
        </div>
        <div class="success" v-show="success_msg.length > 0">{{ success_msg }}</div>
        <button :class="'button is-outlined ' + button" type="submit">Continuar</button>
        </form>
    </div>
    
</template>
<script>
import Axios from "axios";
import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';

// Use v-calendar & v-date-picker components
Vue.use(VCalendar, {
  componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
  //...,// ...other defaults
});
export default {
    name: 'Calendar',
    props: {
        schedule_dates: String,
        property_id: {
            required: true
        },
        from_date: {
            required: true
        },
        to_date: {
            required: true
        },
        range: {
            required: true
        },
        button: {
            required: true
        },
        type: {
            required: true
        }
    },
    data() {
        return {
            available: [],
            config: {
                max_days: 4
            },
            schedule_date: null,
            schedule_range: null,
            morning_range: false,
            noon_range: false,
            afternoon_range: false, 
            is_login: true,
            sending: false,
            errors: {},
            success_msg: ""
        }
    },
    computed:{
        color_calendar(){
            switch (this.button) {
                case 'is-basic':
                    return 'blue';
                    break;
                case 'is-select':
                    return 'purple';
                    break;
                case 'is-premium':
                    return 'pink';
                    break;
            
                default:
                    return 'blue';
                    break;
            }
        },
        attributes() {
            var morning = [];
            var morningFormat = JSON.parse(this.schedule_dates).morning;
            morningFormat.forEach(element => {
                morning.push(element);
            });
            var noon = []
            var noonFormat = JSON.parse(this.schedule_dates).noon;
            noonFormat.forEach(element => {
                noon.push(element);
            });
            var afternoon = []
            var afternoonFormat = JSON.parse(this.schedule_dates).afternoon;
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
        },
        fromDate: function() {
            return new Date(this.from_date);
        },
        toDate: function() {
            return new Date(this.to_date);
        },

        available_dates(){
            let vm = this;
            var available = [];
            var morningFormat = JSON.parse(vm.schedule_dates).morning;
            morningFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });
            var noonFormat = JSON.parse(vm.schedule_dates).noon;
            noonFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });
            var afternoonFormat = JSON.parse(vm.schedule_dates).afternoon;
            afternoonFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });

            if(available.length == 0)
            {
                available = true;
            }

            return available;
        }

    },
    methods: {
        schedule_attr(){
            var available = [];
            var morningFormat = JSON.parse(this.schedule_dates).morning;
            morningFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });
            var noonFormat = JSON.parse(this.schedule_dates).noon;
            noonFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });
            var afternoonFormat = JSON.parse(this.schedule_dates).afternoon;
            afternoonFormat.forEach(element => {
                if(moment(moment().format('YYYY-MM-DD')).isBefore(element)){
                    available.push({start: element, end: element});
                }
            });

            if(available.length() == 0)
            {
                available = true
            }

            this.available = available;

        },

        alterScheduleRange(){

            //this.schedule_range = null;
            var available_m = JSON.parse(this.schedule_dates).morning;
            var find_morning = [];
            find_morning = available_m.filter(available => moment(this.schedule_date).format('YYYY-MM-DD') == available);

            if(find_morning.length < 1){
                this.morning_range = true;
            } else { 
                this.morning_range = false;
            }

            var available_n = JSON.parse(this.schedule_dates).noon;
            var find_noon = [];
            find_noon = available_n.filter(available => moment(this.schedule_date).format('YYYY-MM-DD') == available);

            if(find_noon.length < 1){
                this.noon_range = true;
            } else {
                this.noon_range = false;
            }

            var available_a = JSON.parse(this.schedule_dates).afternoon;
            var find_afternoon = [];
            find_afternoon = available_a.filter(available => moment(this.schedule_date).format('YYYY-MM-DD') == available);

            if(find_afternoon.length < 1){
                this.afternoon_range = true;
            } else {
                this.afternoon_range = false; 
            }
        },

        submitForm: function() {
            let vm = this;

            if (vm.sending) return false;
            vm.sending = true;
            vm.errors = [];
            vm.success_msg = "";

            Axios.post("/schedules", {
                property_id: vm.property_id,
                schedule_date: moment(vm.schedule_date).format('YYYY-MM-DD'),
                schedule_range: vm.schedule_range,
                type: vm.type
            })
                .then(response => {
                // Add success message
                vm.success_msg = response.data;
                })
                .catch(e => {
                switch (e.response.status) {
                    // Not Authorized
                    case 401: {
                    vm.is_login = false;
                    break;
                    }
                    // Form Error
                    case 422: {
                    vm.errors = Object.assign({}, vm.errors, e.response.data.errors);
                    break;
                    }
                    default: {
                    console.log(e.response);

                    vm.errors = Object.assign({}, vm.errors, {
                        other: "Ha ocurrido un error inesperado. Intente nuevamente."
                    });
                    }
                }
                })
                .then(() => {
                vm.sending = false;
                });
            },
            formatDate: function(date) {
            let day = date.getDate(),
                month = date.getMonth() + 1,
                year = date.getFullYear();

            if (day < 10) day = "0" + day;
            if (month < 10) month = "0" + month;

            return year + "-" + month + "-" + day;
            },
            availableRange: function(range) {
            let vm = this;

            if (!vm.range || vm.range.length < 1) return false;

            return vm.range.split(" ").indexOf(range) != -1;
            },
            getDay: function(date) {
            let days = [
                "Domingo",
                "Lunes",
                "Martes",
                "Miercoles",
                "Jueves",
                "Viernes",
                "Sabado"
            ];
            return days[date.getDay()];
            }
        
    },
    mounted() {
        this.schedule_attr();
    },
    watch: {
    sending: function(newValue, oldV) {
      let $button = this.$refs["schedule-form-container"].querySelector(
        "button[type=submit]"
      );
      if (newValue) {
        $button.classList.add("is-loading");
      } else {
        $button.classList.remove("is-loading");
      }
    }
  },
}
</script>

<style scoped>
.vc-rounded-lg {
    border-radius: 0;
}
</style>