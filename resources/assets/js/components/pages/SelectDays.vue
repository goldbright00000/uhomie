<template>
    <div class="modal " id="modalSelectDays" style="z-index:100">
        <div class="modal-background" @click="cerrarModalSelectDays"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Seleccione los dias que desea reservar</p>
                <button class="delete botonCerrarModalSelectDays" @click="cerrarModalSelectDays" aria-label="close" ></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column">
                        <v-date-picker
                            style="border-radius:0;"
                            v-model="fechas" 
                            :min-date='new Date()'
                            :mode="mode"
                            is-inline
                            is-expanded
                            color="blue"
                            :attributes="attributesC"
                            :dayclick="alterScheduleDate()"
                            :disabled-dates="fechas_ocupadas"
                            :columns="$screens({ default: 1, lg: 2 })">
                        </v-date-picker>
                    </div>
                </div>
                <div v-if="count_days >= 1">
                    <div class="columns" style="margin-left: 0.1rem; margin-right: 0.1rem;">
                        <div class="column is-half" style="border:1px solid #cbd5e0">
                            <div class="field has-addons">
                            <p class="control">
                                <a class="button is-white is-small">
                                Entrada
                                </a>
                            </p>
                            <p class="control">
                                <input class="input is-small" v-model="check_in" type="text" style="border:0">
                            </p>
                            </div>
                        </div>
                        <div class="column is-half" style="border:1px solid #cbd5e0">
                            <div class="field has-addons">
                            <p class="control">
                                <a class="button is-white is-small">
                                Salida
                                </a>
                            </p>
                            <p class="control">
                                <input class="input is-small" v-model="check_out" type="text" style="border:0">
                            </p>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>
                            CLP <strong>{{ moneyFormat(property.rent) }}</strong> x {{ count_days }} Días 
                            </p>
                        </div>
                        <div class="column is-half">
                            <p>
                            CLP <strong>{{ moneyFormat(property.rent * count_days ) }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>Tarifa de limpieza</p>
                        </div>
                        <div class="column is-half">
                            <p>
                            CLP <strong>{{moneyFormat(property.cleaning_rate)}}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="columns is-gapless is-mobile" style="margin:0" v-if="offer_week > 0">
                        <div class="column is-half">
                            <p>Oferta Semanal de {{property.week_sale}}%</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{ moneyFormat(offer_week) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="columns is-gapless is-mobile" style="margin:0" v-if="offer_month > 0">
                        <div class="column is-half">
                            <p>Oferta Mensual de {{property.month_sale}}%</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{ moneyFormat(offer_month) }}</strong>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>Sub Total</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{ moneyFormat(sub_total) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>Comisión por servicio</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{ moneyFormat(commission) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div v-if="false" class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>IVA 19%</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{ moneyFormat(total_iva) }}</strong>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="columns is-gapless is-mobile" style="margin:0">
                        <div class="column is-half">
                            <p>Total</p>
                        </div>
                        <div class="column">
                            <p>
                            CLP <strong>{{moneyFormat(total_stay)}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
            <button class="button is-success" ref="reservarPropiedad" :disabled="count_days < 1" @click="postularArriendoCorto">Reservar</button>
            <button class="button botonCerrarModalSelectDays" @click="cerrarModalSelectDays">Cancelar</button>
            </footer>
        </div>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    name: 'SelectDays',
    props: {
        property_id: Number,
        fechas_ocupadas: Array,
        property: Object
    },
    methods:{
        postularArriendoCorto: function(){
            console.log('enviar postulacion');
            var vm = this;

            var dates = {
                start: moment(vm.fechas.start).format("YYYY-MM-DD"),
                end: moment(vm.fechas.end).format("YYYY-MM-DD")
            }

            vm.schedule_dates = JSON.stringify(dates);
            vm.$refs["reservarPropiedad"].classList.add('is-loading');
            Axios.post("/properties/" + vm.property.id + "/apply", {days: vm.schedule_dates})
                .then(response => {
                
                    if(response.data.espera == 2){
                        toastr['info']("Tu postulación ha sido recibida, pero mientras no verifiques tu identidad, la postulación estará en modo espera. Cuando tu identidad sea verificada tu postulación será enviada y te avisaremos via e-mail.");
                        vm.$refs["reservarPropiedad"].classList.remove('is-loading');
                        vm.cerrarModalSelectDays();
                    } else {
                        vm.$refs["reservarPropiedad"].textContent = "Postulado";
                        vm.$refs["reservarPropiedad"].classList.remove('is-loading');
                        vm.cerrarModalSelectDays();
                        toastr['success']("Se ha guardado la postulación");
                        setTimeout(function(){
                            window.location.href = "/users/payments/"+vm.property.id+"/short_stay";
                        }, 2000);
                    }
                
                })
                .catch(e => {
                    vm.$refs["reservarPropiedad"].classList.remove('is-loading');
                    vm.cerrarModalSelectDays();
                    switch (e.response.status) {
                        case 302: {
                            console.log('caso 302');
                        }
                        case 401: {
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
                            if(e.response.data.errors['upgrade_link']){
                                let link = '<a target="_blank" href="'+e.response.data.errors['upgrade_link'][0]+'">Haz click aqui para mejorar tu membresia</a>';
                                toastr['info'](link);
                            }
                            if(e.response.data.errors['verification']){
                                console.log('deberia imprimir el toastr de verificacion');
                                toastr["info"](e.response.data.errors['verification'][0]);
                            }
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
                })
        },
        alterScheduleDate(){

            this.check_in = this.fechas.start ? moment(this.fechas.start).format("DD-MM-YYYY") : ''
            this.check_out = this.fechas.end ? moment(this.fechas.end).format("DD-MM-YYYY") : ''

            var start = moment(this.fechas.start)
            var end = moment(this.fechas.end)

            this.offer_week = 0
            this.offer_month = 0

            this.count_days = this.fechas.end ? end.diff(start, 'days') + 1 : 0;
            var total_days = parseInt(this.property.rent) * parseInt(this.count_days)
            
            
            if(parseInt(this.property.week_sale) > 0 && this.count_days > 7 && this.count_days < 30){
            this.offer_week = parseFloat(total_days) * parseFloat((parseInt(this.property.week_sale) / 100))
            }
            if(parseInt(this.property.month_sale) > 0 && this.count_days > 30){
            this.offer_month = parseFloat(total_days) * parseFloat((parseInt(this.property.month_sale) / 100))
            }
            this.sub_total = (parseInt(total_days) + parseInt(this.property.cleaning_rate)) - parseFloat(this.offer_week) - parseFloat(this.offer_month)
            this.commission = parseFloat(parseFloat(this.sub_total)) * 0.07
            this.total_iva =  (parseInt(this.sub_total) + parseFloat(this.commission)) * this.iva
            // Con IVA
            //this.total_stay =  parseFloat(this.sub_total) + parseFloat(this.total_iva) + parseFloat(this.commission)

            //Sin IVA

            this.total_stay =  parseFloat(this.sub_total) + parseFloat(this.commission)

        },
        moneyFormat: function(n, c, d, t) {
            var c = isNaN((c = Math.abs(c))) ? 0 : c,
                d = d == undefined ? "," : d,
                t = t == undefined ? "." : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            return (
                s +
                (j ? i.substr(0, j) + t : "") +
                i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
                (c
                ? d +
                    Math.abs(n - i)
                    .toFixed(c)
                    .slice(2)
                : "")
            );
        },
        cerrarModalSelectDays: function(){
            console.log('funcion cerrarModalSelectDays');
            $('#modalSelectDays').removeClass('is-active');
        },
        
    },
    data(){
        return {
            active: false,
            check_in: '',
            check_out: '',
            count_days: '',
            commission: 0,
            sub_total: 0,
            total_stay: 0,
            total_iva: 0,
            iva: 0.19,
            offer_week: 0,
            offer_month: 0,
            schedule_dates: [],
            dias_seleccionados: [],
            fechas_ocupadas: null,
            attributesC: [],
            now: Date(),
            fechas: [],
            modal: false,
            mode: 'range',
        }
    },
    computed: {
        fechas_ocupadas_computado: function(){
            if( !this.fechas_ocupadas ) return null;
            else {
                let arreglo = [];
                this.fechas_ocupadas.forEach(function(elem){
                arreglo.push({start: elem, end: elem});
                });
                return arreglo;
            }
        },
    },
    mounted(){
        this.active = true;
    }
}
</script>