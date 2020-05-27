import Vue from 'vue'




require('./bootstrap');
import router from './router';



import App from './App.vue'



//Vue.config.devtools = false;
Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    router,
    components: {
        App,
    },
  
    created() {
      
    }
});