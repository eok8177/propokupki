import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Home from '@/views/Home'
import Avtopark from '@/views/Avtopark'
import About from '@/views/About'
import Reviews from '@/views/Reviews'
import Tips from '@/views/Tips'
import Tip from '@/views/Tip'
import Bus from '@/views/Bus'

const routes = [

  {path: '/', name: 'Home', component: Home},
  {path: '/avtopark', name: 'Avtopark', component: Avtopark},
  {path: '/about-us', name: 'About', component: About},
  {path: '/reviews', name: 'Reviews', component: Reviews},
  {path: '/tips', name: 'Tips', component: Tips},
  {path: '/tip/:id', name: 'Tip', component: Tip},
  {path: '/bus/:id', name: 'Bus', component: Bus, props: true},

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


