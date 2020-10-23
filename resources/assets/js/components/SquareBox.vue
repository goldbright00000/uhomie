<template>
    <label class="checkbox square" :class="{active:checked, disabled:!editing}" :disabled="!editing" >
        <input type="checkbox" :name="item.text" class="" :checked="checked" :value="item.id" @click=" $emit('click',{item:item.id, checked: !checked})" :disabled="!editing" />
        {{ item.text }} <span v-if="checked" class="fa fa-minus"></span> <span v-if="!checked" class="fa fa-plus"></span>
    </label>
</template>


<script>

    export default {
        name: 'SquareBox',
        props: {
            editing : Boolean,
            checked : Boolean,
            item: Object,


        },
        data: function () {
            return {

            }
        },
        methods: {}
    }
</script>
