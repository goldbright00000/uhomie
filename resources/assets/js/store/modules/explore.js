// Explore Filters state
const state = {
	loaded: false,
  active: false,
	basic: {
    city: {
      value: 0,
      options: [{id: 0, text: 'Todas'}]
    },
    village: {
      value: 0,
      options: [{id: 0, text: 'Todas'}]
    },
    membership: {
      value: 0,
      options: [{id: 0, text: 'Todas'}]
    },
    propertyType: {
      value: 0,
      options: [{id: 0, text: 'Todas'}]
    },
    profile: {
      value: 0,
      options: [{id: 0, text: 'Todas'}]
    },
    type_user: {
      value: 0,
      options: [
        {id: 0, text: 'Todos'},
        {id: 1, text: 'Dueños directo'},
        {id: 5, text: 'Corredores'},
      ]
    },
    type_stay: {
      value: 0,
      options: [
        {id: 0, text: 'Todos'},
        {id: 'SHORT_STAY', text: 'Corta'},
        {id: 'LONG_STAY', text: 'Larga'},
      ]
    },
  },
  features: {
    rooms: 0,
    meters: 0,
    bath: 0,
    verifiedProperty: false,
    pets: false,
    parking: false,
    furnished: false,
    cellar: false,
    insurance: false
  },
	unitServices: {
    options: [{id: 1, text: 'Baño'}, {id: 2, text: 'Cocina'}, {id: 3, text: 'Estacionamiento'}],
    selected: []
  },
  condoServices: {
    options: [{id: 1, text: 'Baño'}, {id: 2, text: 'Cocina'}, {id: 3, text: 'Estacionamiento'}],
    selected: []
  },
}

// getters
const getters = {
  active: (state) => {
    return state.active
  },
  basic: (state) => {
    return state.basic
  },
  features: (state) => {
    return state.features
  },
  condoServices: (state) => {
    return state.condoServices
  },
  unitServices: (state) => {
    return state.unitServices
  },
}

// actions
const actions = {
	cleanFilters({commit}) {
		commit('cleanFilters')    
	},
  fetchBasicData({ commit, state }) {
    return new Promise((resolve, reject) => {
      if(!state.loaded) {
        axios.get('/explorar/basic-filters')
        .then( res => {
          commit('setBasicData', res.data)

          resolve()
        })
        .catch((err) => {
          reject(err)
        })
      } else {
        reject()
      }
    })
  },
  fetchVillage({commit, state}, city_id) {

  	commit('setBasicCityValue', city_id)

  	axios.get('/get-villages', {
      params: {
        city: city_id
      }
     })
  	.then((res) => {
    	var options = [{id: 0, text: 'Todas'}]
    	options = options.concat(res.data.villages)
    	var value = 0

	  	if(state.basic.village.value > 0) {
	  		options.find(function(element) {
	  			if(element.id == state.basic.village.value) {
	  				value = state.basic.village.value	
	  			}
	  		})
	  	}
    	commit('newBasicVillage', {
    		value: value,
    		options: options,
    	})
    })
  }
}

// mutations
const mutations = {
  setBasicData(state, data) {
    Vue.set(state, 'loaded', true)
    Vue.set(state.basic.city, 'options', state.basic.city.options.concat(data.cities))
    Vue.set(state.basic.village, 'options',state.basic.village.options.concat(data.villages))
    Vue.set(state.basic.membership, 'options', state.basic.membership.options.concat(data.memberships))
    Vue.set(state.basic.propertyType, 'options', state.basic.propertyType.options.concat(data.propertyTypes))
    Vue.set(state.basic.profile, 'options', state.basic.profile.options.concat(data.profiles))
    Vue.set(state.unitServices, 'options', data.unitAmenities)
    Vue.set(state.condoServices, 'options', data.condoAmenities)
  },
  setBasicCityValue(state, city_id) {
    Vue.set(state.basic.city, 'value', city_id)
    Vue.set(state, 'active', true)
  },
  setBasicVillageValue(state, village_id) {
    Vue.set(state.basic.village, 'value', village_id)
    Vue.set(state, 'active', true)
  },
  setMembershipValue(state, membership_id) {
    Vue.set(state.basic.membership, 'value', membership_id)
    Vue.set(state, 'active', true)
  },
  setPropertyTypeValue(state, propertyType_id) {
    Vue.set(state.basic.propertyType, 'value', propertyType_id)
    Vue.set(state, 'active', true)
  },
  setProfileValue(state, profile_id) {
    Vue.set(state.basic.profile, 'value', profile_id)
    Vue.set(state, 'active', true)
  },
  setTypeUserValue(state, type_user) {
    Vue.set(state.basic.type_user, 'value', type_user)
    Vue.set(state, 'active', true)
  },
  setTypeStayValue(state, type_stay) {
    Vue.set(state.basic.type_stay, 'value', type_stay)
    Vue.set(state, 'active', true)
  },
  setFeaturesValue(state, data) {
//    state.features[data.type] = data.value
    Vue.set(state.features, data.type, data.value)
    Vue.set(state, 'active', true)
  },
  newBasicVillage(state, village) {
    Vue.set(state.basic, 'village', village)
  },
  cleanFilters(state) {
    Vue.set(state, 'active', false)
    Vue.set(state.basic.city, 'value', 0)
    Vue.set(state.basic.village, 'value', 0)
    Vue.set(state.basic.membership, 'value', 0)
    Vue.set(state.basic.propertyType, 'value', 0)
    Vue.set(state.basic.profile, 'value', 0)
    Vue.set(state, 'features', {
      rooms: 0,
      meters: 0,
      bath: 0,
      verifiedProperty: false,
      pets: false,
      parking: false,
      furnished: false,
      cellar: false,
      insurance: false
    })    

    console.log(state)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}