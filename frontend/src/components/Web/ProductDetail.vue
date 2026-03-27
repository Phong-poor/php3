<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api' // Gọi API bằng file config của bạn

import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'

const route = useRoute()
const isLoading = ref(true)

// Khởi tạo product với cấu trúc sẵn để không bị lỗi lúc chưa load xong
const product = ref({
    tenSP: 'Đang tải...',
    gia: 0,
    SKU: '',
    hinhanh: '',
    hinhAnhs: [],
    bienThes: []
})

const selectedImage = ref('https://via.placeholder.com/600')
const selectedVariant = ref(null)
const selectedOptions = ref({})
const getVariantAttributes = (variant) => {
    return variant?.thuoc_tinh || variant?.attributes || []
}

const getAttrValue = (variant, attrName) => {
    const attrs = getVariantAttributes(variant)
    const found = attrs.find(item => item.ten_thuoctinh === attrName)
    return found ? found.giatri : null
}

const getColorCode = (variant) => {
    const attrs = getVariantAttributes(variant)
    const found = attrs.find(item => item.ten_thuoctinh === 'Màu sắc')
    return found?.ma_mau || '#ccc'
}
const variantGroups = computed(() => {
    const variants = product.value.bienThes || []
    const map = new Map()

    variants.forEach(variant => {
        const attrs = getVariantAttributes(variant)

        attrs.forEach(attr => {
            const name = attr.ten_thuoctinh
            if (!map.has(name)) map.set(name, [])

            const exists = map.get(name).some(v => v.giatri === attr.giatri)
            if (!exists) {
                map.get(name).push({
                    giatri: attr.giatri,
                    ma_mau: attr.ma_mau || null
                })
            }
        })
    })

    const groups = Array.from(map.entries()).map(([name, values]) => ({
        name,
        values
    }))

    groups.sort((a, b) => {
        if (a.name === 'Màu sắc') return -1
        if (b.name === 'Màu sắc') return 1
        return 0
    })

    return groups
})
const findMatchingVariant = () => {
    const variants = product.value.bienThes || []

    return variants.find(variant => {
        const attrs = getVariantAttributes(variant)

        return Object.entries(selectedOptions.value).every(([attrName, attrValue]) => {
            const found = attrs.find(item => item.ten_thuoctinh === attrName)
            return found && found.giatri === attrValue
        })
    }) || null
}
const handleSelectOption = (groupName, value) => {
    selectedOptions.value = {
        ...selectedOptions.value,
        [groupName]: value
    }

    const matched = findMatchingVariant()
    if (matched) {
        selectedVariant.value = matched
    }
}
// Hàm Format Tiền
const formatPrice = (price) => {
    if (!price) return '0đ'
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ'
}

// Hàm xử lý đường dẫn ảnh
const getImageUrl = (path) => {
    if (!path) return 'https://via.placeholder.com/600'
    if (path.startsWith('http') || path.startsWith('data:image')) return path
    return `http://127.0.0.1:8000/storage/${path}`
}

// Gom ảnh bìa và ảnh phụ
const allImages = computed(() => {
    if (!product.value) return []
    let images = []
    if (product.value.hinhanh) images.push(getImageUrl(product.value.hinhanh))
    
    const listHinhAnh = product.value.hinh_anhs || product.value.hinhAnhs || []
    listHinhAnh.forEach(img => {
        images.push(getImageUrl(img.duongdan))
    })
    
    return images.length > 0 ? images : ['https://via.placeholder.com/600']
})

