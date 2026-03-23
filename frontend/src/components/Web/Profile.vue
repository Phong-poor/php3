<script setup>
import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'
import { ref } from 'vue'

const form = ref({
  name: 'Nguyễn Văn A',
  email: 'nguyenvana@gmail.com',
  phone: '0987 654 321',
  dob: '1996-12-30',
  gender: 'male'
})
</script>

<template>
  <Header />

  <div class="page">

    <div class="container">

      <!-- SIDEBAR -->
      <div class="sidebar">

        <div class="user">
          <img src="https://i.pravatar.cc/100" />
          <h4>Nguyễn Văn A</h4>
          <span>THÀNH VIÊN VÀNG</span>
        </div>

        <div class="menu">
          <div class="item active">👤 Thông tin cá nhân</div>
          <div class="item">📦 Đơn hàng của tôi</div>
          <div class="item">📍 Sổ địa chỉ</div>
          <div class="item">🔒 Đổi mật khẩu</div>
        </div>

        <div class="logout">⛔ Đăng xuất</div>
      </div>

      <!-- CONTENT -->
      <div class="content">

        <!-- TOP STATS -->
        <div class="top">

          <div class="card level">
            <p>Hạng thành viên hiện tại</p>
            <h2>Vàng</h2>
            <div class="progress">
              <div class="bar"></div>
            </div>
            <span>Còn 2.500 điểm để lên hạng Kim cương</span>
          </div>

          <div class="card point">
            <p>Điểm tích lũy</p>
            <h2>7.850</h2>
            <span>Tương đương với 785.000 VNĐ</span>
            <a href="#">Đổi quà ngay →</a>
          </div>

        </div>

        <!-- FORM -->
        <div class="form-box">

          <div class="form-head">
            <h3>Thông tin cá nhân</h3>
            <button>Lưu thay đổi</button>
          </div>

          <p class="desc">
            Cập nhật thông tin của bạn để có trải nghiệm tốt nhất tại VinaTech.
          </p>

          <div class="grid">
            <div>
              <label>Họ và tên</label>
              <input v-model="form.name" />
            </div>

            <div>
              <label>Email</label>
              <input v-model="form.email" />
            </div>

            <div>
              <label>Số điện thoại</label>
              <input v-model="form.phone" />
            </div>

            <div>
              <label>Ngày sinh</label>
              <input type="date" v-model="form.dob" />
            </div>
          </div>

          <!-- GENDER -->
          <div class="gender">
            <label>Giới tính</label>

            <div class="gender-options">

              <div
                :class="['g-item', form.gender==='male' && 'active']"
                @click="form.gender='male'"
              >
                Nam
              </div>

              <div
                :class="['g-item', form.gender==='female' && 'active']"
                @click="form.gender='female'"
              >
                Nữ
              </div>

              <div
                :class="['g-item', form.gender==='other' && 'active']"
                @click="form.gender='other'"
              >
                Khác
              </div>

            </div>
          </div>

        </div>

        <!-- SECURITY -->
        <div class="security">
          <div>
            <h4>Tăng cường bảo mật</h4>
            <p>
              Chúng tôi khuyến khích bạn đổi mật khẩu định kỳ để bảo vệ tài khoản.
            </p>
          </div>

          <button>Cập nhật bảo mật</button>
        </div>

      </div>

    </div>

  </div>

  <Footer />
</template>

<style scoped>

.page {
  background: #f4f7fb;
  min-height: 100vh;
}

.container {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 24px;
  padding: 40px 120px;
}

/* SIDEBAR */
.sidebar {
  background: #eef2ff;
  padding: 20px;
  border-radius: 20px;
}

.user {
  text-align: center;
}

.user img {
  width: 70px;
  border-radius: 50%;
}

.user h4 {
  margin-top: 10px;
}

.user span {
  font-size: 12px;
  color: #2563eb;
}

.menu {
  margin-top: 20px;
}

.item {
  padding: 10px;
  border-radius: 10px;
  margin-bottom: 6px;
  cursor: pointer;
}

.item.active {
  background: linear-gradient(90deg,#2563eb,#7c3aed);
  color: white;
}

.logout {
  margin-top: 20px;
  color: red;
  cursor: pointer;
}

/* CONTENT */
.top {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 16px;
}

.progress {
  height: 6px;
  background: #e5e7eb;
  border-radius: 10px;
  margin: 10px 0;
}

.bar {
  width: 70%;
  height: 100%;
  background: linear-gradient(90deg,#7c3aed,#2563eb);
  border-radius: 10px;
}

/* FORM */
.form-box {
  background: white;
  margin-top: 20px;
  padding: 24px;
  border-radius: 20px;
}

.form-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.form-head button {
  background: #2563eb;
  color: white;
  border-radius: 20px;
  padding: 8px 16px;
  border: none;
}

.desc {
  color: #64748b;
  margin: 10px 0 20px;
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

label {
  font-size: 12px;
  color: #64748b;
}

input {
  width: 100%;
  padding: 10px;
  border-radius: 10px;
  border: none;
  background: #f1f5f9;
  margin-top: 4px;
}

/* GENDER */
.gender {
  margin-top: 20px;
}

.gender-options {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.g-item {
  flex: 1;
  text-align: center;
  padding: 10px;
  border-radius: 20px;
  background: #f1f5f9;
  cursor: pointer;
}

.g-item.active {
  border: 2px solid #2563eb;
  background: white;
  color: #2563eb;
}

/* SECURITY */
.security {
  margin-top: 20px;
  padding: 20px;
  border-radius: 20px;
  background: linear-gradient(90deg,#ede9fe,#e0e7ff);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.security button {
  background: white;
  border-radius: 20px;
  padding: 8px 16px;
  border: none;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .container {
    grid-template-columns: 1fr;
    padding: 20px;
  }

  .top {
    grid-template-columns: 1fr;
  }
}

</style>