<script setup>
import { ref } from 'vue'

import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'

const product = ref({
    name: 'Precision Pro Gen 9 Elite',
    price: '45.990.000đ',
    oldPrice: '49.990.000đ',
    reviews: 48,
    cpu: 'Intel Core i9-14900HX',
    ram: '32GB LPDDR5x 7467MHz',
    storage: '1TB PCIe Gen4 NVMe SSD',
    gpu: 'RTX 4070 8GB GDDR6',
    colors: ['#1e3a8a', '#94a3b8', '#1d4ed8'],
    images: [
        'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
        'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=200',
        'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=200',
        'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=200'
    ]
})

const selectedImage = ref(product.value.images[0])

const related = [
    {
        name: 'Precision Air 14 Ultrabook',
        spec: 'Core i7 · 16GB RAM · 512GB SSD',
        price: '32.990.000đ',
        img: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300'
    },
    {
        name: 'Precision Studio 16 Pro',
        spec: 'Core i9 · 64GB RAM · 2TB SSD',
        price: '58.990.000đ',
        img: 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=300'
    },
    {
        name: 'Workstation Go X7',
        spec: 'Core i7 · 32GB RAM · 1TB SSD',
        price: '39.490.000đ',
        img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300'
    },
    {
        name: 'Precision Slim 13 Ultra',
        spec: 'Core i5 · 16GB RAM · 512GB SSD',
        price: '26.990.000đ',
        img: 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=300'
    }
]
</script>

<template>

<Header />

    <div class="page">

        <div class="container">

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                Trang chủ / Laptop / {{ product.name }}
            </div>

            <!-- ===== DETAIL ===== -->
            <div class="detail">

                <!-- LEFT -->
                <div>
                    <div class="main-img">
                        <img :src="selectedImage" />
                    </div>

                    <div class="thumbs">
                        <img v-for="(img, i) in product.images" :key="i" :src="img" @click="selectedImage = img"
                            :class="{ active: selectedImage === img }" />
                    </div>
                </div>

                <!-- RIGHT -->
                <div>
                    <span class="tag">MỚI NHẤT 2024</span>

                    <h1>{{ product.name }}</h1>

                    <div class="rating">
                        ⭐⭐⭐⭐⭐ ({{ product.reviews }} đánh giá)
                    </div>

                    <div class="price">{{ product.price }}</div>
                    <div class="old-price">{{ product.oldPrice }}</div>

                    <div class="specs">
                        <div class="spec"><span>Vi xử lý</span><b>{{ product.cpu }}</b></div>
                        <div class="spec"><span>RAM</span><b>{{ product.ram }}</b></div>
                        <div class="spec"><span>Lưu trữ</span><b>{{ product.storage }}</b></div>
                        <div class="spec"><span>Đồ họa</span><b>{{ product.gpu }}</b></div>
                    </div>

                    <div class="colors">
                        <p>Màu sắc: <b>Titan Grey</b></p>
                        <div class="color-list">
                            <span v-for="(c, i) in product.colors" :key="i" :style="{ background: c }"></span>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="add">🛒 Thêm vào giỏ hàng</button>
                        <button class="install">Trả góp 0%</button>
                    </div>

                    <div class="info">
                        <span>🚚 Giao nhanh 2h</span>
                        <span>🛡️ Bảo hành 24 tháng</span>
                    </div>
                </div>

            </div>

            <!-- ===== EXTRA ===== -->
            <div class="extra">

                <div class="tech">
                    <h2>Thông số kỹ thuật chi tiết</h2>
                    <p class="desc">
                        Laptop cao cấp dành cho dân kỹ thuật và sáng tạo nội dung.
                    </p>

                    <div class="spec-list">
                        <div><span>Màn hình</span><b>16" QHD+ 165Hz OLED</b></div>
                        <div><span>Cổng</span><b>Thunderbolt 4, HDMI 2.1</b></div>
                        <div><span>Pin</span><b>99.9Whr, 140W</b></div>
                        <div><span>Trọng lượng</span><b>1.85 kg</b></div>
                    </div>
                </div>

                <div class="product-desc">
                    <h3>Mô tả sản phẩm</h3>
                    <p>
                        Thiết kế nhôm CNC cao cấp, hiệu năng mạnh mẽ, tản nhiệt tối ưu.
                    </p>

                    <div class="features">
                        <div>
                            <h4>Tản nhiệt</h4>
                            <p>Giữ hiệu năng ổn định lâu dài</p>
                        </div>

                        <div>
                            <h4>Âm thanh</h4>
                            <p>Dolby Atmos sống động</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ===== REVIEWS ===== -->
            <div class="reviews">

                <div class="review-header">
                    <div>
                        <h2>Đánh giá từ người dùng</h2>
                        <p>Ý kiến thực tế từ khách hàng</p>
                    </div>
                    <button>Viết đánh giá</button>
                </div>

                <div class="review-list">
                    <div class="review-card">
                        <b>Nguyễn Hoàng</b>
                        <p class="role">Designer</p>
                        <p class="stars">★★★★★</p>
                        <p>"Màn OLED cực đẹp!"</p>
                    </div>

                    <div class="review-card">
                        <b>Trần Phan</b>
                        <p class="role">Dev</p>
                        <p class="stars">★★★★★</p>
                        <p>"Chạy code cực mượt."</p>
                    </div>

                    <div class="review-card">
                        <b>Lê Minh</b>
                        <p class="role">Business</p>
                        <p class="stars">★★★★★</p>
                        <p>"Pin trâu, ổn định."</p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ===== RELATED PRODUCTS ===== -->
    <div class="related">

        <div class="related-header">
            <h2>Sản phẩm tương tự</h2>
            <a href="#">Xem tất cả →</a>
        </div>

        <div class="related-list">
            <div class="product-card" v-for="(p, i) in related" :key="i">
                <div class="img-box">
                    <img :src="p.img" />
                </div>

                <h4>{{ p.name }}</h4>
                <p class="sub">{{ p.spec }}</p>
                <p class="price">{{ p.price }}</p>
            </div>
        </div>

    </div>

