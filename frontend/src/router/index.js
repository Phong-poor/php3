import { createRouter, createWebHistory } from 'vue-router'

// ── Layout ──
import MainLayout from '../components/Layout/MainLayout.vue'

// ── Web Pages ──
import Home from '../components/Web/Home.vue'
import Producpage from '../components/Web/Producpage.vue'
import News from '../components/Web/News.vue'
import Cart from '../components/Web/Cart.vue'
import Checkout from '../components/Web/Checkout.vue'
import ProductDetail from '../components/Web/ProductDetail.vue'
import Contact from '../components/Web/Contact.vue'
import Profile from '../components/Web/Profile.vue'
import Chatbot from '../components/Web/Chatbot.vue'
import Orderspage from '../components/Web/Orderspage.vue'
import Addresspage from '../components/Web/Addresspage.vue'
import Passwordpage from '../components/Web/Passwordpage.vue'
import LoginSuccess from '../components/Web/LoginSuccess.vue'
import WishlistPage from '../components/Web/WishlistPage.vue'

// ── Auth ──
import Login from '../components/Auth/Login.vue'
import Register from '../components/Auth/Register.vue'
import ForgotPassword from '../components/Auth/ForgotPassword.vue'
import OtpVerify from '../components/Auth/OtpVerify.vue'
import ResetPassword from '../components/Auth/ResetPassword.vue'

// ── Admin ──
import AdminLayout from '../components/Admin/Layout/AdminLayout.vue'
import AdminDashboard from '../components/Admin/Dashboard.vue'

const routes = [
  // ── WEB ──
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Home },
      { path: 'products', component: Producpage },
      { path: 'products/:id', component: ProductDetail },
      { path: 'news', component: News },
      { path: 'contact', component: Contact },
      { path: 'cart', component: Cart },
      { path: 'checkout', component: Checkout },
      { path: 'profile', component: Profile },
      { path: 'chat', component: Chatbot },
      { path: 'orderspage', component: Orderspage },
      { path: 'addresspage', component: Addresspage },
      { path: 'passwordpage', component: Passwordpage },
      { path: 'wishlistpage', component: WishlistPage },
    ],
  },

  // ── AUTH ──
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/forgot-password', component: ForgotPassword },
  { path: '/otp-verify', component: OtpVerify },
  { path: '/reset-password', component: ResetPassword },

  { path: '/login-success', component: LoginSuccess },



  // ── ADMIN ──
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true },
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
      { path: 'reviews' , component: () => import('../components/Admin/ReviewManagement.vue')},
    ],
  },

  // ── 404 fallback ──
  { path: '/:pathMatch(.*)*', redirect: '/' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user'))
  const token = localStorage.getItem('token')

  // route public (KHÔNG cần login)
  const publicPages = [
    '/',
    '/products',
    '/products/',
    '/login',
    '/register'
  ]

  const isPublic = publicPages.some(path => to.path.startsWith(path))

  if (!isPublic && !token) {
    return next('/login')
  }

  if (to.matched.some(route => route.meta.requiresAdmin)) {
    if (!user) return next('/login')
    if (user.role !== 'admin') return next('/')
  }

  next()
})

router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user'))

  if (to.matched.some(route => route.meta.requiresAdmin)) {
    if (!user) {
      return next('/login')
    }

    if (user.role !== 'admin') {
      return next('/')
    }
  }

  next()
})

export default router