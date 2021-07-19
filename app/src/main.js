import Vue from 'vue'
import App from './App.vue'
import VueNotify from 'vuejs-notify'
import VueRouter from 'vue-router'
import router from './router'
import store from "./store"
import config from "./config/env"

Vue.config.productionTip = false
Vue.prototype.server = config.api

Vue.use(VueRouter)
Vue.use(VueNotify, {
  position: 'bottom right'
})

new Vue({
  render: h => h(App),
  store,
  router
}).$mount('#app')
