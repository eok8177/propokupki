import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Home from '@/views/Home'
import Actions from '@/views/Actions'

import ErrorPage from '@/views/ErrorPage'

const routes = [

  {path: '/', name: 'Home', component: Home},
  {path: '/actions', name: 'Actions', component: Actions},
  {path: '/404', name: '404', component: ErrorPage},


  { path: '*', redirect: '/404', hidden: true }
]

export default new Router({
  mode: 'history',
  routes: routes,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})


