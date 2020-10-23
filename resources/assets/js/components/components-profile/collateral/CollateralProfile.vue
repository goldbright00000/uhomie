<template>
    <div class="collateral-profile">        
        <div class="columns">            
            <div :class="info.role == 5 ? 'column is-12' : 'column is-8'">
                <profile-info editing="data.editing" :info="info"></profile-info>
                <profile-info2 tipo="collateral" :filters="filters" :info="info"></profile-info2>
            </div>
        </div>                
        <div class="columns">
            <div class="column is-12">
                <profile-documents v-bind:info="info"></profile-documents>
            </div>
        </div>
        
    </div>
</template>

<script>    
    
    import ProfileInfo from '../common/ProfileInfo.vue';
    import ProfileInfo2 from '../common/ProfileInfo2.vue';
    import ProfileDocuments from '../common/ProfileDocuments.vue';
    
    import CollateralCore from './CollateralCore';
    import GetingData from '../common/GetingData';

    export default {
        extends : CollateralCore,
        components: {
            ProfileInfo,
            ProfileInfo2,
            ProfileDocuments,
        },
        name: 'CollateralProfile',
        data() {
            return {
            }
        }
    }
</script>
