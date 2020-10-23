<template>
    <div class="tenant-profile">
        <div class="columns">
            <div class="column is-8">

                <profile-info editing="editing" :info="info"></profile-info>
                <profile-info2 tipo="tenant" :filters="filters"  :info="info"></profile-info2>
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
        <!--<div class="columns is-vcentered">
            <div class="column is-7">
                <scoring-list v-bind:info="info"></scoring-list>
            </div>
            
            <div class="column has-text-centered is-size-7">
                Completa todos los requerimientos de tu perfil para que tu puntaje de scoring aumente y encuentres mejores oportunidades de arriendo.
            </div>
        </div>-->
        <div class="columns">
            <div class="column is-12 line-down">
                <span>Tu Puntaje Scoring</span>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <table-scoring v-bind:info="info"></table-scoring>
            </div>
        </div>
    </div>
</template>

<script>

    import ProfileInfo from '../common/ProfileInfo.vue';
    import ProfileInfo2 from '../common/ProfileInfo2.vue';
    import ProfileDocuments from '../common/ProfileDocuments.vue';
    import YourMem from '../common/YourMem';
    import ScoringList from '../common/ScoringList.vue';
    import TableScoring from '../common/TableScoring.vue';
    import TenantCore from './TenantCore';
    //import GetingData from '../common/GetingData';
    
    export default {

        extends : TenantCore,
        components: {
            ProfileInfo,
            ProfileInfo2,
            ProfileDocuments,
            YourMem,
            ScoringList,
            TableScoring
        },
        name: 'TenantProfile',
        data() {
            return {
            }
        }
    }
</script>
