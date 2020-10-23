//import base from 'base';
const _token = document.getElementById('_token').value;
const imagesDir = document.getElementById('images-dir').value;

export default Vue.extend({

    //extends: base,

    props: {

    },

    data() {
        return {
            verification2f: 0,
            editing: false,
            _cache: {},
            imagesDir: imagesDir,
            _token : _token,
            saveEmploymentUrl: 'tenant/save-employment' // Editado por AA
        }
    },

    methods: {

        edit_event : function(){
            this.editing = true;
            this._cache = Object.assign({}, this.info);
            //console.log('editandoooo base')
        },

        cancel_edit : function(){
            console.log('cancelando base')

            this.editing = false;
            Object.assign(this.info, this._cache);


        },

        save_event: function (a) {
            let mapping_zone = a[0];
            this.editing = false;

            let params = new URLSearchParams();
            params.append('_token', _token);

            let watching = a[1];

            if (this.info[watching]) {
                params.append('action', watching);
                let field = this.info[watching];

                
                if(!watching == 'notifications') {           // amenities
                    params.append('data', field);
                    let idProperty = a[2];
                    for(var t in o)
                        params.append(t, o[t]);

                } else {                                // notifications/documents : (objects)
                    let data = [];
                    for(var t in field){
                        params.append('data['+field[t].id+']', field[t].value);
                    }
                }




            } else { // data comun

                var map = this.mapping[mapping_zone];
                for (var t in map)
                    if (this.info[map[t]]) params.append(map[t], this.info[map[t]]);

            }

            this.$Progress.start()

            var url = this.saveUrl

            switch(mapping_zone) {
                case 'employee': 
                    url = this.saveEmploymentUrl
                    break;
                case 'company': 
                    url = this.saveCompany
                    var map = this.mapping[mapping_zone];
                    for (var t in map)
                        if (this.info.company[map[t]]) params.append(map[t], this.info.company[map[t]]);
                    break;
                case 'address_company': 
                    url = this.saveCompany
                    var map = this.mapping[mapping_zone];
                    for (var t in map)
                        if (this.info.company[map[t]]) params.append(map[t], this.info.company[map[t]]);
                    break;
                default:
                    break;
            }

            axios.post(url, params, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then((response) => {
                toastr.success('Los datos se han salvado satisfactoriamente.')
                this.$Progress.finish()
            }).catch((error) => {
                console.log(error)
                if(error.response.data.hasOwnProperty('mensaje')){
                    let info = error.response.data.mensaje;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                } else {
                    toastr.error('No se a cumplido con los requerimientos en los campos')
                    this.$Progress.fail()
                    console.log(error)
                }
            });
        },

        save_pass_event(e){
            console.log(this.info.password)
        },

        save_data_prop(e) {
            let watching = e[1];
            let idProperty = e[2].id;

            let params = new URLSearchParams();
            params.append('_token', _token);

            params.append('action', watching);
            params.append('id', idProperty);

            for(var am of this.info.amenities) {
                console.log(am)
                params.append('data[]', am);
            }

            this.$Progress.start()

            axios.post(this.saveUrl, params, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then((response) => {
                this.editing = false;
                toastr.success('Los datos se han salvado satisfactoriamente.')
                this.$Progress.finish()
            }).catch((error) => {
                if(error.response) {
                    let info = error.response.data;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                }else {
                    console.log(error)
                }
            });
        },

        change_set_collateral(e) {
            let mapping_zone = e[0];
            this.editing = false;

            let params = new URLSearchParams();
            
            params.append('_token', _token);
            
            var map = this.mapping[mapping_zone];
            
            for (var m of map) {
                if (this.info[m]) {
                    var collateral = this.info[m]

                    for (var i in collateral) {
                        params.append('collateral['+ i +']', collateral[i]);
                    }

                }
            }

            this.$Progress.start()

            axios.post('/users/tenant/registration/first-step/three', params, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then((response) => {
                toastr.success('Los datos se han salvado satisfactoriamente.')
                this.$Progress.finish()
            }).catch((error) => {
                if(error.response) {
                    let info = error.response.data;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                }else {
                    console.log(error)
                }
            });
        },


        save_photos_event(e) {
            this.editing = false;

            const params = new FormData()
            
            params.append('_token', _token);
            
            let photos = this.info.photos 
        
            for (var photo of photos) {
                if (photo.file) {
                    console.log(photo)
                    params.append('property_id', this.info.id)
                    params.append('photo_id', photo.id)
                    params.append('cover', photo.cover)
                    params.append('space_id', photo.space.id)
                    params.append('photo_name', photo.file.name.split('.')[0])
                    params.append('files', photo.file)

                    this.$Progress.start()

                    axios.post('/save-prop-photos', params, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                        }
                    }).then((response) => {
                        toastr.success('Los datos se han salvado satisfactoriamente.')
                        this.$Progress.finish()
                    }).catch((error) => {
                        if(error.response) {
                            let info = error.response.data;
                            let status = error.response.status;
                            this.$Progress.fail()
                            toastr.error(info)
                        }else {
                            console.log(error)
                        }
                    });

                }
            }

            this.$Progress.start()

            axios.post('/save-prop-photos', params, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                toastr.success('Los datos se han salvado satisfactoriamente.')
                this.$Progress.finish()
            }).catch((error) => {
                if(error.response) {
                    let info = error.response.data;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                }else {
                    console.log(error)
                }
            });
        },
        save_photos_service_event(e) {
            this.editing = false;

            const params = new FormData()
            
            params.append('_token', _token);
            
            let photos = this.info.photos 
        
            for (var photo of photos) {
                if (photo.file) {
                    console.log(photo)
                    params.append('service_list_id', this.info.id)
                    params.append('photo_id', photo.id)
                    params.append('cover', photo.cover)
                    params.append('photo_name', photo.file.name.split('.')[0])
                    params.append('files', photo.file)

                    this.$Progress.start()

                    axios.post('/save-servi-photos', params, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                        }
                    }).then((response) => {
                        toastr.success('Los datos se han salvado satisfactoriamente.')
                        this.$Progress.finish()
                    }).catch((error) => {
                        if(error.response) {
                            let info = error.response.data;
                            let status = error.response.status;
                            this.$Progress.fail()
                            toastr.error(info)
                        }else {
                            console.log(error)
                        }
                    });

                }
            }

            this.$Progress.start()

            axios.post('/save-servi-photos', params, {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }).then((response) => {
                toastr.success('Los datos se han salvado satisfactoriamente.')
                this.$Progress.finish()
            }).catch((error) => {
                if(error.response) {
                    let info = error.response.data;
                    let status = error.response.status;
                    this.$Progress.fail()
                    toastr.error(info)
                }else {
                    console.log(error)
                }
            });
        },
    }
});
