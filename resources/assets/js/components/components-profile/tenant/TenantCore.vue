<script>
    export default {
        components: {

        },
        name: 'TenantCore',
        props: {},
        computed: {
            info() {
                return this.$parent.info;
            },
            filters() {
                return this.$parent.filters;
            }
        },
        data() {
            return {}
        }
    }
</script>