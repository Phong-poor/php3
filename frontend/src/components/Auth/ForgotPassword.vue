<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const router = useRouter()

const handleSubmit = () => {
  if (!email.value) {
    alert('Vui lòng nhập email')
    return
  }

  alert('Đã gửi hướng dẫn đặt lại mật khẩu!')
}
</script>

<template>
  <div class="page">

    <div class="card">

      <h3 class="brand">NextGen Laptop</h3>

      <div class="icon">🔄</div>

      <h2>Quên mật khẩu?</h2>
      <p class="desc">
        Nhập email của bạn để nhận hướng dẫn khôi phục mật khẩu
      </p>

      <!-- INPUT -->
      <div class="input-box">
        <span>@</span>
        <input v-model="email" placeholder="example@vinatech.com" />
      </div>

      <!-- BUTTON -->
      <button class="btn" @click="handleSubmit">
        Gửi yêu cầu →
      </button>

      <div class="divider"></div>

      <!-- BACK -->
      <p class="back" @click="router.push('/login')">
        ← Quay lại đăng nhập
      </p>

    </div>

    <p class="footer">
      © 2026 VinaTech Premium. Hệ thống bảo mật 2026.
    </p>

  </div>
</template>

<style scoped>

/* PAGE */
.page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg,#eef2ff,#f1f5f9);
  font-family: 'Inter', sans-serif;
}

/* CARD */
.card {
  width: 380px;
  padding: 40px;
  border-radius: 24px;
  background: white;
  text-align: center;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}

/* BRAND */
.brand {
  color: #2563eb;
  margin-bottom: 10px;
}

/* ICON */
.icon {
  width: 50px;
  height: 50px;
  margin: 10px auto;
  background: #e0ecff;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* TEXT */
.desc {
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
  margin-bottom: 15px;
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
}

/* DIVIDER */
.divider {
  height: 1px;
  background: #e5e7eb;
  margin: 20px 0;
}

/* BACK */
.back {
  font-size: 13px;
  color: #2563eb;
  cursor: pointer;
}

/* FOOTER */
.footer {
  margin-top: 20px;
  font-size: 11px;
  color: #94a3b8;
}

</style>