<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="column is-12">
            <div class="field" v-if="info.type_stay == 'LONG_STAY'">
                <label class="label">Gastos Comunes:</label>
                <div class="control">
                    <div class="control has-icons-left has-icons-right">
                        <vue-numeric class="input" separator="." required v-model="info.common_expenses_limit" :disabled="!editing"></vue-numeric>
                        <span class="icon is-small is-left">
                                        $
                                    </span>
                        <span class="icon is-small is-right" v-if="typeProperty == 0">
                                        CLP
                                    </span>
                        <span class="icon is-small is-right" v-if="typeProperty == 1">
                                        UF
                                    </span>
                    </div>

                </div>
            </div>
            <!--
            <div class="field" >
                <label class="label">Gastos Comunes:</label>
                <div class="control">
                    <div class="control has-icons-left has-icons-right">
                        <vue-numeric class="input" separator="." required v-model="info.common_expenses_limit" :disabled="!editing"></vue-numeric>
                        <span class="icon is-small is-left">
                                        $
                                    </span>
                        <span class="icon is-small is-right" v-if="typeProperty == 0">
                                        CLP
                                    </span>
                        <span class="icon is-small is-right" v-if="typeProperty == 1">
                                        UF
                                    </span>
                    </div>

                </div>
            </div>
            -->
            <div class="field" v-if="typeProperty == 1">
                <label class="label">Multa por morosidad de no pago:</label>
                <div class="control">
                    <div class="control has-icons-left has-icons-right">
                        <vue-numeric class="input" separator="." required v-model="info.penalty_fees" :disabled="!editing"></vue-numeric>
                        <span class="icon is-small is-left">
                                        $
                                    </span>
                        <span class="icon is-small is-right">
                                        UF
                                    </span>
                    </div>

                </div>
            </div>

            

            <div class="field" v-if="info.property_type_id != '1' && info.type_stay == 'LONG_STAY'">
                <label class="label">
                    <span>Meses de Garantía:</span>
                </label>
                <div class="control">
                    <div class="select">
                        <select v-model="info.warranty_months_quantity" :disabled="!editing">
                            <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.warranty_months_quantity">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field"  v-if="info.type_stay == 'LONG_STAY'">
                <div class="label">
                    <span>Meses de Adelanto:</span>
                </div>
                <div class="control">
                    <div class="select">
                        <select v-model="info.months_advance_quantity" :disabled="!editing">
                            <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.months_advance_quantity">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="field" >
                <label class="label">
                    <span>{{info.type_stay == 'LONG_STAY' ? 'Fecha disponible para arrendar:' : 'Fecha disponible para empezar a recibir huéspedes'}}</span>
                </label>
                <div class="control">
                    <input v-if="!editing" class="input" type="text" placeholder="Fecha de Nacimiento"
                           v-model="info.available_date" :max="maxDate" :disabled="!editing">
                    <v-date-picker
                        v-if="editing"
                        v-model='available_date'
                        :min-date="maxDate" 
                        :dayclick="alterAvailableDate()"
                        :input-props='{
                            placeholder: "Seleccione una fecha",
                            readonly: true,
                            class: "input date",
                            style:"border-radius: 0; border-bottom: 1px solid #0a0a0a;"
                        }'
                    />
                </div>
            </div>

            <div class="field"  v-if="info.type_stay == 'LONG_STAY'">
                <div class="label">
                    <span>Tiempo de arriendo mínimo:</span>
                </div>
                <div class="control">
                    <div class="select">
                        <select v-model="info.tenanting_months_quantity" :disabled="!editing">
                            <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.tenanting_months_quantity">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field"  v-if="info.type_stay == 'LONG_STAY' && info.property_type_id != '1'" >
                <div class="label">
                    <span>¿Requieres Seguro de Arriendo?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" value="1" :checked="info.tenanting_insurance" v-model="info.tenanting_insurance" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" value="0" :checked="!info.tenanting_insurance" v-model="info.tenanting_insurance" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0 && info.type_stay == 'LONG_STAY'" >
                <div class="label">
                    <span>¿Exiges Aval?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" value="1" :checked="info.collateral_require" v-model="info.collateral_require" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" value="0" :checked="!info.collateral_require" v-model="info.collateral_require" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field"  v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label">
                    <span>¿Deseas agregar oferta especial 10% de descuento a tus primeros 5 huéspedes?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="special_sale" value="1" :checked="info.special_sale == '1'" v-model="info.special_sale" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="special_sale" value="0" :checked="info.special_sale == '0'" v-model="info.special_sale" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field is-horizontal" style="margin-bottom: 1rem;" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                    <img src="/images/icono-estrella.png">
                    <span>Oferta semanal</span>
                </div>
                <div class="field-body">
                    <div class="field is-expanded">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" @click="poesia(-1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-minus"></i>
                                </a>
                            </p>
                            <p class="control is-expanded">
                                <input class="input"  name="week_sale" v-model="campo_oferta_semanal"  type="text" readonly>
                            </p>
                            <p class="control" style="flex-basis: 0%;" @click="poesia(1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-plus"></i>
                                </a>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="field is-horizontal" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                </div>
                <div class="field-body">
                    <div class="field is-expanded" style="display: inline">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" ></p>
                            <p></p>
                            <p class="control" style="flex-basis: 0%;" ></p>
                        </div>
                        <p class="help" style="font-size: 0.85rem;" v-html="help_oferta_semanal"></p>
                    </div>
                </div>
            </div>
            <!-- SEPARADOR -->
            <div class="field is-horizontal" style="margin-bottom: 1rem;" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                    <img src="/images/icono-estrella.png">
                    <span>Oferta mensual</span>
                </div>
                <div class="field-body">
                    <div class="field is-expanded">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" @click="poesiam(-1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-minus"></i>
                                </a>
                            </p>
                            <p class="control is-expanded">
                                <input class="input" name="month_sale" v-model="campo_oferta_mensual" type="text" readonly>
                            </p>
                            <p class="control" style="flex-basis: 0%;" @click="poesiam(1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-plus"></i>
                                </a>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="field is-horizontal" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                </div>
                <div class="field-body">
                    <div class="field is-expanded" style="display: inline">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" ></p>
                            <p></p>
                            <p class="control" style="flex-basis: 0%;" ></p>
                        </div>
                        <p class="help" style="font-size: 0.85rem;" v-html="help_oferta_mensual"></p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal" style="margin-bottom: 1rem;" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                    <img src="/images/icono-empleo.png">
                    <span>Hora de llegada es a partir de:</span>
                </div>
                <div class="field-body">
                    <div class="field is-expanded">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" @click="poesiahl(-1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-minus"></i>
                                </a>
                            </p>
                            <p class="control is-expanded">
                                <input class="input" name="checkin_hour"  v-model="campo_hora_llegada" type="text" readonly>
                            </p>
                            <p class="control" style="flex-basis: 0%;" @click="poesiahl(1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-plus"></i>
                                </a>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="field is-horizontal" style="margin-bottom: 1rem;" v-if="info.type_stay == 'SHORT_STAY'">
                <div class="label-field">
                    <img src="/images/icono-seguro.png">
                    <span>Noches mínimas de alojamiento:</span>
                </div>
                <div class="field-body">
                    <div class="field is-expanded">
                        <div class="field has-addons">
                            <p class="control" style="flex-basis: 0%;" @click="poesianm(-1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-minus"></i>
                                </a>
                            </p>
                            <p class="control is-expanded">
                                <input class="input" name="minimum_nights" v-model="campo_noches_minimas" type="text" readonly>
                            </p>
                            <p class="control" style="flex-basis: 0%;" @click="poesianm(1)">
                                <a class="button is-static" >
                                    <i  class="fa fa-plus"></i>
                                </a>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="field" v-if="typeProperty == 1">
                <div class="label">
                    <span>¿Requiere boleta de garantia?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" value="1" :checked="info.warranty_ticket == 1" v-model="info.warranty_ticket" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" value="0" :checked="info.warranty_ticket == 0" v-model="info.warranty_ticket" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div v-if="info.warranty_ticket == '1'">
                <div class="field">
                    <label class="label">Monto de la boleta de garantia:</label>
                    <div class="control has-icons-left has-icons-right">
                        <vue-numeric class="input" separator="." required v-model="info.warranty_ticket_price" :disabled="!editing"></vue-numeric>
                        <span class="icon is-small is-left">
                                        $
                                    </span>
                        <span class="icon is-small is-right">
                                        UF
                                    </span>
                    </div>
                </div>
            </div>
    <!--      
            <div class="field">
                <div class="label">
                    <span>¿Propiedad Amoblada?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="furnished" value="1" :checked="info.furnished" v-model="info.furnished" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="furnished" value="0" :checked="!info.furnished" v-model="info.furnished" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div>

                    <div><b>Muebles de tu Propiedad:</b></div>
                    <textarea rows="6" style="width: 100%;padding: 6px;" v-model="info.furnished_description"></textarea>

            </div>
    -->
            <div class="field" v-if="info.type_stay == 'LONG_STAY' && info.property_type_id != '1'" >
                <div class="label">
                    <span>Condiciones Anexos</span>
                </div>
                <div class="control">
                    <editor v-model="info.anexo_conditions" api-key="sq59jsf3dzdvpq8lzi0dp2qhn5w0fdth5v28cgwtke1jxkkq" :init="{plugins: 'wordcount'}"></editor>
                </div>
            </div>
        </div>


        


    </div>
</template>

<script>



    const imagesDir = document.getElementById('images-dir').value;

    import VueNumeric from 'vue-numeric'
    import Editor from '@tinymce/tinymce-vue';
    export default {
        components: {
            VueNumeric,
            'editor': Editor 
        },
        name: 'HoldingConditions',
        props: {
            editing: Boolean,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                imagesDir: imagesDir,
                anexos_content: '',
                nro_os: 0,
                nro_om: 0,
                help_oferta_semanal: '',
                help_oferta_mensual: '',
                renta_base: 0,
                hr_hl: 9,
                nm: 1,
                date: new Date(),
                available_date: '',
                maxDate: new Date(moment().format('YYYY-MM-DD'))
            }
        },
        computed: {
            campo_oferta_semanal: function(){
                //this.info.week_sale = this.nro_os;
                return this.nro_os+' %';
            },
            campo_oferta_mensual: function(){
                return this.nro_om+' %';
            },
            campo_hora_llegada: function(){
                if( this.hr_hl <= 12 ){
                    return this.hr_hl+' AM';
                }else{
                    return (this.hr_hl-12)+' PM';
                }
            },
            campo_noches_minimas: function(){
                return this.nm;
            },
            typeProperty(){
                if(this.info.property_type_id == 1 || this.info.property_type_id == 2 || this.info.property_type_id == 3){
                    return 0;
                }
                if(this.info.property_type_id == 4 || this.info.property_type_id == 5){
                    return 1;
                }
            },
        },
        methods: {
            poesia: function(sumando){
                if( (this.nro_os + sumando) <= 100 && (this.nro_os + sumando) >= 0 )
                this.nro_os+= sumando;
                this.info.week_sale = this.nro_os;
            },
            poesiam: function(sumando){
                if( (this.nro_om + sumando) <= 100 && (this.nro_om + sumando) >= 0 )
                this.nro_om+= sumando;
                this.info.month_sale = this.nro_om;
            },
            poesiahl: function(sumando){
                console.log('poesiahl');
                if( (this.hr_hl+sumando) >= 0 && (this.hr_hl+sumando) <= 23 )
                this.hr_hl+= sumando;
                this.info.checkin_hour = this.hr_hl;
            },
            poesianm: function(sumando){
                if( (this.nm+sumando) >= 1 && (this.nm+sumando) <= 30 )
                this.nm+= sumando;
                this.info.minimum_nights = this.nm;
            },
            onlyNumber ($event) {
                //console.log($event.keyCode); //keyCodes value
                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 48 || keyCode > 57) ) { // 46 is dot
                    $event.preventDefault();
                }
            },
            alterAvailableDate(){
                if(this.available_date != ''){
                    this.info.available_date = moment(this.available_date).format('YYYY-MM-DD');
                }
            },
            available_date(){
                const [year, month, day] = this.info.available_date.split('-')
                var month_minus = month - 1; 
                this.available_date = new Date(`${year},${month},${day}`)
            }
        },
        watch: {
            campo_oferta_semanal: function(){
                let precio_arriendo_base = parseInt(this.renta_base);
                this.help_oferta_semanal = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 7 dias) - '+this.nro_os+'% = '+((precio_arriendo_base*7)*((100-this.nro_os))/100).toLocaleString('de-DE')+' CLP';
            },
            campo_oferta_mensual: function(){
                let precio_arriendo_base = parseInt(this.renta_base);
                this.help_oferta_mensual = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 30 dias) - '+this.nro_om+'% = '+((precio_arriendo_base*30)*((100-this.nro_om))/100).toLocaleString('de-DE')+' CLP';
            },
            renta_base: function(){
                let precio_arriendo_base = parseInt(this.renta_base);
                this.help_oferta_semanal = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 7 dias) - '+this.nro_os+'% = '+((precio_arriendo_base*7)*((100-this.nro_os))/100).toLocaleString('de-DE')+' CLP';
                this.help_oferta_mensual = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 30 dias) - '+this.nro_om+'% = '+((precio_arriendo_base*30)*((100-this.nro_om))/100).toLocaleString('de-DE')+' CLP';
            }
        },
        mounted: function(){
            this.nro_os = parseInt(this.info.week_sale);
            this.nro_om = parseInt(this.info.month_sale);
            this.hr_hl = parseInt(this.info.checkin_hour);
            this.nm = parseInt(this.info.minimum_nights);
            this.renta_base = parseInt(this.info.rent);
            this.available_date();
        },
    }
</script>
