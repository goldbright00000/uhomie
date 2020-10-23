<template>
    <div class="columns" style="padding-bottom: 20px;">
        <div class="column">
            <!--<div v-for="file in info.files" :key="file.id">
                <h2 class="file-title">{{ getText(file.name) }}</h2>
                <div class="file has-name">
                    <label class="file-label">
                        <input class="file-input" type="file" :id="file.name" :name="file.name" max="1" accept="">
                        <span class="file-cta">
                            <span class="file-label">
                                Examinar...
                            </span>
                        </span>
                        <span class="file-name">
                            {{ file.path ? file.original_name : "No se seleccionó un archivo" }}
                        </span>
                    </label>
                    <span class="file-show">
                        <div v-if="file.path">
                            <img :src="imagesDir + '/icono-tilde-azul-g.png'">
                            <a target="_blank" :href="'/get-file/?path='+file.id">
                                <img :src="imagesDir + '/icono-descarga-g.png'">
                            </a>
                        </div>
                        <img v-if="!file.path" :src="imagesDir + '/icono-atencion.png'">
                    </span>
                </div>
            </div>-->
            <!--<div v-for="thisFile in info.files" :key="thisFile.id">
                <div class="columns">
                    <div class="column is-three-quarters">
                        <span>{{getText(thisFile.name)}}</span>
                    </div>
                    <div class="column has-text-right">
                        <div v-if="thisFile">
                            <input @change="uploadFile($event)" v-if="editing == true" class="file-input-profile" type="file" :id="thisFile.name" :name="thisFile.name" max="1" v-bind:accept="mines">
                            <label :for="thisFile.name" v-if="editing == true"><img :src="imagesDir+'/box-edit.png'"/></label>
                            <img :src="imagesDir+'/doc-check.png'" :class="!thisFile.path || thisFile.verified == '0' ? 'is-hidden': '' "/>
                            <a :href="'/get-file/?path='+thisFile.id"><img :src="imagesDir+'/doc-eye.png'" :class="!thisFile.path ? 'is-hidden' : ''" style="cursor: pointer"/></a>
                        </div>
                    </div>
                </div>
            </div>-->
            <doc-property-view :editing="editing" v-for="file in info.files" :key="file.id" v-bind:file="file" @filedata="fileData"></doc-property-view>
        </div>
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    const _token = document.getElementById('_token').value;

    import DocPropertyView from '../common/DocPropertyView.vue';


    export default {
        name: 'HoldingDocuments',
        components: {
            DocPropertyView
        },
        props: {
            editing: Boolean,
            info: Object,
            filters: Object,
            mines: {
                type: String,
                default: 'application/pdf'
            }
        },
        data() {
            return {
                imagesDir: imagesDir,

            }
        },
        methods: {
            fileData(value){
                console.log(value)
                this.$emit('filedata', value);
            }
        }

    }
</script>
