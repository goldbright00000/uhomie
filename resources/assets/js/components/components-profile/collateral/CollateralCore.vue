<script>
    export default {
        components: {

        },
        name: 'CollateralCore',
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