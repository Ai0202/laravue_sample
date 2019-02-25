import Vue from 'vue'
import VueRouter from 'vue-router'

//import components'
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

Vue.use(VueRouter)

// mapping path and component
const routes = [
  {
    path: '/',
    component: PhotoList
  },
  {
    path: '/login',
    component: Login
  }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

// export VueRouter instance for import app.js
export default router