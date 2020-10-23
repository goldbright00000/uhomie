<template>
    <div class="holding-management">
        <div class="columns">
            <div class="column">
                <div style="margin-left: 20px;">
                    <ol>
                        <li value="1">Recaudación y liquidación de arriendo</li>
                        <li value="2">Control mensual de pagos de cuentas de servicios y gastos comunes</li>
                        <li value="3">Gestionar Mantención de preventiva de propiedades</li>
                        <li value="4">Reparaciones de propiedad</li>
                        <li value="5">Asesoria y cobranza judicial</li>
                        <li value="6">Visitas periodicas para revisar el estado del inmueble</li>
                        <li value="7">Recepcion de la propiedad</li>
                        <li value="8">Liquidación de la garantia</li>
                        <li value="9">Finiquito de arriendo</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <article class="message is-success">
                    <div class="message-body">
                        <strong>10%</strong> del canon mensual del arriendo.
                    </div>
                </article> 
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="label">
                    <span>¿Deseas que tus propiedades las gestione Uhomie?</span>
                </div>
                <div class="control">
                    <input type="hidden" v-model="info.id">
                    <label class="radio">
                        <input type="radio" v-model="info.manage" name="pool" value="1" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" v-model="info.manage" name="pool" value="0" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'HoldingManagement',
    props: {
        info: Object,
        editing: Boolean,
    },
    data() {
        return {
            
        }
    },
}
</script>
