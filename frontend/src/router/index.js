import { createRouter, createWebHistory } from 'vue-router'

// Layout
import MainLayout from '../components/Layout/MainLayout.vue'

// Pages
import Home from '../components/Web/Home.vue'
import Producpage from '../components/Web/Producpage.vue'
import News from '../components/Web/News.vue'
import Cart from '../components/Web/Cart.vue'
import Checkout from '../components/Web/Checkout.vue'
import ProductDetail from '../components/Web/ProductDetail.vue'
import Contact from '../components/Web/Contact.vue'
import Profile from '../components/Web/Profile.vue'
import Chatbot from '../components/Web/Chatbot.vue'

// Auth
import Login from '../components/Auth/Login.vue'
import Register from '../components/Auth/Register.vue'
import ForgotPassword from '../components/Auth/ForgotPassword.vue'

// Admin
import AdminLayout from '../components/Admin/Layout/AdminLayout.vue'
import AdminDashboard from '../components/Admin/Dashboard.vue'

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Home },
      { path: 'products', component: Producpage },
      { path: 'news', component: News },
      { path: 'contact', component: Contact },
      { path: 'cart', component: Cart },
      { path: 'checkout', component: Checkout },
      { path: 'products/:id', component: ProductDetail },
      { path: 'profile', component: Profile },
      { path: 'chat', component: Chatbot }
    ]
  },

  {
    path: '/login',
    component: Login
  },
  {
    path: '/register',
    component: Register
  },
  {
    path: '/forgot-password',
    component: ForgotPassword
  },

  //  ADMIN
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      { path: '', component: AdminDashboard },
      { path: 'products', component: () => import('../components/Admin/Products.vue') },
      { path: 'orders', component: () => import('../components/Admin/Orders.vue') },
      { path: 'users', component: () => import('../components/Admin/Users.vue') },
      { path: 'news', component: () => import('../components/Admin/News.vue') },
      { path: 'settings', component: () => import('../components/Admin/Settings.vue') },
      { path: 'variants', component: () => import('../components/Admin/ProductVariants.vue') },
      { path: 'categories', component: () => import('../components/Admin/Categories.vue') },
      { path: 'promotions', component: () => import('../components/Admin/Promotions.vue') },
      { path: 'contacts', component: () => import('../components/Admin/Contact.vue') },
      { path: 'brands', component: () => import('../components/Admin/Brands.vue') },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router