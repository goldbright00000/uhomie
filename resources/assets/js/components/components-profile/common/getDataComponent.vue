<script>
    export default {
        extends : ComponentBase,
        props: {
        },
        data() {
            return {
                info: {},
                filters : [],

                dataUrl : '',
                filtersUrl : '',
            };
        },

        mounted: function () {
            this.fetchFiltersData();
            this.fetchInitialServices();
        },

        methods: {

            getIndexData : function(name, index){
                for(var item in this.filters[name]){
                    if(this.filters[name][item].id==index ) return this.filters[name][item].text
                }
            },

            fetchFiltersData: function () {
                if (this.filtersUrl!='')
                axios.get(this.filtersUrl)
                    .then((response) => {
                        for(var filter in response.data.filters) {
                            if (this.filters[filter]==null)
                                this.filters[filter]= {
                                    value: 1,
                                    options:[]
                                };
                            this.filters[filter].options = this.filters[filter].options.concat(response.data.filters[filter])
                        }

                    });
            },
            fetchInitialServices: function () {
                if (this.dataUrl!='')
                axios.get(this.dataUrl, {
                    params: {
                        //limit: 4
                    }
                })
                .then((response) => {
                    this.info = response.data.info;
                });
            }
        }
    };


</script>