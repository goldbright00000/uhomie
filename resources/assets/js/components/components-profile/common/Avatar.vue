<template>
    <a v-on:click="launcherChangeAvatar">
        <input type="file" ref="file" accept="image/*" @change="onFileChange($event.target.files[0])" style="display: none" />
        <div :class="['image' , 'avatar', sizeAvatar]">
            <img class="is-rounded" :src="avatar" v-show="!loading"  />
            <spinner v-show="loading" />
        </div>
    </a>
</template>

<script>
    import axios from "axios";
    import Spinner from './Spinner';
    const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    axios.defaults.headers.common = {
       'Authorization' : 'Bearer ' + sessionStorage.getItem('secret')
    };
    export default {

        name: 'Avatar',
        components: {
            Spinner
        },
        computed: {
            sizeAvatar() {
                switch(this.size) {
                    case 'sm':
                        return 'is-32x32';
                    case 'md':
                        return 'is-64x64';
                    case 'lg':
                        return 'is-128x128';
                    default:
                        return 'is-96x96';
                }
            },
            avatar() {
                if (this.avatarImage) {
                    return this.avatarImage;
                }
                return this.source ? this.source : '/images/husky.png';
            }
        },
        props: {
            source: String,
            size: String
        },  
        data() {
            return {
                loading: false,
                avatarImage: null
            }
        },
        methods: {
            launcherChangeAvatar(evt) {
                if (this.loading) {
                    return;
                }
                this.$refs.file.click();
            },
            onFileChange(file) {
                this.loading = true;
                const reader = new FileReader()
                reader.onload = (evt) => {
                    this.avatarImage = reader.result;
                    setTimeout(() => {
                        this.loading = false;
                    }, 500);
                };
                const form = new FormData();
                form.append('avatar', file);
                axios.post('/avatar', form, {
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    }}).then(() => {
                    });
                reader.readAsDataURL(file);
                
            }
        }

    }
</script>
<style lang="css">
    .avatar {
        overflow: hidden;
        border: 1px solid #eee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar img {
        height: 100%;
        padding: 5px;
    }
</style>
