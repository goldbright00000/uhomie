<script>

    import ComponentBase from '../../../profiles/base';

    const imagesDir = document.getElementById('images-dir').value;

    export default {

        extends: ComponentBase,
        props: {
            curr_menu: Number,      // el menu actual
            menu_id: Number,        // el menu que me corresponde
        },
        // aqui se leen los datos de la base de datos de ser necesarios
        watch: {
            curr_menu: function (val) {
                if (val == this.menu_id) {
                    this.getData()
                }
            }
        },
        methods: {
            getData: function(num) {
                axios.get('/profile/tenant/data/' + num, {
                    params: {}
                }).then(
                    response => this.info = response.data.info
                ).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
