<template>
    <div class="agent-contracts">
        <panel-up-down title="Verificaciones" :open="true" :save="false">
            <template slot-scope="data">
                <config-verifications v-bind:info="info" :verification2f="verification2f" :editing="data.editing"></config-verifications>
            </template>
        </panel-up-down>

        <!--<panel-up-down title="Tu Scoring Uhomie" :save="false">
            <div class="property-scoring">
                <vue-slider v-model="scoringSlider.value" v-bind="scoringSlider"></vue-slider>
            </div>
        </panel-up-down>-->

        <panel-up-down title="Notificaciones Autorizadas & Compartir datos" watching="notifications" @save_event="save_event($event)">
            <template slot-scope="data">
                <config-notifications :info="info" :editing="data.editing"></config-notifications>
            </template>
        </panel-up-down>

        <panel-up-down title="Datos y Privacidad" watching="privacies" @save_event="save_event($event)">
            <template slot-scope="data">
                <config-privacity :info="info" :editing="data.editing"></config-privacity>
            </template>

        </panel-up-down>

        <panel-up-down title="Modificaciones de la cuenta" :verification2f="verification2f" map_zone="config" @edit_event="openVerify = true" @save_event="save_event($event)">
            <template slot-scope="data">
                <config-account :editing="data.editing" v-bind:info="info"></config-account>
            </template>
        </panel-up-down>

        <panel-up-down title="Cambiar Contraseña" :verification2f="verification2f" @edit_event="openVerify = true" map_zone="password" @save_event="save_event($event)">
            <template slot-scope="data">
                <config-password :editing="data.editing" v-bind:info="info"></config-password>
            </template>
        </panel-up-down>

        <verificationTwoF :open="openVerify" @back="openVerify = false" @verify="verification2f = 0; openVerify = false"></verificationTwoF>

    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    import ConfigVerifications from '../common/ConfigVerifications';
    import PanelUpDown from '../../PanelUpDown';
    import ConfigNotifications from '../common/ConfigNotifications';
    import ConfigPrivacity from '../common/ConfigPrivacity';
    import ConfigAccount from '../common/ConfigAccount';
    import ConfigPassword from '../common/ConfigPassword';

    import vueSlider from "vue-slider-component";
    import StarRating from "vue-star-rating";

    import datasheet from '../../../profiles/datasheet';
    import VerificationTwoF from '../common/VerificationTwoF';

    export default {

        extends : datasheet,

        components: {
            ConfigVerifications,
            PanelUpDown,
            vueSlider,
            StarRating,
            ConfigNotifications,
            ConfigPrivacity,
            ConfigAccount,
            VerificationTwoF,
            ConfigPassword
        },
        name: 'AgentConfig',
        computed: {
            info() {
                return this.$parent.info;
            },
            filters() {
                return this.$parent.filters;
            },
        },
        data() {
            return {
                saveUrl: 'agent/save-data',
                openVerify: false,
                verification2f: 1,
                mapping: {
                    config: ['phone','email','phone_code','mail_verified','phone_verified'],
                    password: ['password', 'confir_password']
                },
                scoringSlider: {
                    value: 0,
                    min: 0,
                    max: 1000,
                    height: 5,
                    tooltip: "always",
                    piecewise: true,
                    interval: 250,
                    bgStyle: {
                        background:
                            "linear-gradient(90deg, rgba(241,62,13,1) 0%, rgba(232,245,11,1) 50%, rgba(30,181,6,1) 100%)"
                    },
                    processStyle: {
                        background: "transparent"
                    },
                    piecewiseStyle: {
                        visibility: "visible",
                        width: "12px",
                        height: "12px"
                    },
                    sliderStyle: {
                        background: "transparent",
                        boxShadow: "none"
                    }
                },

                imagesDir: imagesDir,

            }
        }
    }
</script>