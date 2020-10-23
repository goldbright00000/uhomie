<template>
    <div class="order-payment">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-half line-down">
                <span>Orden de Pago</span>
            </div>
            <div class="column is-half has-text-right">
                <a class="button is-outlined is-primary" @click="$router.push('/payments')">
                    <span class="icon">
                        &lt;
                    </span>
                    <span>Volver</span>
                </a>
            </div>
        </div>
        <div class="columns is-inline is-italic">
            <div class="column is-8">
                <div class="field">
                    <label class="label">Estatus de Compra:</label>
                    <div class="control">
                        <span class="with-line">{{statusOrder(info.status)}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Detalle:</label>
                    <div class="control">
                        <span class="with-line">{{info.details}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Numero de Orden:</label>
                    <div class="control">
                        <span class="with-line">{{info.order}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Metodo de Pago:</label>
                    <div class="control">
                        <span class="with-line">{{info.method}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Monto:</label>
                    <div class="control">
                        <span class="with-line">$ {{money(info.amount)}} {{info.currency}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Monto mas iva {{money(info.iva)}}%:</label>
                    <div class="control">
                        <span class="with-line">$ {{money(info.total)}}  {{info.currency}}</span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Fecha de Pago:</label>
                    <div class="control">
                        <span class="with-line">{{moment(info.updated_at).locale('es').format('LLL') }}</span>
                    </div>
                </div>
                <div class="field">
                    
                </div>

                <div class="columns">
                    <div class="colomn is-half">
                        <label class="label">Token:</label>
                            <span class="with-line">{{info.token_ws}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'OrderPayment',
    computed:{
        info(){
            var w = this.$route.params.idOrder;
            var nfo = this.$parent.info.payments;
            for(var t in nfo){
                if (nfo[t].order==w) {
                    return nfo[t]
                }
            }
            return ;
        }
    },
    data() {
        return {
        }
    },
    props: {
        prop: Object,
    },
    mounted() {
    },
    methods: {
        statusOrder: function(e) {
            switch (e) {
                    case '0':
                        return 'Transacción Creada mas no Aprobada'
                        break;
                    case '1':
                        return 'Transacción Aprobada'                       
                        break;
                    case '2':
                        return 'Transacción Rechazada'                       
                        break;
                    case '3':
                        return 'Transacción Reembolzada'                       
                        break;
                    case '4':
                        return 'Transacción Cancelada'                       
                        break;
                }
        },
        money: function(value) {
            return window.globals.filters.moneyFormat(value, 0);
        }
    }
}
</script>
