/* Vuex */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex)

import  slots  from './slot.module';

// window.Vue.use(Vuex);

export const store = new Vuex.Store({
  modules: {
    slots
  }
});




