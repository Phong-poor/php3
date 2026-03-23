import { createWebHistory, createRouter } from 'vue-router'

import home from './components/Web/Home.vue'
import Producpage from './components/Web/Producpage.vue'
import News from './components/Web/News.vue'
import Contact from './components/Web/Contact.vue'      
import MainLayout from './components/Layout/MainLayout.vue'
import Cart from './components/Layout/Cart.vue'
import Checkout from './components/Web/Checkout.vue' 
import Login from './components/Auth/Login.vue' 


const routes = [
        
    { path: '/', name: 'MainLayout', component: MainLayout, meta: { isAuth: true }},
    { path: '/products', name: 'Products', component: Producpage, meta: { isAuth: true }},
    { path: '/news', name: 'News', component: News, meta: { isAuth: true }},
    { path: '/contact', name: 'Contact', component: Contact, meta: { isAuth: true }},
    { path: '/checkout', name: 'Checkout', component: Checkout, meta: { isAuth: true }},
    { path: '/cart', name: 'Cart', component: Cart, meta: { isAuth: true }},
    { path: '/login', name: 'Login', component: Login, meta: { isAuth: false }},

    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound,
        meta: { isAuth: false  }
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes
})
router.beforeEach((to, from, next) => {
  const currentUser = localStorage.getItem('currentUser');
  
  if (to.meta.isAuth) {
    if (!currentUser) {
      // chưa login -> về trang login
      return next({ name: 'Login' });
    }

    const user = JSON.parse(currentUser);
    if (!user.role) {
      // user không có quyền hợp lệ
      return next({ name: 'Login' });
    }
  }

  // nếu mọi thứ ok thì cho đi tiếp
  next();
});

export default router