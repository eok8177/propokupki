import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Home from '@/views/Home'
import Actions from '@/views/Actions'
import Product from '@/views/Product'
import About from '@/views/About'
import Cabinet from '@/views/Cabinet'
import Contact from '@/views/Contact'

import ErrorPage from '@/views/ErrorPage'

const routes = [

  {path: '/', name: 'Home', component: Home},
  {path: '/actions', name: 'Actions', component: Actions},
  {path: '/product/:slug', name: 'Product', component: Product, props: true},
  {path: '/about', name: 'About', component: About},
  {path: '/cabinet', name: 'Cabinet', component: Cabinet},
  {path: '/contact', name: 'Contact', component: Contact},
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


