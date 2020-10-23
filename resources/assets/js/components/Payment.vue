<template>
    <tr class="payment">
        <td>{{info.order}}</td>
        <td class="has-text-centered"><p :class="statusStyle(info.status)"><b>{{status(info.status)}}</b></p></td>
        <td>{{info.details}}</td>
        <td>$ {{money(info.amount)}} {{info.currency}}</td>
        <td>$ {{money(info.total)}} {{info.currency}}</td>
        <td>{{moment(info.updated_at).locale('es').format('LLL') }}</td>
        <td>
            <span class="tooltip" data-tooltip="Orden de Pago">
                <img @click="$router.push('/order/' + info.order)" :src="imagesDir + '/doc.png'" style="cursor:pointer"/>
            </span>
        </td>
    </tr>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;
export default {
    name:'Payment',
    props:{
        info: Object
    },
    data() {
        return {
            imagesDir: imagesDir
        }
    },
    methods:{
        money: function(value) {
            return window.globals.filters.moneyFormat(value, 0);
        },
        status: function(value) {
            switch(value){
                case '0':
                    return 'Creado'
                    break;
                case '1':
                    return 'Aprobado'
                    break;
                case '2':
                    return 'Rechazado'
                    break;
                case '3':
                    return 'Reembolsado'
                    break;
                case '4':
                    return 'Cancelado'
                    break;
                default:
                    return 'Default'
                    break;
            }
        },
        statusStyle: function(value) {
            switch(value){
                case '0':
                    return 'created'
                    break;
                case '1':
                    return 'approved'
                    break;
                case '2':
                    return 'rejected'
                    break;
                case '3':
                    return 'refunded'
                    break;
                case '4':
                    return 'canceled'
                    break;
                default:
                    return 'Default'
                    break;
            }
        }
    }
}
</script>

<style>
.created {
    border-bottom: 3px solid hsl(48, 100%, 67%);
}

.approved {
    border-bottom: 3px solid hsl(141, 71%, 48%);
}

.refunded {
    border-bottom: 3px solid hsl(204, 86%, 53%);
}

.rejected
{
    border-bottom: 3px solid hsl(348, 100%, 61%);
}
.canceled
{
    border-bottom: 3px solid hsl(348, 100%, 61%);
}
</style>