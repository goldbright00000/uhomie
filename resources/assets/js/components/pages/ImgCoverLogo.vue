<template>
    <div>
        <div :class="info.space_id == null ? 'card warning-card' : 'card'">
            <header class="card-header">
                <p class="card-header-title">
                    Logo
                </p>
                <a class="card-header-icon" @click="delPhoto(info.id)" aria-label="more options" >
                    <span class="icon">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content" style="padding: 0">
                <div class="content">
                    <div class="has-text-centered">
                        <img :src="info.path">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

const _token = document.head.querySelector("[name=csrf-token]").content;

export default {
    name: 'ImgCoverLogo',
    props: {
        info: Object,
        editing: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        spaces(){
            return this.$parent.spaces;
        }
    },
    data() {
        return {
        }
    },
    methods: {
        delPhoto(id){
            this.$emit('delphotocover', {'id': id});
        },
    }
}


</script>

<style>
.warning-card {
    -webkit-box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
    -moz-box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
    box-shadow: 0px 0px 5px 0px rgba(255,0,0,1);
}
</style>