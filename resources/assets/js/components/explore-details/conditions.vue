<template>
	<section id="condiciones" class="section">
    <div class="container">
			<h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
				Condiciones de arriendo
			</h3>
			
			<div class="rent-conditions">
        <p>Esta propiedad es apta para: <span> {{ lessorType }}</span></p>
        <p>Tipo de arrendatario requerido: <span> {{ leasingType }}</span></p>
        <p v-if="property.data.type_stay == 'LONG_STAY'">Aval: <span v-html="property.data.collateral_require == '1' ? 'Si' : 'No'"></span></p>
        <table>
          <tbody>
            <tr v-if="property.data.type_stay == 'LONG_STAY'">
              <td>Meses de garantia:</td>
              <td v-html="number(property.data.warranty_months_quantity) + ' meses'"></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY'">
              <td>Meses de adelanto:</td>
              <td v-html="number(property.data.months_advance_quantity) + ' meses'"></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY' == typeProperty != 1">
              <td>Tiempo minimo de arriendo:</td>
              <td v-html="number(property.data.tenanting_months_quantity) + ' meses'"></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY' == typeProperty == 1">
              <td>Plazo minimo de arriendo (años):</td>
              <td v-html="number(property.data.term_year)"></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY'">
              <td>Precio mensual del arrendamiento:</td>
              <td v-html="'$ ' + moneyFormat(property.data.rent) +  ' CLP' "></td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Precio arriendo por día:</td>
              <td v-html="'$ ' + moneyFormat(property.data.rent) +  ' CLP' "></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY' && typeProperty == 1 && property.data.rent_year_1 != 0">
              <td>Precio año 1:</td>
              <td v-html="'$ ' + moneyFormat(property.data.rent_year_1) +  ' UF' "></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY' && typeProperty == 1 && property.data.rent_year_2 != 0">
              <td>Precio año 2:</td>
              <td v-html="'$ ' + moneyFormat(property.data.rent_year_2) +  ' UF' "></td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY' && typeProperty == 1 && property.data.rent_year_3 != 0">
              <td>Precio año 3:</td>
              <td v-html="'$ ' + moneyFormat(property.data.rent_year_3) +  ' UF' "></td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY' && property.data.cleaning_rate != 0">
              <td>Tarifa por Limpieza:</td>
              <td v-html="'$ ' + moneyFormat(property.data.cleaning_rate) +  ' CLP' "></td>
            </tr>
            <tr v-if="property.data.common_expenses && property.data.type_stay == 'LONG_STAY'">
              <td>Gastos comunes:</td>
              <td v-html="'$ ' + moneyFormat(property.data.common_expenses_limit) +  ' CLP' "></td>
            </tr>
            <tr>
              <td>Condición:</td>
              <td>
                <div class="condition" v-if="propertyCondition.name">
                  <!--img :src="propertyCondition.image" :title="propertyCondition.name"-->
                  {{ propertyCondition.name }}
                </div>
              </td>
            </tr>
            <tr>
              <td>Estacionamiento Público:</td>
              <td>
                <span>
                  {{ propertyPublicParking }}
                </span>
              </td>
            </tr>
            <tr>
              <td>Amoblada:</td>
              <td>
                <span v-if="propertyFurnished.status_text">
                  {{ propertyFurnished.status_text }}
                </span>
              </td>
            </tr>
            <tr v-if="propertyFurnished.status">
              <td>Muebles:</td>
              <td>
                {{ propertyFurnished.description }}
              </td>
            </tr>
            <tr>
              <td>Bodega:</td>
              <td>
                <span v-if="propertyCellar.status_text">
                  {{ propertyCellar.status_text }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'LONG_STAY'">
              <td>Seguro de Arrendatario:</td>
              <td>
                <span v-if="propertyInsurance.status_text">
                  {{ propertyInsurance.status_text }}
                </span>
              </td>
            </tr>
            <!-- Campos Si/No de propiedades Corta estadía-->
            <!--<tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Noches mínimas de arriendo:</td>
              <td>
                <span >
                  @{{ property.data.minimum_nights }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Descuento especial para primeros 5 huéspedes:</td>
              <td>
                <span >
                  @{{ property.data.special_sale == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Descuento por arrendar 1 semana o más:</td>
              <td>
                <span >
                  @{{ property.data.week_sale+' %' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Descuento por arrendar 1 mes o más:</td>
              <td>
                <span >
                  @{{ property.data.month_sale+' %' }}
                </span>
              </td>
            </tr>
            
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Se permite fumar:</td>
              <td>
                <span >
                  @{{ property.data.smoking_allowed == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Adecuado para niños de 2 a 12 Años:</td>
              <td>
                <span >
                  @{{ property.data.allow_small_child == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Adecuado para bebés de hasta 2 años:</td>
              <td>
                <span >
                  @{{ property.data.allow_baby == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Se permiten fiestas o eventos:</td>
              <td>
                <span >
                  @{{ property.data.allow_parties == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Es necesario utilizar escleras:</td>
              <td>
                <span >
                  @{{ property.data.use_stairs == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Puede haber ruido:</td>
              <td>
                <span >
                  @{{ property.data.there_could_be_noise == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Hay zonas comunes que se comparten con otros huéspedes:</td>
              <td>
                <span >
                  @{{ property.data.common_zones == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Limitaciones de servicios:</td>
              <td>
                <span >
                  @{{ property.data.services_limited == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Dispositivos de vigilancia o de grabación en la vivienda:</td>
              <td>
                <span >
                  @{{ property.data.survellaince_camera == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Armas en la vivienda:</td>
              <td>
                <span >
                  @{{ property.data.weaponry == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Animales peligrosos en la vivienda:</td>
              <td>
                <span >
                  @{{ property.data.dangerous_animals == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>
            <tr v-if="property.data.type_stay == 'SHORT_STAY'">
              <td>Hay mascotas en la propiedad:</td>
              <td>
                <span >
                  @{{ property.data.pets_friendly == '1' ? 'Si' : 'No' }}
                </span>
              </td>
            </tr>-->
          </tbody>
        </table>
        <p v-if="property.data.type_stay == 'LONG_STAY'">Si cancelas el arriendo una vez firmado el contrato digital con el dueño, se aplicara una penalidad de 25% del valor de un mes</p>
      </div>
		</div>
	</section>
</template>

<script>

export default {
  props: ['property'],
  data: function () {
    return {
    	
    }
  },
  mounted() {
  	
  },
  methods: {
  	number: function(a) {
      let b = a * 1;
      return b === b && typeof b == "number" ? b : 0;
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
  },
  computed: {
  	typeProperty(){
      let vm = this;
      if(vm.property.data.property_type_id == 1 || vm.property.data.property_type_id == 2 || vm.property.data.property_type_id == 3){
        return 0;
      }
      if(vm.property.data.property_type_id == 4 || vm.property.data.property_type_id == 5){
        return 1;
      }
    },
  	propertyCondition: function() {
      let vm = this;
      let condition = {
        image: undefined,
        name: undefined
      };

      if (!vm.property.data) return condition;

      condition.name = vm.property.data.condition ? "Nueva" : "Usada";
      condition.image =
        vm.imgDir +
        (vm.property.data.condition
          ? "/explore-details/ico_condicion_si.png"
          : "/explore-details/ico_condicion_no.png");

      return condition;
    },
    leasingType: function() {
      let vm = this;
      let availables_leasing = [];

      if (typeof vm.property.data.id == "undefined") return "";

      if (vm.property.data.nationals_with_rut == "1")
        availables_leasing.push("Nacionales con RUT");
      if (vm.property.data.foreigners_with_rut == "1")
        availables_leasing.push("Extranjeros con RUT");
      if (vm.property.data.foreigners_with_passport == "1")
        availables_leasing.push("Extranjeros con Pasaporte");

      if (availables_leasing.length < 1)
        availables_leasing.push("No especificado");

      return availables_leasing.join(", ");
    },
    lessorType: function() {
      let vm = this;
      let lessor_type = "";

      if (vm.property.properties_for.length < 1) return "No especificado";

      return vm.property.properties_for
        .map(
          lessor =>
            lessor.name.charAt(0).toUpperCase() +
            lessor.name.toLowerCase().slice(1)
        )
        .join(", ");
    },
    propertyPublicParking: function() {
      let vm = this;

      if (!vm.property.data) return false;

      return vm.property.data.public_parking ? "Si" : "No";
    },
    propertyFurnished: function() {
      let vm = this;
      let furnished = {
        status: null,
        status_text: null,
        description: null
      };

      if (!vm.property.data) return furnished;

      furnished.status = vm.property.data.furnished > 0;
      furnished.status_text = furnished.status ? "Si" : "No";
      furnished.description = vm.property.data.furnished_description
        ? vm.property.data.furnished_description
        : "No especificado";

      return furnished;
    },
    propertyCellar: function() {
      let vm = this;
      let cellar = {
        status: null,
        status_text: null
      };

      if (!vm.property.data) return cellar;

      cellar.status = vm.property.data.cellar > 0;
      cellar.status_text = cellar.status ? "Si" : "No";

      return cellar;
    },
    propertyInsurance: function() {
      let vm = this;
      let insurance = {
        status: null,
        status_text: null
      };

      if (!vm.property.data) return insurance;

      insurance.status = vm.property.data.tenanting_insurance > 0;
      insurance.status_text = insurance.status ? "Si" : "No";

      return insurance;
    },
  }
}
</script>

<style scoped>
	.columns {
		margin-top: 1rem;
	}
	.container {
		max-width: 640px;
	}
</style>