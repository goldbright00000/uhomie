<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="column">
            <div class="field">
                <label class="label">Nombre:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Nombres" v-model="info.firstname" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Apellidos:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Apellidos" v-model="info.lastname" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Tel&eacute;fono:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Telefono" v-model="info.phone" :disabled="true">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Correo:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Correo" v-model="info.email" :disabled="true">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">¿Que nacionalidad tienes?</label>
                <div class="control">
                    <div class="select is-info.personal">
                        <select v-model="info.country_id" :disabled="!editing">
                            <option v-for="item in filters.countries.options" :value="item.id" :selected="item.id==info.country_id">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">¿Que tipo de RUT tienes?</label>
                <div class="control">
                    <div class="select is-info.personal">
                        <select v-model="info.document_type" :disabled="!editing">
                            <option v-for="item in filters.rut_type.options" :value="item.id" :selected="item.id==info.document_type">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">¿N&uacute;mero?</label>
                <div class="control">
                    <input class="input" type="text" placeholder="No. Rut" v-model="info.document_number" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Fecha de Nacimiento</label>
                <div class="control">
                    <input v-if="!editing" class="input" type="text" placeholder="Fecha de Nacimiento"
                           v-model="info.birthdate" :max="maxDate" :disabled="!editing">
                    <v-date-picker
                        v-if="editing"
                        v-model='birthdate'
                        :max-date="maxDate" 
                        :dayclick="alterBirthdayDate()"
                        :input-props='{
                            placeholder: "Seleccione una fecha",
                            readonly: true,
                            class: "input date",
                            style:"border-radius: 0; border-bottom: 1px solid #0a0a0a;"
                        }'
                    />
                    
                    
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Estado Civil</label>
                <div class="control">
                    <div class="select is-info.personal">
                        <select v-model="info.civil_status_id" :disabled="!editing">
                            <option v-for="item in filters.civilstatus.options" :value="item.id" :selected="item.id==info.civil_status_id">{{item.text}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'MyformPersonal',
        props: {
            editing:false,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                birthdate: '',
                maxDate: new Date(moment().subtract(18, 'years').format('YYYY-MM-DD'))
            }
        },
        methods:{
            alterBirthdayDate(){
                if(this.birthdate != ''){
                    this.info.birthdate = moment(this.birthdate).format('YYYY-MM-DD');
                }
            },
            birthdate_date(){
                const [year, month, day] = this.info.birthdate.split('-')
                var month_minus = month - 1; 
                this.birthdate = new Date(`${year},${month},${day}`)
            }
        },
        mounted(){
            this.birthdate_date()
        }

        
    }
</script>
