import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import VModal from 'vue-js-modal';
import VideoPlayer from 'vue-vjs-hls';
import 'core-js/stable';
import 'regenerator-runtime/runtime';

// https://github.com/FortAwesome/vue-fontawesome
import {library} from '@fortawesome/fontawesome-svg-core';
import {fas} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import DateFormat from '@/filter/DateFormat';

Vue.config.productionTip = false;

import Axios from 'axios'
Axios.defaults.baseURL = 'http://localhost:5080';

VideoPlayer.config({
  youtube: false,
  switcher: true,
  hls: true,
});

// use
Vue.use(VModal);
Vue.use(VideoPlayer);

library.add(fas);
Vue.component('v-icon', FontAwesomeIcon);

Vue.filter('dateFormat', DateFormat);

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount('#app');