<Footer />

</template>

<style scoped>
.page {
    background: #f5f7fb;
}

/* ✅ CONTAINER CHUẨN */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 24px;
    /* 👈 thụt 2 bên */
    font-family: sans-serif;
}

/* ===== DETAIL ===== */
.detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}

.main-img {
    background: #eef2ff;
    padding: 30px;
    border-radius: 12px;
}

.main-img img {
    width: 100%;
}

.thumbs {
    display: flex;
    gap: 12px;
    margin-top: 15px;
}

.thumbs img {
    width: 90px;
    height: 70px;
    object-fit: cover;
    cursor: pointer;
    opacity: 0.7;
}

.thumbs img.active {
    border: 2px solid #2563eb;
    opacity: 1;
}

/* RIGHT */
.tag {
    background: #e2e8f0;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
}

h1 {
    margin: 10px 0;
}

.price {
    font-size: 24px;
    color: #2563eb;
    font-weight: bold;
}

.old-price {
    text-decoration: line-through;
    color: #94a3b8;
}

.specs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin: 20px 0;
}

.spec {
    background: #f1f5f9;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
}

.colors {
    margin-bottom: 20px;
}

.color-list {
    display: flex;
    gap: 10px;
}

.color-list span {
    width: 24px;
    height: 24px;
    border-radius: 6px;
}

/* BUTTON */
.actions {
    display: flex;
    gap: 10px;
}

.add {
    flex: 1;
    background: #2563eb;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
}

.install {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

/* ===== EXTRA ===== */
.extra {
    margin-top: 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.spec-list div {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 0;
}

.product-desc {
    background: #eef2ff;
    padding: 20px;
    border-radius: 12px;
}

.features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* ===== REVIEWS ===== */
.reviews {
    margin-top: 60px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    align-items: center;
}

.review-header button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 8px 16px;
    height: auto;
    width: auto;
    display: inline-block;
    border-radius: 6px;
    align-self: center;
}

.review-header button:hover {
    background: #1e40af;
}

.review-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.review-card {
    background: #dadbdc;
    padding: 15px;
    border-radius: 10px;
}

.stars {
    color: orange;
}

/* ===== RELATED ===== */
.related {
    margin-top: 60px;
}

.related-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.related-header h2 {
    font-size: 20px;
}

.related-header a {
    font-size: 13px;
    color: #2563eb;
    text-decoration: none;
}

/* LIST */
.related-list {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* CARD */
.product-card {
    background: #eef2ff;
    padding: 15px;
    border-radius: 12px;
    transition: 0.25s;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
}

.img-box {
    background: #c7d2fe;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.img-box img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.product-card h4 {
    font-size: 14px;
    margin-bottom: 4px;
}

.sub {
    font-size: 12px;
    color: #64748b;
}

.price {
    font-weight: bold;
    margin-top: 6px;
}

.related {
    padding: 0 150px;
    margin-bottom: 60px;
}

/* RESPONSIVE */
@media (max-width: 768px) {

    .detail,
    .extra,
    .review-list {
        grid-template-columns: 1fr;
    }
}
</style>