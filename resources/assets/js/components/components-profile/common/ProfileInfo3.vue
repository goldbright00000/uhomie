<template>
    <div class="profile-info3">
        <div class="columns">
            <div class="column is-one-third">
                <span class="as-text-line">{{ info.age_cstatus }}</span>
            </div>
            <div class="column is-one-third">
                <span class="as-text-line">{{ info.nationality }}</span>
            </div>
            <div class="column is-one-third">
                <span class="as-text-line">RUT: {{ info.rut }}</span>
            </div>

        </div>

    </div>
</template>
<script>

    export default {
        name: 'ProfileInfo3',
        props: {
          info: Object,
        },
        data() {
            return {}
        }
    }
</script>
