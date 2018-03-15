window._ = require('lodash');


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

var ClipboardJS = require('clipboard/dist/clipboard');
var clipboards = new ClipboardJS('.copy-public-key');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Import ES6 Promise
import 'es6-promise/auto'

// Import System requirements
        import Vue from 'vue'
        import VueRouter from 'vue-router'

        import { sync } from 'vuex-router-sync'
        import routes from './routes'
        import store from './store'

// Import Helpers for filters
        import { domain, count, prettyDate, prettyDateTime, toLumens, pluralize } from './filters'

// Import Views - Top level
        import AppView from './components/App.vue'

// Import Install and register helper items
Vue.filter('count', count)
Vue.filter('domain', domain)
Vue.filter('prettyDate', prettyDate)
Vue.filter('pluralize', pluralize)
Vue.filter('prettyDateTime', prettyDateTime)
Vue.filter('toLumens', toLumens)

Vue.use(VueRouter)

// Routing logic
var router = new VueRouter({
    routes: routes,
    mode: 'history',
    linkExactActiveClass: 'active',
    scrollBehavior: function (to, from, savedPosition) {
        return savedPosition || {x: 0, y: 0}
    }
})

// Some middleware to help us ensure the user is authenticated.
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!router.app.$store.state.token || router.app.$store.state.token === 'null') {
            // this route requires auth, check if logged in
            // if not, redirect to login page.
            window.console.log('Not authenticated')
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            })
        } else if (!router.app.$store.state.user.lmnry_verified) {
            window.console.log('Public Key Not Verified')
            next({
                path: '/verify/key/public',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next()
    }
})

sync(store, router)

// Check local storage to handle refreshes
if (window.localStorage) {
    var localUserString = window.localStorage.getItem('user') || 'null'
    var localUser = JSON.parse(localUserString)

    if (localUser && store.state.user !== localUser) {
        store.commit('SET_USER', localUser)
        store.commit('SET_TOKEN', window.localStorage.getItem('token'))
    }
}

// Start out app!
// eslint-disable-next-line no-new
new Vue({
    el: '#dashboard',
    router: router,
    store: store,
    render: h => h(AppView)
})
