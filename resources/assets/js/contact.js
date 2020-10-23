import VueRecaptcha from "vue-recaptcha";
import Axios from "axios";

const Contact = new Vue({
  el: "#contact",
  delimiters: ["{{", "}}"],
  components: {
    VueRecaptcha
  },
  data() {
    return {
      state: null,
      form: {
        name: "",
        phone: "",
        email: "",
        reason_contact: "",
        message: "",
        recaptcha: null
      },
      errors: {}
    };
  },
  mounted: function() {
    let vm = this;
  },
  computed: {
    STATE: () => {
      return {
        FILLING: 0,
        SENDING: 1,
        SUCESS: 2,
        FAIL: 3
      };
    }
  },
  watch: {
    state: function(oldV, newV) {
      let vm = this;
      switch (this.state) {
        case vm.STATE.SUCESS: {
          // Show modal.
          vm.$refs["modal"].classList.add("is-active");

          break;
        }
      }
    }
  },
  methods: {
    submitForm: function() {
      let vm = this;

      if (vm.state == vm.STATE.SENDING) return;

      vm.state = vm.STATE.SENDING;
      vm.errors = {};

      Axios.post("/contacto", vm.form)
        .then(response => {
          window.scrollTo(0, 0);
          vm.state = vm.STATE.SUCESS;

          // Reset
          vm.$refs["form"].reset();
          vm.form = Object.assign({}, vm.form, {
            name: null,
            phone: null,
            email: null,
            reason_contact: null,
            message: null,
            recaptcha: null
          });
        })
        .catch(e => {
          vm.state = vm.STATE.FAIL;

          switch (e.response.status) {
            case 422: {
              vm.errors = Object.assign({}, vm.errors, e.response.data.errors);
              break;
            }
            default: {
              alert(
                "Ha ocurrido un error inesperado. Por favor, recargue la pagina e intente nuevamente."
              );
            }
          }
        })
        .then(() => {
          vm.$refs.recaptcha.reset();
        });

      return false;
    },
    onReCaptchaVerify: function(captchaToken) {
      this.form.recaptcha = captchaToken;
    },
    onReCaptchaExpired: function() {
      //Reload captcha
      this.ref.recaptcha.reset();
    }
  }
});

/* --- MODAL SECTION --- */
const close_modal_button = document.querySelector(
  ".success-modal button[aria-label=close]"
);

close_modal_button.onclick = function() {
  document.querySelector(".success-modal").classList.remove("is-active");
};
