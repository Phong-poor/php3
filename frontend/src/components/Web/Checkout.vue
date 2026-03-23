<script setup>
import { ref, computed } from 'vue'

import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'

const form = ref({
  name: '',
  phone: '',
  email: '',
  address: ''
})

const payment = ref('cod')

const cart = ref([
  {
    name: 'Precision Workstation X1',
    desc: '32GB RAM / 1TB SSD',
    price: 45990000,
    qty: 1,
    img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
  },
  {
    name: 'Mechanical Key Studio',
    desc: 'Tactile Switches / RGB',
    price: 3250000,
    qty: 1,
    img: 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=200'
  }
])

const subtotal = computed(() =>
  cart.value.reduce((sum, i) => sum + i.price * i.qty, 0)
)

const tax = computed(() => subtotal.value * 0.1)
const total = computed(() => subtotal.value + tax.value)

const format = (n) => n.toLocaleString('vi-VN') + 'đ'
</script>

<template>

<Header />

  <div class="checkout-page">
    <div class="container">

      <!-- LEFT -->
      <div class="left">
        <h1>Thanh toán</h1>
        <p class="subtitle">
          Hoàn tất đơn đặt hàng của bạn với sự chính xác tuyệt đối.
        </p>

        <!-- INFO -->
        <div class="box">
          <div class="box-title">
            <span class="step">1</span>
            Thông tin người nhận
          </div>

          <div class="form-grid">
            <input v-model="form.name" placeholder="Họ và tên" />
            <input v-model="form.phone" placeholder="Số điện thoại" />
          </div>

          <input v-model="form.email" placeholder="Email" />

          <textarea
            v-model="form.address"
            placeholder="Địa chỉ nhận hàng"
          ></textarea>
        </div>

        <!-- PAYMENT -->
        <div class="box">
          <div class="box-title">
            <span class="step">2</span>
            Phương thức thanh toán
          </div>

          <div class="pay-list">

            <label class="pay-item" :class="{ active: payment === 'cod' }">
              <input type="radio" value="cod" v-model="payment" />
              <div class="radio"></div>
              <div class="pay-text">
                <b>COD (Thanh toán khi nhận hàng)</b>
                <p>Thanh toán tiền mặt khi nhận hàng</p>
              </div>
            </label>

            <label class="pay-item" :class="{ active: payment === 'bank' }">
              <input type="radio" value="bank" v-model="payment" />
              <div class="radio"></div>
              <div class="pay-text">
                <b>Chuyển khoản ngân hàng</b>
                <p>Nhận thông tin tài khoản sau khi đặt</p>
              </div>
            </label>

            <label class="pay-item" :class="{ active: payment === 'momo' }">
              <input type="radio" value="momo" v-model="payment" />
              <div class="radio"></div>
              <div class="pay-text">
                <b>Ví điện tử (Momo / ZaloPay)</b>
                <p>Thanh toán nhanh qua ví điện tử</p>
              </div>
            </label>

          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <div class="right">
        <div class="summary">
          <h3>Tóm tắt đơn hàng</h3>

          <div class="item" v-for="(i, index) in cart" :key="index">
            <img :src="i.img" />
            <div>
              <p>{{ i.name }}</p>
              <span>{{ i.desc }}</span>
            </div>
            <b>{{ format(i.price) }}</b>
          </div>

          <div class="line"></div>

          <div class="row">
            <span>Tạm tính</span>
            <b>{{ format(subtotal) }}</b>
          </div>

          <div class="row">
            <span>Phí vận chuyển</span>
            <b>0đ</b>
          </div>

          <div class="row">
            <span>Thuế VAT (10%)</span>
            <b>{{ format(tax) }}</b>
          </div>

          <div class="total">
            <span>TỔNG CỘNG</span>
            <b>{{ format(total) }}</b>
          </div>

          <button class="btn">
            Xác nhận đặt hàng
          </button>

          <p class="secure">🔒 Giao dịch được bảo mật 256-bit</p>
        </div>

        <div class="coupon">
          <span>🏷️ Mã giảm giá</span>
          <p>Áp dụng mã TECH2024 để nhận ưu đãi</p>
        </div>
      </div>

    </div>
  </div>

<Footer />

</template>

<style scoped>
.checkout-page {
  background: #f5f7fb;
  min-height: 100vh;
}

/* CONTAINER */
.container {
  max-width: 1200px;
  margin: auto;
  padding: 40px 24px;
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
}

/* TEXT */
.subtitle {
  color: #64748b;
  margin-bottom: 20px;
}

/* BOX */
.box {
  background: white;
  padding: 24px;
  border-radius: 14px;
  margin-bottom: 20px;
}

.box-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  margin-bottom: 16px;
}

.step {
  width: 28px;
  height: 28px;
  background: #2563eb;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* FORM */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 12px;
}

/* INPUT */
input {
  width: 100%;
  height: 44px;
  padding: 0 14px;
  border: none;
  background: #f1f5f9;
  border-radius: 10px;
  font-size: 14px;
  box-sizing: border-box;
  margin-bottom: 12px;
}

/* TEXTAREA TÁCH RIÊNG */
textarea {
  width: 100%;
  height: 120px;
  padding: 12px 14px;
  border: none;
  background: #f1f5f9;
  border-radius: 10px;
  font-size: 14px;
  box-sizing: border-box;

  margin-top: 10px;    
  resize: none;
}

textarea {
  height: 120px;
  padding-top: 12px;
  resize: none;
}

/* PAYMENT */
.pay-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.pay-item {
  display: grid;
  grid-template-columns: 28px 1fr;
  align-items: center;
  gap: 14px;
  padding: 16px;
  border-radius: 12px;
  background: #f8fafc;
  cursor: pointer;
}

.pay-item.active {
  background: #e0ecff;
  border: 1px solid #2563eb;
}

.pay-item input {
  display: none;
}

.radio {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  border: 2px solid #94a3b8;
  position: relative;
}

.pay-item.active .radio {
  border-color: #2563eb;
}

.pay-item.active .radio::after {
  content: '';
  width: 12px;
  height: 12px;
  background: #2563eb;
  border-radius: 50%;
  position: absolute;
  top: 3px;
  left: 3px;
}

.pay-text p {
  font-size: 12px;
  color: #64748b;
}

/* RIGHT */
.summary {
  background: #eef2ff;
  padding: 20px;
  border-radius: 12px;
}

.item {
  display: flex;
  gap: 10px;
  align-items: center;
  margin-bottom: 10px;
}

.item img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

.line {
  height: 1px;
  background: #cbd5e1;
  margin: 10px 0;
}

.row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.total {
  margin-top: 10px;
  font-size: 18px;
  font-weight: bold;
}

.btn {
  margin-top: 15px;
  width: 100%;
  padding: 12px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 8px;
}

.secure {
  text-align: center;
  font-size: 12px;
  color: #64748b;
  margin-top: 10px;
}

/* COUPON */
.coupon {
  margin-top: 15px;
  background: #e0e7ff;
  padding: 15px;
  border-radius: 10px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .container {
    grid-template-columns: 1fr;
  }
}
</style>