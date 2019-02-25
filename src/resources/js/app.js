import Vue from 'vue'
import router from './router'
import App from './App.vue'

new Vue({
  el: '#app',
  router, //define routing
  components: { App }, //Declare to use root component
  template: '<App />' //render root component
})