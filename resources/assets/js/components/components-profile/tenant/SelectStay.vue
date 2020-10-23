<template>
    <div class="columns is-centered is-vcentered is-mobile" >
        <div class="column is-12 has-text-centered">
            <div class="tile is-child has-text-centered text-logo">
                <img src="/images/estadia/casa-icon.png">
                <p style="font-size: 1.2rem;font-weight: 500;font-style: italic;">{{titulo}}</p>
                <div class="div-vacio"></div>
                <div class="columns is-mobile is-multiline is-centered" style="padding-top: 25px;">
                    <div class="column is-narrow">
                        <img @click="stay='short'" @mouseover="mouse_vacaciones=true" @mouseleave="mouse_vacaciones=false" :src="'/images/estadia/vacaciones-'+modo_imagen_vacaciones+'.png'" style="margin-left: 40px; margin-right: 40px;">
                        <div class="columns is-mobile is-multiline is-centered" >
                            <div class="column is-12">
                                <div v-if="modo_imagen_vacaciones == 'active'">
                                    <p style="font-size: 0.7rem;font-weight: 600;font-style: italic;"> < 3 meses</p>
                                    <p style="font-size: 0.7rem;font-weight: 500;font-style: italic;">Vacaciones / Corta Temporada.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-narrow" >
                        <img @click="stay='long'" @mouseover="mouse_contrato=true" @mouseleave="mouse_contrato=false" :src="'/images/estadia/contrato-'+modo_imagen_contrato+'.png'" style="margin-left: 40px; margin-right: 40px;">
                        <div class="columns is-mobile is-multiline is-centered" >
                            <div class="column is-12">
                                <div v-if="modo_imagen_contrato == 'active'">
                                    <p style="font-size: 0.7rem;font-weight: 600;font-style: italic;"> > 3 meses</p>
                                    <p style="font-size: 0.7rem;font-weight: 500;font-style: italic;">Larga Temporada</p>
                                    <p style="font-size: 0.7rem;font-weight: 500;font-style: italic;">Requiere contrato de arriendo,</p>
                                    <p style="font-size: 0.7rem;font-weight: 500;font-style: italic;">meses de garantía y meses de adelanto. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form :action="ruta" method="POST">
                <button class="button is-primary"  type="submit" v-if="stay">Continuar</button>
                <input type="hidden" name="estadia" v-model="stay">
                <input type="hidden" name="_token" v-model="token">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            titulo: String,
            rol: String,
            siguiente_paso: {
                type: String,
                default: 'registro'
            }
        },
        components: {
            
        },
        name: 'SelectStay',
        methods: {
            cambiarIcono: function(){
                console.log('oao');
            }
        },
        data(){
            return {
                mouse_contrato: false,
                mouse_vacaciones: false,
                click_contrato: false,
                click_vacaciones: false,
                stay: null,
                token: null,
                ruta: String
            }
        },
        computed: {
            modo_imagen_vacaciones: function(){
                if(this.stay == 'short'){
                    return 'active';
                } else if(this.mouse_vacaciones){
                    return 'active';
                } else{
                    return 'static';
                }
            },
            modo_imagen_contrato: function(){
                if(this.stay == 'long'){
                    return 'active';
                } else if(this.mouse_contrato){
                    return 'active';
                } else{
                    return 'static';
                }
            }
        },
        mounted: function()
        {
            this.token = $(document.head.querySelector('meta[name="csrf-token"]')).attr('content');
            console.log(this.titulo);
            if( this.siguiente_paso == 'registro' ){
                if( this.rol == 'tenant' ){
                    this.ruta = "/users/tenant/registration/first-step/select";
                }
                if( this.rol == 'owner'){
                    this.ruta = "/users/owner/registration/second-step/select";
                }
            } else{
                this.ruta = this.siguiente_paso;
            }
            
            console.log('ruta: ' + this.ruta);
        }
    }
</script>