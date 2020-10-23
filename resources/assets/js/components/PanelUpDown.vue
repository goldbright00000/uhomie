<template>

        <div class="columns is-inline" style="margin:0">
            <div class="column" >
                <div class="colums is-flex">
                    <div class="column is-9 line-down" @click="putupordown">
                        <span :class="show?'up':'down'">{{ title }}</span>
                    </div>
                    <div class="column is-3 save_ico" style="text-align: right;">
                        <div v-if="show">
                        <img v-if="(editing && verification2f == 0 || verification2f == 2) && map_zone != 'documents'" :src="imagesDir+'/icono_guardar.png'" @click="saveEmit"/>
                        <img v-if="editing" :src="imagesDir+'/icono_cruz_basic.png'" @click="$emit('cancel_edit');editing=false"/>
                        <img v-if="save && !editing" :src="imagesDir+'/box-edit.png'" @click="editEmit()"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-full" :class="{'is-hidden':!show}">
                <slot :editing="editing"></slot>
            </div>
        </div>

</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    export default {
        name: 'PanelUpDown',
        props: {
            watching :{
                default: '',
                type: String,
            },
            map_zone: String,
            title : String,
            open: {
                default: false,
                type: Boolean,
            },
            blocked_save:  {
                default: false,
                type: Boolean,
            },
            save: {
                default: true,
                type: Boolean,
            },
            verification2f: {
                default: 0,
                type: Number
            }

        },
        data : function(){

            return {
                editing : false,
                imagesDir : imagesDir,
                show : this.open,
            }
        },
        watch: {
            verification2f: function(newVal, oldVal) {
                if(newVal == 2 || newVal == 0) {
                    this.editing = true;
                }
            }
        },
        methods : {
            putupordown: function () {
                this.show = !this.show;
            },
            saveEmit: function () {
                console.log('se guardo');
                if (this.blocked_save) return
                console.log('no estaba blocked_save');
                this.$emit('save_event', [this.map_zone, this.watching])
                this.editing = false 
            },
            editEmit: function () {
                if (this.verification2f == 0) this.editing = true
                if (this.verification2f == 1) this.$emit('edit_event');
                if (this.verification2f == 2) this.editing = true;
            }
        }

    }
</script>
