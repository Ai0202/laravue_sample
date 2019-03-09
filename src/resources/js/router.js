import Vue from 'vue'
import VueRouter from 'vue-router'

//import components'
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'
import PhotoDetail from './pages/PhotoDetail.vue'

import store from './store'

import SystemError from './pages/errors/System.vue'

Vue.use(VueRouter)

// mapping path and component
const routes = [
  {
    path: '/',
    component: PhotoList
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/photos/:id',
    component: PhotoDetail,
    props: true
  },
  {
    path: '/500',
    component: SystemError
  }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

// export VueRouter instance for import app.js
export default router