<template>
    <div class="agent-buyers">
        <!-- FILTROS -->
        <div class="columns">
            <div class="column is-4">
                <div class="media">
                    <span class="media-left">
                        <input type="checkbox" />
                        <i>Ordenar Por:</i>
                    </span>
                    <div class="select media-content">
                        <select class="" name="">
                            <option value="Fecha de Visita">Fecha de Visita</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- HEADER -->
        <div class="columns">
            <div class="column is-12">
                <div class="columns" style="border-bottom: 1px solid #eee; border-top: 1px solid #eee; ">
                    <div class="column is-2">
                        <span>Usuario</span>
                    </div>
                    <div class="column">
                        <span>Proyecto</span>
                    </div>
                    <div class="column">
                        <span>Membres&iacute;a</span>
                    </div>
                    <div class="column is-2">
                        <img :src="imagesDir+'/postul-date.png'" />
                    </div>
                    <div class="column is-1">
                        <img :src="imagesDir+'/postul-contact.png'" />
                    </div>
                </div>

            </div>
        </div>

        <!-- DETALLES -->
        <div v-for="item in buyer" :key="item.id">
            <span></span>
        </div>
    </div>
</template>

<script>

</script>
