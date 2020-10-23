<template>
    <div class="squere-list-app columns">
        <div class="column" style="padding: 20px">
            <square-box :editing="editing" v-for="item in options" @click="sel_box($event)" :key="item.id" :item="item" :checked="list.indexOf(item.id)!=-1">

            </square-box>
        </div>

    </div>

</template>


<script>

    import SquareBox from './SquareBox'

    export default {
        name: 'SquareList',
        components : {
            SquareBox,
        },
        computed: {
            options() {
                if (!this.items) {
                    return [];
                }
                if(this.type == '1' || this.type == '2' || this.type == '3'){
                    return this.items.options.filter(element => element.type == 0);
                }
                if(this.type == '4' || this.type == '5'){
                    return this.items.options.filter(element => element.type == 1);
                }
                if(this.type == '0'){
                    return this.items.options;
                }
                
            },
            list() {
                return this.values
            }
        },
        props: {
            editing: Boolean,
            items: Object,
            type: {
                type: String,
                default: '0'},
            values: {
                type: Array,
                default: function () {
                    return []
                }
            }
        },
        data: function () {
            return {}
        },
        methods: {
            sel_box(j) {
                var i = this.list.indexOf(j.item);
                if (j.checked){
                    if (i == -1)
                        this.values.push(j.item);
                } else {
                    if (i > -1)
                        this.values.splice(i, 1);
                }

                this.$emit('change',this.values);
            },
        }
    }
</script>
