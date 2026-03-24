<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const remember = ref(false)
const showPassword = ref(false)

const modal = ref({ show: false, type: 'error', title: '', message: '', onConfirm: null })

const showModal = (type, title, message, onConfirm = null) => {
  modal.value = { show: true, type, title, message, onConfirm }
}

const closeModal = () => {
  const cb = modal.value.onConfirm
  modal.value.show = false
  if (cb) cb()
}

const router = useRouter()

const handleLogin = () => {
  if (!email.value || !password.value) {
    showModal('error', 'Thiếu thông tin', 'Vui lòng nhập đầy đủ email và mật khẩu trước khi đăng nhập.')
    return
  }

  showModal('success', 'Đăng nhập thành công!', 'Chào mừng bạn trở lại VinaTech. Đang chuyển hướng...', () => {
    router.push('/')
  })
}
</script>

<template>
  <div class="page">

    <div class="login-box">

      <!-- LEFT -->
      <div class="left">
        <h1>
          NextGen <br />
          <span>Chinh Phục</span> <br />
          Tầm Cao Mới.
        </h1>
        <p>
          Khám phá hệ sinh thái công nghệ cao cấp,
          nơi hiệu năng gặp gỡ nghệ thuật chế tác tinh xảo.
        </p>
      </div>

      <!-- RIGHT -->
      <div class="right">

        <div class="tabs">
          <span class="active" @click="router.push('/login')">Đăng nhập</span>
          <span @click="router.push('/register')">Đăng ký</span>
        </div>

        <h2>Chào mừng trở lại</h2>
        <p class="sub">Nhập thông tin tài khoản của bạn.</p>

        <!-- EMAIL -->
        <div class="input-box">
          <span>@</span>
          <input v-model="email" placeholder="example@vinatech.vn" />
        </div>

        <!-- PASSWORD -->
        <div class="input-box">
          <span>🔒</span>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="password"
            placeholder="••••••••"
          />
          <button class="eye-btn" @click="showPassword = !showPassword" type="button">
            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>
        </div>

        <!-- OPTIONS -->
        <div class="options">
          <label>
            <input type="checkbox" v-model="remember" />
            Ghi nhớ đăng nhập
          </label>
          <a @click="router.push('/forgot-password')">Quên mật khẩu?</a>
        </div>

        <!-- BUTTON -->
        <button class="btn" @click="handleLogin">Đăng Nhập Ngay</button>

        <div class="divider">HOẶC TIẾP TỤC VỚI</div>

        <!-- SOCIAL -->
        <div class="social">
          <button class="btn-google">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48">
              <path fill="#fff" d="M44.5 20H24v8.5h11.7C34.2 33.6 29.7 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l6-6C34.5 6.5 29.6 4.5 24 4.5 12.7 4.5 3.5 13.7 3.5 25S12.7 45.5 24 45.5c11 0 20.5-8 20.5-20.5 0-1.4-.1-2.7-.5-4z"/>
              <path fill="#fff" d="M6.3 14.7l7 5.1C15 16.1 19.2 13 24 13c3 0 5.7 1.1 7.8 2.9l6-6C34.5 6.5 29.6 4.5 24 4.5c-7.8 0-14.5 4.4-17.7 10.2z"/>
            </svg>
            Google
          </button>
          <button class="btn-facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#fff">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
            Facebook
          </button>
        </div>

        <p class="register">
          Bạn chưa có tài khoản?
          <a @click="router.push('/register')">Tạo tài khoản mới</a>
        </p>

      </div>
    </div>

    <!-- MODAL -->
    <Transition name="modal">
      <div v-if="modal.show" class="modal-overlay" @click.self="closeModal">
        <div class="modal-card" :class="modal.type">
          <div class="modal-icon">
            <svg v-if="modal.type === 'error'" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="32" height="32"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
              <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
          </div>
          <h3 class="modal-title">{{ modal.title }}</h3>
          <p class="modal-message">{{ modal.message }}</p>
          <button class="modal-btn" :class="modal.type" @click="closeModal">
            {{ modal.type === 'success' ? 'Tiếp tục' : 'Đã hiểu' }}
          </button>
        </div>
      </div>
    </Transition>

  </div>
