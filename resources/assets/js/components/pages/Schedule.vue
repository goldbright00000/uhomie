<template>
  <div class="schedule" ref="schedule-form-container">
    <div class="need-login" v-show="!is_login">Debes iniciar sesion para agendar una visita.</div>
    <form method="POST" @submit.prevent="submitForm">
      <h1 class="title">Agenda una visita</h1>
      <div class="days">
        <h2>Selecciona un día</h2>
        <input type="hidden" v-model="property_id" name="property_id" required>
        <div class="options-wrapper">
          <div class="option" v-for="(date, index) in available_dates" :key="index">
            <label>
              <input
                type="radio"
                v-model="schedule_date"
                name="schedule_date"
                :value="formatDate(date)"
                required
              >

              <div class="text">
                <span>{{ getDay(date) }}</span>
                <span>{{ date.getDate() }}</span>
              </div>
            </label>
          </div>
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
                required
                :disabled="availableRange('9-12') == 0"
              >
              <div class="text">
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
                required
                :disabled="availableRange('12-3') == 0"
              >
              <div class="text">
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
                required
                :disabled="availableRange('3-7') == 0"
              >
              <div class="text">
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
export default {
  props: {
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
    }
  },
  data: function() {
    return {
      config: {
        max_days: 4
      },
      schedule_date: null,
      schedule_range: null,
      is_login: true,
      sending: false,
      errors: {},
      success_msg: ""
    };
  },
  computed: {
    fromDate: function() {
      return new Date(this.from_date);
    },
    toDate: function() {
      return new Date(this.to_date);
    },
    available_dates: function() {
      let vm = this,
        dates = [];

      if (isNaN(vm.fromDate) || isNaN(vm.toDate)) return dates;

      let toDay = new Date(),
        initDate = new Date(toDay.getTime() + 1);

      if (initDate.getTime() < vm.fromDate.getTime()) initDate = vm.fromDate;

      // If init date is out of range
      if (initDate.getTime() > vm.toDate.getTime()) return dates;

      for (let days = 0; days < vm.config.max_days; days++) {
        let date = new Date(initDate.getTime());

        date.setDate(initDate.getDate() + days);

        dates.push(date);
      }

      return dates;
    }
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
  mounted: function() {},
  updated: function() {},
  methods: {
    submitForm: function() {
      let vm = this;

      if (vm.sending) return false;
      vm.sending = true;
      vm.errors = [];
      vm.success_msg = "";

      Axios.post("/schedules", {
        property_id: vm.property_id,
        schedule_date: vm.schedule_date,
        schedule_range: vm.schedule_range
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
  }
};
</script>