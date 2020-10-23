<template>
  <a href="#" @click.prevent="applyToProperty()" class="button is-outlined" ref="button" :id="'botonPostularJQ_'+property_id">
    <slot></slot>
  </a>
</template>
<script>
import Axios from "axios";
import Toast from "toastr";
const STATE = {
  INITIAL: 0,
  SENDING: 1,
  SUCCESS: 2,
  ERROR: 3
};
export default {
  props: {
    type_stay: String,
    property_id: {
      type: Number,
      required: true
    },
    has_apply: {
      type: Boolean
    },
    upgradeMembershipUrl: String,
  },
  data: function() {
    return {
      state: STATE.INITIAL,
      modal: false,
    };
  },
  watch: {
    has_apply: function(newV, oldV) {
      if (newV) {
        this.hasApply();
      }
    }
  },
  methods: {
    
    applyToProperty: function() {
      if(this.type_stay == 'SHORT_STAY'){
        this.modal = true;
        this.$emit('evento-abrir-modal-select-days', this.property_id);
        return;
      } else {
          let vm = this,
          el = vm.$refs["button"];

        if ([STATE.SENDING, STATE.SUCCESS].indexOf(vm.state) != -1) return false;

        vm.state = STATE.SENDING;
        el.classList.add("is-loading");
        console.log('antes de Axios');

        const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        Axios.defaults.headers.common = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN' : csrf_token
        };

        Axios.post("/properties/" + vm.property_id + "/apply")
          .then(response => {
            
            if(response.data.espera == 1){
              
              toastr['info']("Tu postulación ha sido recibida, pero mientras no verifiques tu identidad, la postulación estará en modo espera. Cuando tu identidad sea verificada tu postulación será enviada y te avisaremos via e-mail.");
            } else {
              vm.hasApply();
            }
            
          })
          .catch(e => {
            switch (e.response.status) {
              case 302: {
                console.log('caso 302');
              }
              case 401: {
                //Toast.danger('HOLa');
                toastr["warning"]("Para postular, debes iniciar sesion.");
                break;
              }
              case 403: {
                toastr["warning"](
                  "Debes ser arrendatario para postularte en una propiedad."
                );
                break;
              }
              case 422: {
                if(e.response.data.errors['user_id']){
                  toastr["error"]( e.response.data.errors['user_id'][0]);
                }
                if(e.response.data.errors['verification_link']){
                  let link = '<a target="_blank" href="'+e.response.data.errors['verification_link'][0]+'">Haz click aqui para iniciar la verificación de identidad</a>';
                  toastr['info'](link);
                }
                //toastr["info"](e.response.data.errors['upgrade_link'][0]? '<a target="_blank" href="'+e.response.data.errors['upgrade_link'][0]+'">Haz click aqui para mejorar tu membresia</a>': '');
                if(e.response.data.errors['upgrade_link']){
                  let link = '<a target="_blank" href="'+e.response.data.errors['upgrade_link'][0]+'">Haz click aqui para mejorar tu membresia</a>';
                  toastr['info'](link);
                }
                if(e.response.data.errors['verification']){
                  console.log('deberia imprimir el toastr de verificacion');
                  toastr["info"](e.response.data.errors['verification'][0]);
                }
                /*
                for (let field in e.response.data.errors) {
                  toastr["error"](e.response.data.errors[field][0]? e.response.data.errors[field][0]: '');
                }
                */
                /*toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;*/
                //toastr.options.closeButton = true;
                //toastr.info("<a target='_blank' href='/users/"+this.$attrs.upgrademembershipurl+"/memberships-upgrade'>Haz click aquí para mejorar tu membresía</a>", "uHomie", {timeOut: 15000, extendedTimeout: 10000, closeButton: true, onClick: function() { console.log('clicked'); } });
                break;
              }
              case 302: {
                console.log('error 302');
                break;
              }
              default: {
                toastr["error"]("Ha ocurrido un error inesperado.");
              }
            }

            vm.state = STATE.ERROR;
          })
          .then(e => {
            el.classList.remove("is-loading");
          });
      }

      
    },
    hasApply: function() {
      let vm = this,
        el = vm.$refs["button"];

      vm.state = STATE.SUCCESS;
      el.textContent = "¡Postulado!";
      el.classList.add("is-active");
    }
  }
};
</script>

