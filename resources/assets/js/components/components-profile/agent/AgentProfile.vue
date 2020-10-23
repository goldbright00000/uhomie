<template>
    <div class="agent-profile">        
        <div class="columns">            
            <div class="column is-8">
                <profile-info editing="data.editing" :info="info"></profile-info>
                <profile-info2 tipo="agent" :filters="filters"  :info="info"></profile-info2>
            </div>
            <div class="column">
                <div style="border: 1px solid #000; height: 100%;">
                    <your-mem v-bind="info"></your-mem>
                </div>
            </div>            
        </div>                
        <div class="columns">
            <div class="column is-12">
                <profile-documents v-bind:info="info">
                </profile-documents>
            </div>
        </div>
        
    </div>
</template>

<script>    
    
    import ProfileInfo from '../common/ProfileInfo.vue';
    import ProfileInfo2 from '../common/ProfileInfo2.vue';
    import ProfileDocuments from '../common/ProfileDocuments.vue';
    
    
    import YourMem from '../common/YourMem';
    import AgentCore from './AgentCore';
    // import GetingData from '../common/GetingData';

    export default {
        extends : AgentCore,
        components: {
            ProfileInfo,
            ProfileInfo2,
            ProfileDocuments,
            YourMem, 
        },
        name: 'AgentProfile',
        data() {
            return {
            }
        }
    }
</script>