// GỌI API LẤY DỮ LIỆU
const fetchProductDetail = async () => {
    try {
        isLoading.value = true
        const productId = route.params.id || 1 // Lấy ID từ URL
        
        const response = await api.get(`/sanpham/${productId}`)
        
        // Chuẩn hóa dữ liệu
        const data = response.data
        const variants = data.bien_thes || data.bienThes || []
        
        product.value = { ...data, bienThes: variants }

        if (allImages.value.length > 0) {
            selectedImage.value = allImages.value[0]
        }

        // MẶC ĐỊNH: Chọn nút đầu tiên
        if (variants.length > 0) {
            const firstVariant = variants[0]
            selectedVariant.value = firstVariant

            const attrs = getVariantAttributes(firstVariant)
            const defaultOptions = {}

            attrs.forEach(attr => {
                defaultOptions[attr.ten_thuoctinh] = attr.giatri
            })

            selectedOptions.value = defaultOptions
        }

    } catch (error) {
        console.error("Lỗi khi tải chi tiết sản phẩm:", error)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    fetchProductDetail()
    
})

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

            <div v-if="isLoading" style="text-align: center; padding: 50px;">
                Đang tải dữ liệu sản phẩm...
            </div>

            <div v-else>
                <div class="breadcrumb">
                    Trang chủ / Laptop / {{ product.tenSP }}
                </div>

                <div class="detail">

                    <div>
                        <div class="main-img">
                            <img :src="selectedImage" />
                        </div>

                        <div class="thumbs">
                            <img v-for="(img, i) in allImages" :key="i" :src="img" @click="selectedImage = img"
                                :class="{ active: selectedImage === img }" />
                        </div>
                    </div>

                    <div>
                        <span class="tag" v-if="product.thuong_hieu">{{ product.thuong_hieu.ten_thuonghieu }}</span>
                        <span class="tag" v-else>MỚI NHẤT 2026</span>

                        <h1>{{ product.tenSP }}</h1>

                        <div class="rating">
                            ⭐⭐⭐⭐⭐ (Chưa có đánh giá)
                        </div>

                        <div class="price">
                            {{ selectedVariant ? formatPrice(selectedVariant.gia) : formatPrice(product.gia) }}
                        </div>
                        <div class="variant-stock" v-if="selectedVariant">
                            <span v-if="selectedVariant.soluong > 0" class="in-stock">
                                Còn hàng: {{ selectedVariant.soluong }} sản phẩm
                            </span>
                            <span v-else class="out-stock">
                                Hết hàng
                            </span>
                        </div>
                        <div class="product-options" v-if="product.bienThes && product.bienThes.length > 0">
                            <div
                                class="option-group"
                                v-for="group in variantGroups"
                                :key="group.name"
                            >
                                <p class="option-label">{{ group.name }}:</p>

                                <!-- MÀU SẮC -->
                                <div v-if="group.name === 'Màu sắc'" class="color-list">
                                    <button
                                        v-for="item in group.values"
                                        :key="item.giatri"
                                        class="color-btn"
                                        :class="{ active: selectedOptions[group.name] === item.giatri }"
                                        @click="handleSelectOption(group.name, item.giatri)"
                                        :title="item.giatri"
                                    >
                                        <span
                                            class="color-dot"
                                            :style="{ backgroundColor: item.ma_mau || '#ccc' }"
                                        ></span>
                                    </button>
                                </div>

                                <!-- CÁC THUỘC TÍNH KHÁC -->
                                <div v-else class="variant-grid">
                                    <button
                                        v-for="item in group.values"
                                        :key="item.giatri"
                                        class="variant-item-btn"
                                        :class="{ active: selectedOptions[group.name] === item.giatri }"
                                        @click="handleSelectOption(group.name, item.giatri)"
                                    >
                                        {{ item.giatri }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-options" v-else>
                            <p style="color: #666; font-size: 14px;">Sản phẩm này đang được cập nhật tùy chọn.</p>
                        </div>
                        <div class="actions">
                            <button 
                                class="add"
                                :disabled="!selectedVariant || selectedVariant.soluong === 0"
                                :class="{ 'disabled-btn': !selectedVariant || selectedVariant.soluong === 0 }"
                            >
                                🛒 Thêm vào giỏ hàng
                            </button>
                            <button class="install">Trả góp 0%</button>
                        </div>

                        <div class="info">
                            <span>🚚 Giao nhanh 2h</span>
                            <span>🛡️ Bảo hành 24 tháng</span>
                        </div>
                    </div>

                </div>


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
    </div>

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
    margin-bottom: 20px;
}

.old-price {
    text-decoration: line-through;
    color: #94a3b8;
}

/* 👉 CSS PHẦN BIẾN THỂ (Thêm mới) */
.product-options {
    margin-bottom: 25px;
}

.option-group {
    display: flex;
    flex-direction: column;
}

.option-label {
    margin: 0 0 5px;
    font-size: 14px;
    color: #334155;
    font-weight: bold;
}

.variant-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 5px;
}

.variant-item-btn {
    padding: 8px 16px;
    border: 1px solid #cbd5e1;
    background: #fff;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
    color: #334155;
}

.variant-item-btn:hover:not(.disabled) {
    border-color: #2563eb;
    color: #2563eb;
}

.variant-item-btn.active {
    border-color: #2563eb;
    background-color: #eff6ff;
    color: #2563eb;
    font-weight: bold;
    box-shadow: 0 0 0 1px #2563eb;
}

.variant-item-btn.disabled {
    background: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
    text-decoration: line-through;
    border-style: dashed;
}

.stock-status {
    margin-top: 10px;
    font-size: 13px;
}
.in-stock { color: #16a34a; }
.out-stock { color: #dc2626; font-weight: bold;}
.disabled-btn { opacity: 0.5; cursor: not-allowed !important; }
/* 👉 KẾT THÚC CSS BIẾN THỂ */

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
    cursor: pointer;
    transition: 0.2s;
}
.add:hover:not(.disabled-btn) { background: #1d4ed8; }

.install {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    background: white;
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
    cursor: pointer;
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
.color-list {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.color-btn {
    width: 38px;
    height: 38px;
    border-radius: 999px;
    border: 2px solid transparent;
    background: transparent;
    padding: 3px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.color-btn.active {
    border-color: #2563eb;
    box-shadow: 0 0 0 1px #2563eb;
}

.color-dot {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 999px;
    border: 1px solid #cbd5e1;
}
.variant-stock {
    margin-top: -10px;
    margin-bottom: 18px;
    font-size: 14px;
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