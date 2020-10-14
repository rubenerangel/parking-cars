require('./bootstrap');

const URL = process.env.MIX_URL;
const URL_API = `${URL}/api/v1`;

window.axios.defaults.baseURL = URL_API;

window.moment = require('moment');

window.Vue = require('vue');

import {store} from './store/index';

Vue.component('base-parking', require('./components/BaseParking.vue').default); 

const app = new Vue({
  el: '#app',
  store
});
