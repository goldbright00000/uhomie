<template>
    <div class="media profile-info owner-postulations">
        <div class="image media-left" style="width: 80px;">
            <avatar :source="info.photo" />
        </div>
        <div :class="info.role!=5 ? 'profile-data column is-12' : 'profile-data column is-12'">
            <div class="user-name">{{ info.firstname }} {{ info.lastname }}</div>
            <div>{{ info.utype }} <span v-if="!verify_profile" class="tag is-link is-warning"><i style="color: white; margin-right: 5px;" class="fa fa-times"></i>Identidad No Verificada</span><span v-if="verify_profile" class="tag is-link is-success"><i style="color: white; margin-right: 5px;" class="fa fa-check"></i>Identidad Verificada</span></div>
        </div>

        <!-- <div class="column holding-edit">
            <router-link :to="'/'">
                <div class="border-bottom save_ico">
                    <img :src="imagesDir+'/box-edit.png'" style="width:32px; height:32px; margin-top: 20px" @click="$emit('edit',info.id)">
                </div>
            </router-link>
        </div> -->

        <div v-if="showdatails" class="media" style="margin-left: 20px; border: 0px;">
            <div class="media-left">
                <img :src="imagesDir+'/logo_'+info.membership+'.png'">
            </div>
            <div class="media-content scoring" style="margin-top: -36px">
                <div style="text-align: center">
                    <img :src="imagesDir+'/postul-scoring.png'" />
                    <div :class="'mem-'+info.membership">
                        <p>{{ info.scoring}}</p>
                        <img :src="imagesDir+'/posit.png'"/>
                    </div>
                </div>
            </div>
            <div class="media-right">
                <div style="font-size: 12px;  padding: 6px 25px 0px;">Recomendación<br>uHomie para arrendar</div>
            </div>
            <div style="margin-left: 20px">
                <a>SI</a>
            </div>
        </div>
    </div>
</template>

<script>
    import Avatar from './Avatar';
    import HoldingIcons from '../common/HoldingIcons';
    const imagesDir = document.getElementById('images-dir').value;

    export default {
        name: 'ProfileInfo',
        components: {
            Avatar
        },
        computed: {
            avatar() {
                return this.info.avatar ? this.info.avatar : '/images/husky.png';
            },
            info() {
                return this.$parent.info;
            },
            verify_profile() {
                if(this.info.documents.id_front.verified == 1 && this.info.documents.id_front.verified_ocr == 1){
                    if(true){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        },
        props: {
            showdatails: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {
                imagesDir: imagesDir,

            }
        }
    }
</script>

<style>
    .profile-data {
        font-weight: 500;
    }
</style>
