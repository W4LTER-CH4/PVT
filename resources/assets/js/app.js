// Vue instance
import '@/bootstrap'
Vue.config.productionTip = false
import App from '@/layout/App'

// Print PDF
import print from 'print-js'

// Toast notification
import toastr from 'toastr'
import 'toastr/build/toastr.min.css'
toastr.options = {
  debug: false,
  closeButton: true,
  newestOnTop: true,
  progressBar: true,
  timeOut: 6000,
  fadeIn: 300,
  fadeOut: 1000,
  extendedTimeOut: 6000,
  preventDuplicates: true,
  positionClass: 'toast-bottom-right'
}
Vue.prototype.toastr = toastr

// Validator
import '@/plugins/vee-validate'

// Vuetify
import vuetify from '@/plugins/vuetify'

// Locale
import VueI18n from 'vue-i18n'
const i18n = new VueI18n({
  locale: 'es'
})

// Router
import router from '@/plugins/router'

// Vuex
import Vuex from 'vuex'
import StoreData from '@/store'
const store = new Vuex.Store(StoreData)

// Moment
import '@/plugins/moment'

// Filters
import '@/plugins/filters'

// Map
import '@/plugins/map'

// JWT
axios.defaults.withCredentials = true
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Authorization'] = `${store.getters.tokenType} ${store.getters.accessToken}`
axios.interceptors.response.use(response => {
  return response
}, error => {
  if (error.response) {
    if ([401, 409].includes(error.response.status)) {
      if (error.response.status == 401) {
        store.dispatch('logout')
        if (router.currentRoute.name != 'login') {
          router.push('login')
        }
      } else {
        for (let key in error.response.data.errors) {
          error.response.data.errors[key].forEach(error => {
            toastr.error(error)
          })
        }
      }
    }
  }
  if (error.response.data.hasOwnProperty('errors')) return Promise.reject(error.response.data.errors)
  return []
})
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const user = store.state.user
  store.dispatch('refresh')
  if (requiresAuth && !user) {
    next({
      path: '/login'
    })
  } else if (to.path == '/login' && user) {
    next({
      path: '/'
    })
  } else {
    next()
  }
})
if (store.getters.tokenExpired) {
  store.dispatch('logout')
}

export const vm = new Vue({
  router,
  i18n,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

import money from 'v-money'
 
// register directive v-money and component <money>
Vue.use(money, {precision: 2})

