<template>
    <div class="collateral-membership">
        <div class="columns">
            <div class="column is-12">
                <div class="membership-info">
                    <div class="column line-down">
                        <span>Mi membres&iacute;a</span>
                    </div>
                    <br>
                    <div>
                        <img :src="imagesDir+'/logo_'+info.name+'.png'">
                        <a href="" class="button is-outlined is-primary">Renovar</a>                        
                        
                        <a href="#" class="button is-outlined is-primary">Cambiar</a>
                    </div>
                    <div>
                        Vencimiento: 10/10/2020
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <table class="plans-table">
                    <tr>
                        <td class="td-title">Su plan incluye</td>
                        <td class="price">
                            <span>$ PRICE <span>+ IVA</span></span>
                            <span>(CLP)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Postulaciones disponibles</td>
                        <td class="value">                            
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Scoring UHOMIE</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Propiedades a visualizar</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Comisión x Arriendo</td>
                        <td class="value">                            
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ propietarios</td>
                        <td class="value">
                            {{ info.ownercontact }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de membresía</td>
                        <td class="value">30</td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Sugerencias a Potenciales propietarios</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_cruz_'+info.name+'.png'">
                        </td>
                    </tr>
                </table>            
            </div>
            <div class="column side-payment is-5 is-offset-1">
                <h2>Tu cupón</h2>
                <span>Si tienes un cupón de descuento <br>puedes canjearlo aquí.</span>
                <div class="coupon">
                    <input class="input" type="text" name="coupon" disabled>
                    <span><img :src="imagesDir+'/icono-tilde-azul.png'"> Cupón aplicado correctamente</span>
                </div>
                <h3 class="total-price before">$ 14.750 <span>+ IVA</span></h3>
                <h3 class="total-price">$ 12.290 <span>+ IVA</span></h3>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="payment-method" disabled>
                        <img :src="imagesDir+'/icono-visa.png'">
                        <img :src="imagesDir+'/icono-mastercard.png'">
                        <img :src="imagesDir+'/icono-americanexpress.png'">
                    </label>
                    <label class="radio">
                        <input type="radio" name="payment-method" checked>
                        <img :src="imagesDir+'/icono-paypal.png'">
                    </label>
                </div>
                <hr>
                <div id="paypal-button"></div>
            </div>
        </div>
    </div>
</template>



<script>
    const imagesDir = document.getElementById('images-dir').value;
    import CollateralCore from './CollateralCore';

    export default {
        extends: CollateralCore,
        components: {
            

        },
        name: 'CollateralMembership',
        props: {

        },
        data() {
            return {
                imagesDir: imagesDir
            }
        }
    }
</script>