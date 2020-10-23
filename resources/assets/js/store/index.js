import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist'
import axios from 'axios'

import explore from './modules/explore'

Vue.use(Vuex)

const vuexLocalStorage = new VuexPersist({
  key: 'uHomie',
  storage: window.sessionStorage, // window.localStorage or window.sessionStorage or localForage
})

export default new Vuex.Store({
  plugins: [vuexLocalStorage.plugin],
  modules: {
    explore
  }
})