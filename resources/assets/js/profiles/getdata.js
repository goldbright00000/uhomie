    import base from 'base';

    const imagesDir = document.getElementById('images-dir').value;

    export default Vue.extend({

        props: {
            imagesDir: {
                type: String,
                default: imagesDir,
            }
        },

        data() {
            return {
                loading: true,
                info: {},
                filters : {},
         
                dataUrl : '',
                filtersUrl : '',
            };
        },

        mounted: function () {
            
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
                            this.filters[filter].options = this.filters[filter].options.concat(response.data.filters[filter].options)
                        }
                    });
            },
            fetchInitialServices: function () {
                if (this.dataUrl != '')
                    this.$Progress.start()
                axios.get(this.dataUrl, {
                    params: {
                        //limit: 4
                    }
                })
                .then((response) => {
                    this.loading = false;
                    sessionStorage.setItem('secret', response.data.info.secret);
                    this.$Progress.finish()
                    this.info = response.data.info;
                }).catch(error => {
                    let info = error.response.data;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                });;
            }
        }
    })