</template>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f7fc;
  font-family: 'Inter', sans-serif;
}
.login-box {
  width: 950px;
  height: 520px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  background: white;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}
.left {
  background: linear-gradient(135deg, #2563eb, #7c3aed);
  color: white;
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.left h1 { font-size: 34px; line-height: 1.3; }
.left span { color: #c4b5fd; }
.left p { margin-top: 20px; font-size: 14px; opacity: 0.85; }
.right { padding: 40px; }
.tabs { display: flex; gap: 20px; margin-bottom: 20px; font-weight: 600; }
.tabs span { cursor: pointer; color: #94a3b8; transition: color 0.2s; }
.tabs span:hover { color: #475569; }
.tabs .active { color: #2563eb; border-bottom: 2px solid #2563eb; }
h2 { margin-bottom: 4px; }
.sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
.input-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f1f5f9;
  border-radius: 10px;
  padding: 10px;
  margin-bottom: 14px;
}
.input-box span { color: #64748b; }
.input-box input { border: none; background: transparent; outline: none; flex: 1; }
.eye-btn {
  background: none; border: none; padding: 0 2px;
  cursor: pointer; color: #94a3b8;
  display: flex; align-items: center;
  line-height: 1; transition: color 0.2s;
}
.eye-btn:hover { color: #475569; }
.options { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 20px; }
.options a { color: #2563eb; text-decoration: none; cursor: pointer; }
.options a:hover { text-decoration: underline; }
.btn {
  width: 100%; padding: 12px; border-radius: 25px; border: none;
  background: linear-gradient(90deg, #2563eb, #7c3aed);
  color: white; font-weight: 600; cursor: pointer;
  margin-bottom: 20px; transition: opacity 0.2s;
}
.btn:hover { opacity: 0.9; }
.divider { text-align: center; font-size: 12px; color: #94a3b8; margin-bottom: 15px; }
.social { display: flex; gap: 10px; }
.btn-google, .btn-facebook {
  flex: 1; padding: 10px; border-radius: 20px; border: none;
  cursor: pointer; font-weight: 600; font-size: 13px;
  display: flex; align-items: center; justify-content: center;
  gap: 8px; transition: opacity 0.2s;
}
.btn-google { background: #db4437; color: white; }
.btn-facebook { background: #1877f2; color: white; }
.btn-google:hover, .btn-facebook:hover { opacity: 0.88; }
.register { text-align: center; font-size: 12px; margin-top: 15px; }
.register a { color: #2563eb; text-decoration: none; cursor: pointer; font-weight: 600; }
.register a:hover { text-decoration: underline; }
@media (max-width: 768px) {
  .login-box { grid-template-columns: 1fr; width: 95%; height: auto; }
  .left { display: none; }
}

/* MODAL */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex; align-items: center; justify-content: center;
  z-index: 999;
}
.modal-card {
  background: white; border-radius: 20px;
  padding: 36px 32px; width: 360px;
  text-align: center;
  box-shadow: 0 24px 60px rgba(0,0,0,0.15);
}
.modal-icon {
  width: 64px; height: 64px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}
.modal-card.error .modal-icon { background: #fee2e2; color: #ef4444; }
.modal-card.success .modal-icon { background: #dcfce7; color: #22c55e; }
.modal-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #1e293b; }
.modal-message { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }
.modal-btn {
  width: 100%; padding: 12px; border-radius: 25px; border: none;
  font-weight: 600; font-size: 14px; cursor: pointer; transition: opacity 0.2s;
}
.modal-btn.error { background: linear-gradient(90deg, #ef4444, #dc2626); color: white; }
.modal-btn.success { background: linear-gradient(90deg, #2563eb, #7c3aed); color: white; }
.modal-btn:hover { opacity: 0.88; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-active .modal-card, .modal-leave-active .modal-card { transition: transform 0.25s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-card, .modal-leave-to .modal-card { transform: scale(0.92) translateY(10px); }
</style>