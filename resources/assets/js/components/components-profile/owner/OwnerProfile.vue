<template>
    <div class="owner-profile">
        <div class="columns">
            <div class="column is-9">

                <profile-info :info="info"></profile-info>
                <profile-info2 :filters="filters" tipo="owner" :info="info"></profile-info2>
            </div>
            <div class="column">
                <div style="border: 1px solid #000; height: 100%;">
                    <your-mem v-bind="info"></your-mem>
                </div>
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
    import OwnerCore from './OwnerCore';
    import YourMem from '../common/YourMem';

    export default {
        extends: OwnerCore,
        components: {
            ProfileInfo,
            ProfileInfo2,
            ProfileDocuments,
            YourMem,
        },
        name: 'OwnerProfile',
        data() {
            
            return {
            }
        },
    }
</script>
