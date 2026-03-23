<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const remember = ref(false)

const router = useRouter()

const handleLogin = () => {
  if (!email.value || !password.value) {
    alert('Vui lòng nhập đầy đủ thông tin')
    return
  }

  router.push('/')
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
          <span class="active">Đăng nhập</span>
          <span>Đăng ký</span>
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
          <input type="password" v-model="password" placeholder="••••••••" />
        </div>

        <!-- OPTIONS -->
        <div class="options">
          <label>
            <input type="checkbox" v-model="remember" />
            Ghi nhớ đăng nhập
          </label>
          <a href="#">Quên mật khẩu?</a>
        </div>

        <!-- BUTTON -->
        <button class="btn" @click="handleLogin">
          Đăng Nhập Ngay
        </button>

        <div class="divider">HOẶC TIẾP TỤC VỚI</div>

        <!-- SOCIAL -->
        <div class="social">
          <button>Google</button>
          <button>Facebook</button>
        </div>

        <p class="register">
          Bạn chưa có tài khoản?
          <a href="#">Tạo tài khoản mới</a>
        </p>

      </div>

    </div>

  </div>
</template>

<style scoped>

/* PAGE */
.page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f7fc;
  font-family: 'Inter', sans-serif;
}

/* BOX */
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

/* LEFT */
.left {
  background: linear-gradient(135deg,#2563eb,#7c3aed);
  color: white;
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.left h1 {
  font-size: 34px;
  line-height: 1.3;
}

.left span {
  color: #c4b5fd;
}

.left p {
  margin-top: 20px;
  font-size: 14px;
  opacity: 0.85;
}

/* RIGHT */
.right {
  padding: 40px;
}

.tabs {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  font-weight: 600;
}

.tabs span {
  cursor: pointer;
  color: #94a3b8;
}

.tabs .active {
  color: #2563eb;
  border-bottom: 2px solid #2563eb;
}

h2 {
  margin-bottom: 4px;
}

.sub {
  font-size: 13px;
  color: #64748b;
  margin-bottom: 20px;
}

/* INPUT */
.input-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f1f5f9;
  border-radius: 10px;
  padding: 10px;
  margin-bottom: 14px;
}

.input-box span {
  color: #64748b;
}

.input-box input {
  border: none;
  background: transparent;
  outline: none;
  flex: 1;
}

/* OPTIONS */
.options {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  margin-bottom: 20px;
}

.options a {
  color: #2563eb;
  text-decoration: none;
}

/* BUTTON */
.btn {
  width: 100%;
  padding: 12px;
  border-radius: 25px;
  border: none;
  background: linear-gradient(90deg,#2563eb,#7c3aed);
  color: white;
  font-weight: 600;
  cursor: pointer;
  margin-bottom: 20px;
}

/* DIVIDER */
.divider {
  text-align: center;
  font-size: 12px;
  color: #94a3b8;
  margin-bottom: 15px;
}

/* SOCIAL */
.social {
  display: flex;
  gap: 10px;
}

.social button {
  flex: 1;
  padding: 10px;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  background: white;
}

/* REGISTER */
.register {
  text-align: center;
  font-size: 12px;
  margin-top: 15px;
}

.register a {
  color: #2563eb;
  text-decoration: none;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .login-box {
    grid-template-columns: 1fr;
    width: 95%;
    height: auto;
  }

  .left {
    display: none;
  }
}

</style>