<template>
    <div class="columns is-inline" style="padding-bottom: 20px;" v-if="info">
        <div class="column">
            <div class="field">
                <label class="label">Nombre:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Nombre" v-model="firstname" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Apellido:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Apellidos" v-model="lastname" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Email:</label>
                <div class="control">
                    <input ref="email" @keyup="checkCollateralEmail()" class="input" type="email" placeholder="Email" v-model="info.email" :disabled="!editing">
                </div>
            </div>
            <span v-if="error">
                {{ error }}
            </span>
        </div>
        <div class="column" v-if="exist">
            <label class="checkbox">
                <input type="checkbox" v-model="want">
                La direccion de correo electronico suministrada pertenece a un usuario existente dentro de la plataforma , ¿desea que este usuario le sirva como aval?.
                
            </label>
        </div>
        <div class="column" v-if="info.email" > 
            <button class="button is-primary" :disabled="!puedeReenviar"  @click="reenviarEmail"><span class="icon">
                <i class="fa fa-paper-plane"></i>
                </span><span>Reenviar email</span>
            </button>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'MyformAval',
        props: {
            editing: Boolean,
            info: {
                type: Object,
                default: () =>({
                    firstname: '',
                    lastname: '',
                    email: ''
                })
            },
            saving: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            this.firstname = this.info.firstname
            this.lastname = this.info.lastname
        },
        data() {
            return {
                firstname: '',
                lastname: '',
                error: null,
                timer: null,
                want: false,
                exist: false,
                puedeReenviar: true
            }
        },
        watch: {
            /*
            info: {
                handler(val){
                    this.firstname = val.firstname
                    this.lastname = val.lastname
                },
                deep: true
            },*/
            saving: function(newVal) {
                if(newVal) this.setAval()
            }
        },

        methods: {
            checkCollateralEmail() {
                const self = this
                var email = this.$refs.email.value
                if(this.timer) clearTimeout(this.timer)

                this.timer = setTimeout(function() {
                    axios.get('/users/check-collateral-mail', { 
                        params: { 'email': email }
                    }).then(function(res) {
                        if (res.data.exist == false && res.data.data == null) {
                            //self.firstname = ''
                            //self.lastname = '' // no existe y ? no eres tu?
                            console.log('WOWOWOWOW no existe y no eres tu');
                            self.$emit('blockingSave', false)
                            self.error = null
                            self.exist = false
                        } else if(res.data.exist == true && res.data.data == null) { // si existe?? pero si abajo se setea que no existe
                            //self.firstname = ''
                            //self.lastname = ''
                            //console.log('deberia imprimir No puedes ser tu propio aval');
                            console.log('eres tu');
                            self.exist = false // No existe y ?? eres tu ?
                            self.$emit('blockingSave', true)
                            self.error = 'No puedes ser tu propio aval'
                        } else { // si existe y se llenan los datos del response
                            self.firstname = res.data.data.firstname
                            self.lastname = res.data.data.lastname
                            self.exist = true
                            self.$emit('blockingSave', false)
                            self.error = null
                        }
                    }).catch(function(err) {
                        console.log(err)
                    })
                }, 1000)
            },
            setAval() {
                if (!this.firstname.length && !this.lastname.length && !this.info.email.length) {
                    this.$emit('blockingSave', true)
                    this.error = 'Complete todos los campos'
                    return
                } 

                this.error = null
                
                if(this.exist && !this.want) {
                    this.$emit('blockingSave', true)
                }

                this.$emit('blockingSave', false)

                var params = {
                    collateral: 1
                }

                if (this.exist) {
                    params.collateral_email = this.info.email
                    console.log('si existe y va a enviar collateral_mail');
                } else {
                    params.firstname = this.firstname
                    params.lastname = this.lastname
                    params.email = this.info.email
                    params.action = 'set_change_collateral'
                    //params.collateral_email = this.info.email
                    
                }

                axios.post('/users/tenant/registration/first-step/three', 
                    params
                ).then(function(res) {
                    toastr['success']('Hemos enviado un mail de confirmacion a su Aval');
                    console.log(res)
                }).catch(function(err) {
                    console.log(err)
                })

            },
            reenviarEmail(event){
                this.puedeReenviar = false;
                console.log(event);
                var self = this;
                /*
                axios.post('/users/tenant/registration/first-step/three', 
                    params
                ).then(function(res) {
                    toastr['success']('Hemos enviado un mail de confirmacion a su Aval');
                    console.log(res)
                }).catch(function(err) {
                    console.log(err)
                })
                */
               this.setAval();
                setTimeout(function(){ 
                    self.puedeReenviar = true;
                }, 15000);
            }
        
        }
    }
</script>
