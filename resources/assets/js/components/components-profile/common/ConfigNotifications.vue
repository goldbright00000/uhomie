<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div v-for="check in info.notifications" class="column is-12">
            <div class="field">
                <input :id="'privatSwitch_'+check.id" v-model="check.value" type="checkbox" name="switchRoundedInfo" class="switch is-rounded is-info" :checked="check.value" :disabled="!editing">
                <label :for="'privatSwitch_'+check.id">{{ check.title}}</label>
            </div>
        </div>
    </div>
</template>

<script>



    export default {
        components : {

        },
        name: 'ConfigNotifications',
        props: {
            editing: Boolean,
            info: Object,
        },
        data() {
            return {

            }
        },
        methods : {

        }
    }
</script>
