<script setup>
import { ref, computed } from 'vue'
import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'

const cart = ref([
    {
        name: 'MacBook Pro 14" M3 Pro',
        desc: '12-CORE CPU · 18-CORE GPU · 18GB RAM',
        price: 49990000,
        qty: 1,
        img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
    },
    {
        name: 'iPhone 15 Pro Max',
        desc: 'Titanium · 256GB · Blue',
        price: 34490000,
        qty: 1,
        img: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200'
    }
])

const subtotal = computed(() =>
    cart.value.reduce((sum, item) => sum + item.price * item.qty, 0)
)

const discount = 3000000
const shipping = 0

const total = computed(() => subtotal.value - discount + shipping)

const increase = (item) => item.qty++
const decrease = (item) => item.qty > 1 && item.qty--
const removeItem = (index) => cart.value.splice(index, 1)
</script>

<template>
    <Header />

    <div class="page">
        <div class="container cart">

            <!-- LEFT -->
            <div class="left">
                <h1>Giỏ hàng <span>({{ cart.length }})</span></h1>
                <p class="sub">Sản phẩm bạn đã chọn</p>

                <transition-group name="fade" tag="div">
                    <div class="item" v-for="(item, i) in cart" :key="item.name">

                        <img :src="item.img" />

                        <div class="info">
                            <h3>{{ item.name }}</h3>
                            <p>{{ item.desc }}</p>

                            <div class="qty">
                                <button @click="decrease(item)">−</button>
                                <span>{{ item.qty }}</span>
                                <button @click="increase(item)">+</button>
                            </div>
                        </div>

                        <div class="price">
                            {{ item.price.toLocaleString() }}đ
                        </div>

                        <button class="remove" @click="removeItem(i)">×</button>

                    </div>
                </transition-group>

            </div>

            <!-- RIGHT -->
            <div class="right">

                <h3>Tổng thanh toán</h3>

                <div class="row">
                    <span>Tạm tính</span>
                    <b>{{ subtotal.toLocaleString() }}đ</b>
                </div>

                <div class="row">
                    <span>Giảm giá</span>
                    <b class="discount">-{{ discount.toLocaleString() }}đ</b>
                </div>

                <div class="row">
                    <span>Vận chuyển</span>
                    <b class="free">Miễn phí</b>
                </div>

                <div class="coupon">
                    <input placeholder="Nhập mã giảm giá..." />
                    <button>Áp dụng</button>
                </div>

                <div class="total">
                    <span>Tổng</span>
                    <h2>{{ total.toLocaleString() }}đ</h2>
                </div>

                <router-link to="/checkout" class="checkout">
                    <span>Thanh toán ngay</span>
                    <span class="icon">→</span>
                </router-link>

            </div>

        </div>

        <!-- SUGGEST -->
        <div class="suggest container">
            <h3>Gợi ý cho bạn</h3>

            <div class="grid">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=200" />
                    <p>iPad Air M2</p>
                    <span>16.990.000đ</span>
                </div>

                <div class="card">
                    <img src="https://images.unsplash.com/photo-1585386959984-a4155223163c?w=200" />
                    <p>AirPods Max</p>
                    <span>12.490.000đ</span>
                </div>

                <div class="card">
                    <img src="https://images.unsplash.com/photo-1580910051074-3eb694886505?w=200" />
                    <p>MagSafe Charger</p>
                    <span>1.190.000đ</span>
                </div>
            </div>
        </div>

    </div>

    <Footer />
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');

.page {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(180deg, #f8fafc, #eef2ff);
    padding: 40px 0;
}

.checkout {
    width: 91% !important; 
    margin-top: 20px;
    padding: 15px;
    border-radius: 14px;

    position: relative;          /* quan trọng */
    display: flex;
    justify-content: center;
    align-items: center;

    background: linear-gradient(90deg, #2563eb, #4f46e5);
    color: white;
    font-weight: 500;
    font-size: 15px;
    text-decoration: none;
}

/* icon luôn nằm bên phải */
.checkout .icon {
    position: absolute;
    right: 20px;
}.checkout {
    width: 100%;
    margin-top: 20px;

    padding: 15px;
    border-radius: 14px;

    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    /* khoảng cách text - icon */

    background: linear-gradient(90deg, #2563eb, #4f46e5);
    color: white;
    font-weight: 500;
    font-size: 15px;

    text-decoration: none;
}

.checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
}

.container {
    width: min(1100px, 95%);
    margin: auto;
}

/* LAYOUT */
.cart {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

/* LEFT */
.item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-radius: 18px;
    margin-bottom: 14px;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    transition: 0.3s;
}

.item:hover {
    transform: translateY(-5px);
}

.item img {
    width: 90px;
    border-radius: 12px;
}

.info {
    flex: 1;
}

.info p {
    font-size: 12px;
    color: #64748b;
}

.qty {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}

.qty button {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: none;
    background: #e0e7ff;
}

.price {
    font-weight: bold;
    color: #2563eb;
}

.remove {
    border: none;
    background: #fee2e2;
    color: red;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

/* RIGHT */
.right {
    padding: 24px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
}

.row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.discount {
    color: red;
}

.free {
    color: green;
}

.coupon {
    display: flex;
    margin: 16px 0;
}

.coupon input {
    flex: 1;
    padding: 10px;
    border-radius: 10px 0 0 10px;
    border: 1px solid #ddd;
}

.coupon button {
    padding: 10px;
    border: none;
    background: #6366f1;
    color: white;
    border-radius: 0 10px 10px 0;
}

.total {
    margin-top: 10px;
}

.total h2 {
    color: #2563eb;
}

.checkout {
    width: 100%;
    margin-top: 16px;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(90deg, #2563eb, #4f46e5);
    color: white;
    font-weight: 600;
}

/* SUGGEST */
.suggest {
    margin-top: 50px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;

}

.card {
    background: rgba(255, 255, 255, 0.7);
    padding: 16px;
    border-radius: 16px;
    text-align: center;
}

.card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
}

.card span {
    color: #2563eb;
    font-weight: bold;
}

/* ANIMATION */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.4s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateX(20px);
}
</style>