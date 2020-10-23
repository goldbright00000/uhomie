<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="column is-9">
            <div class="field" v-if="typeProperty == 1">
                <label class="label">Nombre de Torre u Edificio:</label>
                <div class="control">
                    <input class="input" type="text" required v-model="info.building_name" :disabled="!editing">
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Requiere Habilitación de local</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="room_enablement" value="1" :checked="info.room_enablement" v-model="info.room_enablement" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="room_enablement" value="0" :checked="!info.room_enablement" v-model="info.room_enablement" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Requiere Obra Civil</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="civil_work" value="1" :checked="info.civil_work" v-model="info.civil_work" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="civil_work" value="0" :checked="!info.civil_work" v-model="info.civil_work" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Elaboración de Proyecto Arquitectura</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="arquitecture_project" value="1" :checked="info.arquitecture_project" v-model="info.arquitecture_project" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="arquitecture_project" value="0" :checked="!info.arquitecture_project" v-model="info.arquitecture_project" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Elaboración de Plan Eléctrico / Red de Aguas</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="work_electric_water" value="1" :checked="info.work_electric_water" v-model="info.work_electric_water" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="work_electric_water" value="0" :checked="!info.work_electric_water" v-model="info.work_electric_water" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Nivel / Planta:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.level" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Cantidad de privados u oficinas que se pueden habilitar:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.bedrooms" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 1">
                <div class="label">
                    <span>Cantidad de salas de reuniones:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.meeting_room" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0">
                <div class="label">
                    <span>Habitaciones:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.bedrooms" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>


            <div class="field" v-if="typeProperty == 0 || typeProperty == 1">
                <div class="label">
                    <span>Baños:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.bathrooms" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field">
                <div class="label">
                    <span>Metros Cuadrados:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.meters" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="typeProperty == 2">
                <div class="label">
                    <span>Numero de terreno / parcela / lote:</span>
                </div>
                <div class="control">
                    <input class="input" type="text" required v-model="info.lot_number" :disabled="!editing">
                </div>
            </div>


            <div class="field" v-if="typeProperty == 0">
                <div class="label">
                    <span>Piscina</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="pool" value="1" :checked="info.pool" v-model="info.pool" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="pool" value="0" :checked="!info.pool" v-model="info.pool" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0">
                <div class="label">
                    <span>Jardín</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="garden" value="1" :checked="info.garden" v-model="info.garden" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="garden" value="0" :checked="!info.garden" v-model="info.garden" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field"  v-if="typeProperty == 0">
                <div class="label">
                    <span>Terraza, Zona Barbacoa</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="terrace" value="1" :checked="info.terrace" v-model="info.terrace" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="terrace" value="0" :checked="!info.terrace" v-model="info.terrace" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0 || typeProperty == 1 || typeProperty == 4">
                <div class="label">
                    <span>Puestos de Estacionamiento Privado:</span>
                </div>
                <div class="control">
                    <input class="input" type="number" required v-model="info.private_parking" style="max-width: 100px;" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0 || typeProperty == 1 || typeProperty == 4">
                <div class="label">
                    <span>Puestos de Estacionamiento Público:</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="public_parking" value="1" :checked="info.public_parking" v-model="info.public_parking" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="public_parking" value="0" :checked="!info.public_parking" v-model="info.public_parking" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0 || typeProperty == 1 || typeProperty == 3">
                <div class="label">
                    <span>Bodega:</span>
                </div>
                <div class="control">
                    <label class="radio">
                    <input type="radio" name="cellar" value="1" :checked="info.cellar" v-model="info.cellar" :disabled="!editing">
                    Si
                    </label>
                    <label class="radio">
                    <input type="radio" name="cellar" value="0" :checked="!info.cellar" v-model="info.cellar" :disabled="!editing">
                    No
                    </label>
                </div>
            </div>
            <div class="field" v-if="info.cellar == '1' && (typeProperty == 0 || typeProperty == 1 || typeProperty == 3)">
                <label class="label">Numero de Bodega:</label>
                <div class="control">
                    <input class="input" type="text" required v-model="info.cellar_description" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="info.type_stay == 'SHORT_STAY'">
                <label class="label">Cantidad de camas invididuales:</label>
                <div class="control">
                    <input class="input" type="text" required v-model="info.single_beds" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="info.type_stay == 'SHORT_STAY'">
                <label class="label">Cantidad de camas matrimoniales:</label>
                <div class="control">
                    <input class="input" type="text" required v-model="info.double_beds" :disabled="!editing">
                </div>
            </div>

            <div class="field" v-if="typeProperty == 0 || typeProperty == 1">
                <div class="label">
                    <span>Exclusiones que no incluye el alquiler:</span>
                </div>
                <div class="control">
                    <textarea rows="6" style="width: 100%;padding: 6px;" v-model="info.exclusions" :disabled="!editing"></textarea>
                </div>
            </div>
        </div>
        


    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'HoldingDetails',
        props: {
            editing: Boolean,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                imagesDir : imagesDir
            }
        },
        computed:{
            typeProperty(){
                if(this.info.property_type_id == 1 || this.info.property_type_id == 2 || this.info.property_type_id == 3){
                    return 0;
                }
                if(this.info.property_type_id == 4 || this.info.property_type_id == 5){
                    return 1;
                }
                if(this.info.property_type_id == 6){
                    return 2;
                }
                if(this.info.property_type_id == 7){
                    return 3;
                }
                if(this.info.property_type_id == 8){
                    return 4;
                }
            },
        }
    }
</script>
