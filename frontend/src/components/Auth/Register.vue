<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const confirm = ref('')

const router = useRouter()

const handleRegister = () => {
  if (!name.value || !email.value || !phone.value || !password.value || !confirm.value) {
    alert('Vui lòng nhập đầy đủ thông tin')
    return
  }

  if (password.value !== confirm.value) {
    alert('Mật khẩu không khớp')
    return
  }

  alert('Đăng ký thành công!')
  router.push('/login')
}
</script>

<template>
  <div class="page">

    <div class="box">

      <!-- LEFT -->
      <div class="left">
        <h1>
          NextGen <br />
          Thế hệ mới 2026 <br />
          <span>Chinh Phục Tầm Cao</span>
        </h1>

        <p>
          Khám phá hệ sinh thái công nghệ đỉnh cao,
          nơi hiệu năng gặp gỡ thiết kế tinh xảo.
        </p>

        <img src="https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?w=500"/>
      </div>

      <!-- RIGHT -->
      <div class="right">

        <h2>Tạo tài khoản mới</h2>
        <p class="sub">Bắt đầu hành trình cùng VinaTech</p>

        <!-- NAME -->
        <div class="input-box">
          <span>👤</span>
          <input v-model="name" placeholder="Nguyễn Văn A" />
        </div>

        <!-- EMAIL -->
        <div class="input-box">
          <span>@</span>
          <input v-model="email" placeholder="example@vinatech.vn" />
        </div>

        <!-- PHONE -->
        <div class="input-box">
          <span>📞</span>
          <input v-model="phone" placeholder="0123 456 789" />
        </div>

        <!-- PASSWORD -->
        <div class="input-box">
          <span>🔒</span>
          <input type="password" v-model="password" placeholder="••••••••" />
        </div>

        <!-- CONFIRM -->
        <div class="input-box">
          <span>🔒</span>
          <input type="password" v-model="confirm" placeholder="Xác nhận mật khẩu" />
        </div>

        <!-- BUTTON -->
        <button class="btn" @click="handleRegister">
          Đăng ký ngay
        </button>

        <div class="divider">HOẶC ĐĂNG KÝ BẰNG</div>

        <!-- SOCIAL -->
        <div class="social">
          <button>Google</button>
          <button>Facebook</button>
        </div>

        <p class="login">
          Bạn đã có tài khoản?
          <a @click="router.push('/login')">Đăng nhập ngay</a>
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
.box {
  width: 1000px;
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
  justify-content: space-between;
}

.left h1 {
  font-size: 28px;
}

.left span {
  color: #c4b5fd;
}

.left p {
  font-size: 13px;
  opacity: 0.85;
}

.left img {
  width: 100%;
  border-radius: 12px;
}

/* RIGHT */
.right {
  padding: 40px;
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
  margin-bottom: 12px;
}

.input-box input {
  border: none;
  background: transparent;
  outline: none;
  flex: 1;
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
  margin-top: 10px;
}

/* DIVIDER */
.divider {
  text-align: center;
  font-size: 12px;
  margin: 15px 0;
  color: #94a3b8;
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

/* LOGIN */
.login {
  text-align: center;
  font-size: 12px;
  margin-top: 15px;
}

.login a {
  color: #2563eb;
  cursor: pointer;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .box {
    grid-template-columns: 1fr;
    width: 95%;
  }

  .left {
    display: none;
  }
}

</style>