import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './assets/style/main.scss'
require('./bootstrap');
import router from './router';

import App from './App.vue';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
//Vue.config.devtools = false;
Vue.config.productionTip = false;

new Vue({
	router,
	render: h => h(App),
}).$mount('#app');
