<template>
    <div class="columns">
        <div class="column is-full">
            <div class="columns">
                <div class="column is-12 line-down">
                    <span>Perfil Arrendatario</span>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <a @click="$router.push('/tenants')">
                        <span><i class="fa fa-arrow-left"></i> Volver</span>
                    </a>
                </div>
            </div>
            <div class="columns is-vcentered">
                <div class="column is-8">
                    <div class="columns is-vcentered">
                        <div class="column">
                            <div class="media">
                                <figure class="media-left">
                                    <p class="image is-64x64">
                                        <img class="is-rounded" :src="tenant.photo || imagesDir + '/roles/avatar-uhomie.png'" style="width:75px; height:75px; border-radius: 50%;"/>
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content">
                                        <div>
                                            <b style="color: #ffd900">{{ tenant.firstname + ' ' + tenant.lastname }}</b>
                                        </div>
                                        <div>
                                            <b>Arrendatario</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line is-size-7">{{tenant.age}} Años, {{getCivilStatus(tenant.civil_status_id)}}</span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line is-size-7">{{ getCountry(tenant.country_id) }}</span>
                        </div>
                        
                    </div>
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line is-size-7">{{ tenant.document_type }} {{ tenant.document_number }}</span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line is-size-7">Celular: (+{{ tenant.phone_code }}) {{ tenant.phone }}</span>
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <div style="border: solid black 1px">
                        <your-mem v-bind:role="info.role" v-bind="tenant"></your-mem>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</template>
<script>
const imagesDir = document.getElementById('images-dir').value;
import CollateralCore from './CollateralCore';
import YourMem from '../common/YourMem';
export default {
    extends: CollateralCore,
    name: 'CollateralTenantProfile',
    components: {
        YourMem
    },
    data() {
        return {
            imagesDir: imagesDir,
            tenant: []
        }
    },
    mounted() {
        let tenantInfo = this.getTenant(this.$route.params.idTenant);
        this.tenant = tenantInfo
    },
    methods: {
        getCivilStatus: function(w){
            var nfo=this.filters.civilstatus.options;
            for(var t in nfo){
                if (nfo[t].id==w) {
                    return nfo[t].text;
                }
            }
            return {};
        },
        getCountry: function(w){
            var nfo=this.filters.countries.options;
            for(var t in nfo){
                if (nfo[t].id==w) {
                    return nfo[t].text;
                }
            }
            return {};
        },
        getTenant: function(w){
            var nfo=this.info.tenants;
            for(var t in nfo){
                if (nfo[t].id==w) {
                    return nfo[t];
                }
            }
            return {};
        },
    }
    
}
</script>
