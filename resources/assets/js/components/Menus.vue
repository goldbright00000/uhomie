<template>
    <div class="menus-app">
        <div class="menu-top">
            <div class="menu-title"> {{ menus.menu_title }}</div>
            <div class="short-bar"></div>
        </div>

        <ul class="menu-list">
            <li v-for="item in menus.items" :key="item.route" :class="{ 'selected': item.route === path }">
                <router-link :to="item.route" style="font-size: 0.85rem;">
                    <img v-bind:src="item.ico"/> {{ item.title }}
                </router-link >
            </li>

        </ul>

    </div>

</template>

<script>


    export default {
        name: 'Menus',
        props: {
            menus: Object,

        },
        computed: {
            path() {
                return this.$route.path;
            }
        },
        data : function(){
            return {
                menuIndex: 0,
            }
        },
        methods: {
            clickOnMenu : function (w) {
                this.menuIndex = w;
                this.$emit('setmenuindex',w);
            }
        }
    }
</script>
