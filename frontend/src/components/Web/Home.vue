<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'
import GiftPopup from './GiftPopup.vue'
import api from '../../services/api'

// ── Popup ──────────────────────────────────────────────────
const showGift = ref(false)


// ── Slides ─────────────────────────────────────────────────
const slides = [
    {
        eyebrow: 'PREMIUM LAPTOP STORE 2026',
        title: 'Laptop cao cấp cho',
        highlight: 'mọi nhu cầu chuyên sâu',
        desc: 'Từ gaming, văn phòng đến đồ hoạ chuyên nghiệp. Chọn đúng cấu hình, đúng trải nghiệm, đúng đẳng cấp.',
        img: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1200',
        primary: 'Mua ngay',
        secondary: 'Xem bộ sưu tập'
    },
    {
        eyebrow: 'NEW GENERATION DEVICES',
        title: 'Hiệu năng mạnh mẽ',
        highlight: 'thiết kế dẫn đầu',
        desc: 'Sở hữu các dòng laptop mới nhất với màn hình sắc nét, pin bền bỉ và hiệu suất vượt trội.',
        img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=1200',
        primary: 'Khám phá ngay',
        secondary: 'Tư vấn cấu hình'
    },
    {
        eyebrow: 'GAMING • CREATOR • BUSINESS',
        title: 'Công nghệ mới cho',
        highlight: 'trải nghiệm không giới hạn',
        desc: 'Cân mọi tác vụ từ chơi game AAA, dựng video 4K đến làm việc doanh nghiệp với độ ổn định tối ưu.',
        img: 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?w=1200',
        primary: 'Xem ưu đãi',
        secondary: 'So sánh sản phẩm'
    }
]

const categories = [
    { name: 'Gaming', desc: 'RTX mạnh mẽ, tản nhiệt tối ưu', icon: '🎮' },
    { name: 'Văn phòng', desc: 'Mỏng nhẹ, pin lâu, bền bỉ', icon: '💼' },
    { name: 'Đồ hoạ', desc: 'Màn đẹp, render nhanh, chuẩn màu', icon: '🎨' },
    { name: 'Doanh nhân', desc: 'Thiết kế cao cấp, bảo mật tốt', icon: '👔' }
]

const featuredProducts = ref([])

onMounted(async () => {
    // Hiện popup sau 10 giây
    setTimeout(() => {
        showGift.value = true
    }, 10000)

    try {
        const response = await api.get('/sanpham')
        featuredProducts.value = response.data.map(p => ({
            name: p.tenSP,
            category: p.danh_muc?.ten_danhmuc || 'Ultrabook',
            price: new Intl.NumberFormat('vi-VN').format(p.bien_thes?.[0]?.gia || 0) + 'đ',
            old: '', 
            img: p.hinhanh ? 'http://127.0.0.1:8000/storage/' + p.hinhanh : '',
            badge: p.trangthai === 'Hot' ? 'HOT SALE' : '',
            specs: [
                p.thuong_hieu?.ten_thuonghieu,
                p.khoiluong ? `${p.khoiluong}kg` : ''
            ].filter(s => s)
        }))
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error)
    }
})

const benefits = [
    { icon: '✔️', title: '100% chính hãng', desc: 'Cam kết sản phẩm mới, nguyên seal, đầy đủ chứng từ.' },
    { icon: '🛡️', title: 'Bảo hành toàn diện', desc: 'Hỗ trợ bảo hành nhanh, chính sách đổi trả rõ ràng.' },
    { icon: '💳', title: 'Trả góp linh hoạt', desc: 'Trả góp 0%, hồ sơ đơn giản, duyệt nhanh chóng.' },
    { icon: '🚚', title: 'Giao hàng toàn quốc', desc: 'Đóng gói an toàn, giao nhanh, hỗ trợ kiểm tra hàng.' }
]

const news = [
    {
        title: 'Top laptop gaming đáng mua nhất năm 2026',
        desc: 'Những lựa chọn cân bằng giữa hiệu năng, nhiệt độ và giá bán cho game thủ hiện đại.',
        img: 'https://images.unsplash.com/photo-1581092335397-9583eb92d232?w=800',
        tag: 'ĐÁNH GIÁ'
    },
    {
        title: 'Laptop AI mới thay đổi trải nghiệm làm việc như thế nào?',
        desc: 'Khám phá các tính năng AI hỗ trợ xử lý văn bản, hình ảnh và tối ưu hiệu suất hệ thống.',
        img: 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800',
        tag: 'TIN TỨC'
    },
    {
        title: 'Cách chọn laptop đồ hoạ phù hợp cho designer',
        desc: 'Từ màn hình, CPU, GPU đến RAM và tản nhiệt, đây là những yếu tố bạn không nên bỏ qua.',
        img: 'https://images.unsplash.com/photo-1492724441997-5dc865305da7?w=800',
        tag: 'HƯỚNG DẪN'
    }
]

const reviews = [
    {
        name: 'Trần Minh Quân', role: 'Creative Designer',
        content: 'Website đẹp, mua hàng dễ, tư vấn đúng nhu cầu. Máy nhận được đúng như mong đợi.',
        avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
    },
    {
        name: 'Nguyễn Phương Anh', role: 'Marketing Manager',
        content: 'Mình rất thích cách trình bày sản phẩm và trải nghiệm đặt hàng. Nhìn cực kỳ cao cấp.',
        avatar: 'https://randomuser.me/api/portraits/women/44.jpg'
    },
    {
        name: 'Lê Hoàng Nam', role: 'Pro Gamer',
        content: 'Laptop mạnh, giá tốt, giao hàng nhanh. Phần gaming nhìn rất chuyên nghiệp.',
        avatar: 'https://randomuser.me/api/portraits/men/52.jpg'
    }
]

const stats = [
    { value: '15K+', label: 'Khách hàng hài lòng' },
    { value: '500+', label: 'Mẫu laptop cao cấp' },
    { value: '24/7', label: 'Tư vấn kỹ thuật' },
    { value: '99%', label: 'Đánh giá tích cực' }
]

const current = ref(0)
let interval = null

const nextSlide = () => { current.value = (current.value + 1) % slides.length }
const prevSlide = () => { current.value = (current.value - 1 + slides.length) % slides.length }
const start = () => { stop(); interval = setInterval(nextSlide, 5000) }
const stop = () => { if (interval) clearInterval(interval) }

onMounted(start)
onUnmounted(stop)
</script>

<template>
    <Header />

    <GiftPopup v-if="showGift" :delay="0" />

    <main class="home">
        <!-- HERO -->
        <section class="hero" @mouseenter="stop" @mouseleave="start">
            <div class="hero-bg"></div>
            <div class="container hero-inner">
                <transition name="fade-slide" mode="out-in">
                    <div class="hero-content" :key="current">
                        <div class="hero-left">
                            <span class="hero-eyebrow">{{ slides[current].eyebrow }}</span>
                            <h1>
                                {{ slides[current].title }}
                                <span>{{ slides[current].highlight }}</span>
                            </h1>
                            <p>{{ slides[current].desc }}</p>
                            <div class="hero-actions">
                                <button class="btn btn-primary">{{ slides[current].primary }}</button>
                                <button class="btn btn-secondary">{{ slides[current].secondary }}</button>
                            </div>
                            <div class="hero-metrics">
                                <div class="metric"><strong>Miễn phí</strong><span>giao hàng toàn quốc</span></div>
                                <div class="metric"><strong>0%</strong><span>trả góp linh hoạt</span></div>
                                <div class="metric"><strong>24 tháng</strong><span>bảo hành uy tín</span></div>
                            </div>
                        </div>
                        <div class="hero-right">
                            <div class="hero-image-card">
                                <img :src="slides[current].img" :alt="slides[current].title" />
                            </div>
                            <div class="floating-card top"><span>🔥 Xu hướng</span><strong>Laptop AI 2026</strong></div>
                            <div class="floating-card bottom"><span>⚡ Deal nổi bật</span><strong>Giảm đến 20%</strong>
                            </div>
                        </div>
                    </div>
                </transition>
                <div class="hero-controls">
                    <button class="nav-btn" @click="prevSlide">‹</button>
                    <div class="dots">
                        <span v-for="(slide, i) in slides" :key="i" :class="{ active: i === current }"
                            @click="current = i"></span>
                    </div>
                    <button class="nav-btn" @click="nextSlide">›</button>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section class="stats">
            <div class="container stats-grid">
                <div class="stat-card" v-for="(item, i) in stats" :key="i">
                    <h3>{{ item.value }}</h3>
                    <p>{{ item.label }}</p>
                </div>
            </div>
        </section>

        <!-- CATEGORY -->
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div>
                        <span class="section-label">DANH MỤC</span>
                        <h2>Lựa chọn đúng dòng laptop cho bạn</h2>
                        <p>Thiết kế tối giản, cấu hình mạnh mẽ, tối ưu theo từng nhu cầu sử dụng.</p>
                    </div>
                    <a href="#" class="section-link">Xem tất cả</a>
                </div>
                <div class="category-grid">
                    <div class="category-card" v-for="(c, i) in categories" :key="i">
                        <div class="category-icon">{{ c.icon }}</div>
                        <h3>{{ c.name }}</h3>
                        <p>{{ c.desc }}</p>
                        <a href="#">Khám phá</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURED -->
        <section class="section featured-section">
            <div class="container">
                <div class="section-head center">
                    <div>
                        <span class="section-label">SẢN PHẨM NỔI BẬT</span>
                        <h2>Những mẫu laptop được yêu thích nhất</h2>
                        <p>Chọn lọc từ các dòng máy bán chạy với hiệu năng tốt, thiết kế đẹp và giá trị cao.</p>
                    </div>
                </div>
                <div class="product-grid">
                    <article class="product-card" v-for="(p, i) in featuredProducts" :key="i">
                        <span class="product-badge" v-if="p.badge">{{ p.badge }}</span>
                        <div class="product-thumb"><img :src="p.img" :alt="p.name" /></div>
                        <div class="product-body">
                            <span class="product-category">{{ p.category }}</span>
                            <h3>{{ p.name }}</h3>
                            <ul class="specs">
                                <li v-for="(spec, index) in p.specs" :key="index">{{ spec }}</li>
                            </ul>
                            <div class="price-row">
                                <strong>{{ p.price }}</strong>
                                <span>{{ p.old }}</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary small">Mua ngay</button>
                                <button class="btn btn-secondary small">Chi tiết</button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- PROMO -->
        <section class="promo">
            <div class="container promo-box">
                <div class="promo-text">
                    <span class="section-label light">ƯU ĐÃI ĐẶC BIỆT</span>
                    <h2>Nâng cấp trải nghiệm làm việc và giải trí ngay hôm nay</h2>
                    <p>Giảm giá trực tiếp, hỗ trợ trả góp, quà tặng đi kèm và dịch vụ kỹ thuật tận tâm cho mọi đơn hàng
                        laptop cao cấp.</p>
                </div>
                <div class="promo-actions">
                    <button class="btn btn-light">Nhận ưu đãi</button>
                    <button class="btn btn-outline-light">Liên hệ tư vấn</button>
                </div>
            </div>
        </section>

        <!-- BENEFITS -->
        <section class="section">
            <div class="container">
                <div class="section-head center">
                    <div>
                        <span class="section-label">LÝ DO CHỌN CHÚNG TÔI</span>
                        <h2>Dịch vụ xứng tầm một hệ thống bán laptop chuyên nghiệp</h2>
                    </div>
                </div>
                <div class="benefits-grid">
                    <div class="benefit-card" v-for="(b, i) in benefits" :key="i">
                        <div class="benefit-icon">{{ b.icon }}</div>
                        <h3>{{ b.title }}</h3>
                        <p>{{ b.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEWS -->
        <section class="section soft-bg">
            <div class="container">
                <div class="section-head">
                    <div>
                        <span class="section-label">BLOG & TIN TỨC</span>
                        <h2>Cập nhật xu hướng công nghệ mới nhất</h2>
                        <p>Thông tin hữu ích giúp bạn chọn đúng laptop và khai thác hiệu quả hơn.</p>
                    </div>
                    <a href="#" class="section-link">Xem thêm</a>
                </div>
                <div class="news-grid">
                    <article class="news-card" v-for="(n, i) in news" :key="i">
                        <div class="news-thumb"><img :src="n.img" :alt="n.title" /></div>
                        <div class="news-body">
                            <span class="news-tag">{{ n.tag }}</span>
                            <h3>{{ n.title }}</h3>
                            <p>{{ n.desc }}</p>
                            <a href="#">Đọc thêm</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- REVIEWS -->
        <section class="section">
            <div class="container">
                <div class="section-head center">
                    <div>
                        <span class="section-label">KHÁCH HÀNG NÓI GÌ</span>
                        <h2>Niềm tin của khách hàng là giá trị lớn nhất</h2>
                        <p>Trải nghiệm mua sắm cao cấp, tư vấn tận tâm và dịch vụ hậu mãi chuyên nghiệp.</p>
                    </div>
                </div>
                <div class="review-grid">
                    <article class="review-card" v-for="(r, i) in reviews" :key="i">
                        <div class="stars">★★★★★</div>
                        <p class="review-content">"{{ r.content }}"</p>
                        <div class="review-user">
                            <img :src="r.avatar" :alt="r.name" />
                            <div><strong>{{ r.name }}</strong><span>{{ r.role }}</span></div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container cta-box">
                <div>
                    <span class="section-label light">SẴN SÀNG NÂNG CẤP?</span>
                    <h2>Tìm chiếc laptop hoàn hảo cho công việc và phong cách của bạn</h2>
                </div>
                <div class="cta-actions">
                    <button class="btn btn-light">Xem sản phẩm</button>
                    <button class="btn btn-outline-light">Tư vấn miễn phí</button>
                </div>
            </div>
        </section>
    </main>

    <Footer />
</template>

<style scoped>
/* === giữ nguyên toàn bộ style gốc === */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* {
    box-sizing: border-box;
}

.home {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top left, rgba(99, 102, 241, 0.12), transparent 28%), radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 24%), linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
    color: #0f172a;
}

.container {
    width: min(1200px, calc(100% - 32px));
    margin: 0 auto;
}

.section {
    padding: 88px 0;
}

.soft-bg {
    background: rgba(255, 255, 255, 0.45);
}

.section-head {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 36px;
}

.section-head.center {
    justify-content: center;
    text-align: center;
}

.section-label {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(79, 70, 229, 0.1);
    color: #4338ca;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 14px;
}

.section-label.light {
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
}

.section-head h2,
.promo-text h2,
.cta-box h2 {
    margin: 0 0 12px;
    font-size: 38px;
    line-height: 1.2;
    font-weight: 800;
}

.section-head p,
.promo-text p,
.cta-box p {
    margin: 0;
    max-width: 680px;
    color: #64748b;
    font-size: 15px;
    line-height: 1.7;
}

.section-link {
    white-space: nowrap;
    color: #4338ca;
    font-weight: 700;
    text-decoration: none;
}

.btn {
    border: none;
    outline: none;
    cursor: pointer;
    transition: all 0.25s ease;
    border-radius: 16px;
    font-weight: 700;
    font-family: inherit;
}

.btn-primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    box-shadow: 0 14px 30px rgba(79, 70, 229, 0.25);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 40px rgba(79, 70, 229, 0.35);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.75);
    color: #0f172a;
    border: 1px solid rgba(148, 163, 184, 0.25);
    backdrop-filter: blur(12px);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    background: #fff;
}

.btn-light {
    background: #fff;
    color: #111827;
    padding: 14px 22px;
}

.btn-outline-light {
    background: transparent;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 14px 22px;
}

.small {
    padding: 11px 16px;
    font-size: 13px;
}

.hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 60px 0 30px;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 20%, rgba(79, 70, 229, 0.18), transparent 22%), radial-gradient(circle at 80% 30%, rgba(37, 99, 235, 0.16), transparent 24%), linear-gradient(135deg, #f8fbff 0%, #edf2ff 45%, #eef2ff 100%);
}

.hero-inner {
    position: relative;
    z-index: 2;
}

.hero-content {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 48px;
    align-items: center;
}

.hero-left {
    max-width: 620px;
}

.hero-eyebrow {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 16px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(148, 163, 184, 0.16);
    backdrop-filter: blur(12px);
    color: #4338ca;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.hero-left h1 {
    margin: 0 0 18px;
    font-size: 64px;
    line-height: 1.05;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.hero-left h1 span {
    display: block;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    -webkit-background-clip: text;
    color: transparent;
}

.hero-left p {
    margin: 0 0 28px;
    color: #475569;
    font-size: 17px;
    line-height: 1.8;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 36px;
}

.hero-actions .btn {
    padding: 15px 22px;
    font-size: 14px;
}

.hero-metrics {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.metric {
    min-width: 150px;
    padding: 16px 18px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.58);
    border: 1px solid rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(14px);
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
}

.metric strong {
    display: block;
    font-size: 18px;
    margin-bottom: 4px;
}

.metric span {
    color: #64748b;
    font-size: 13px;
}

.hero-right {
    position: relative;
    display: flex;
    justify-content: center;
}

.hero-image-card {
    width: 100%;
    max-width: 520px;
    padding: 18px;
    border-radius: 32px;
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(14px);
    box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
}

.hero-image-card img {
    width: 100%;
    height: 560px;
    object-fit: cover;
    border-radius: 24px;
    display: block;
}

.floating-card {
    position: absolute;
    padding: 14px 16px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(14px);
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
}

.floating-card span {
    display: block;
    color: #64748b;
    font-size: 12px;
    margin-bottom: 4px;
}

.floating-card strong {
    font-size: 15px;
}

.floating-card.top {
    top: 28px;
    right: -8px;
}

.floating-card.bottom {
    bottom: 28px;
    left: -12px;
}

.hero-controls {
    margin-top: 28px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
}

.nav-btn {
    width: 46px;
    height: 46px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 28px;
    line-height: 1;
    color: #1e293b;
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
}

.dots {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dots span {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    background: rgba(148, 163, 184, 0.6);
    cursor: pointer;
    transition: all 0.25s ease;
}

.dots span.active {
    width: 34px;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
}

.stats {
    margin-top: -10px;
    padding-bottom: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.stat-card {
    padding: 26px 20px;
    border-radius: 24px;
    text-align: center;
    background: rgba(255, 255, 255, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(12px);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
}

.stat-card h3 {
    margin: 0 0 8px;
    font-size: 28px;
    color: #312e81;
}

.stat-card p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.category-card {
    padding: 28px 24px;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.58);
    border: 1px solid rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(14px);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 50px rgba(15, 23, 42, 0.1);
}

.category-icon {
    width: 58px;
    height: 58px;
    display: grid;
    place-items: center;
    border-radius: 18px;
    font-size: 26px;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(37, 99, 235, 0.12));
    margin-bottom: 18px;
}

.category-card h3 {
    margin: 0 0 8px;
    font-size: 20px;
}

.category-card p {
    margin: 0 0 16px;
    color: #64748b;
    line-height: 1.7;
    font-size: 14px;
}

.category-card a {
    color: #4338ca;
    text-decoration: none;
    font-weight: 700;
}

.featured-section {
    padding-top: 40px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.product-card {
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.82);
    backdrop-filter: blur(14px);
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
    transition: all 0.35s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 28px 60px rgba(15, 23, 42, 0.12);
}

.product-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 2;
    padding: 8px 12px;
    border-radius: 999px;
    background: linear-gradient(135deg, #ef4444, #f97316);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
}

.product-thumb {
    padding: 22px 22px 8px;
    overflow: hidden;
}

.product-thumb img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 22px;
    transition: transform 0.35s ease;
}

.product-card:hover .product-thumb img {
    transform: scale(1.06);
}

.product-body {
    padding: 8px 22px 22px;
}

.product-category {
    display: inline-block;
    margin-bottom: 10px;
    color: #6366f1;
    font-size: 12px;
    font-weight: 700;
}

.product-body h3 {
    margin: 0 0 14px;
    font-size: 20px;
}

.specs {
    list-style: none;
    padding: 0;
    margin: 0 0 18px;
    display: grid;
    gap: 8px;
}

.specs li {
    position: relative;
    padding-left: 18px;
    color: #64748b;
    font-size: 13px;
}

.specs li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 7px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
}

.price-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}

.price-row strong {
    font-size: 22px;
    color: #1d4ed8;
}

.price-row span {
    color: #94a3b8;
    font-size: 14px;
    text-decoration: line-through;
}

.product-actions {
    display: flex;
    gap: 10px;
}

.product-actions .btn {
    flex: 1;
}

.promo {
    padding: 20px 0 10px;
}

.promo-box,
.cta-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    padding: 42px;
    border-radius: 32px;
    background: linear-gradient(135deg, #1d4ed8, #4f46e5);
    color: #fff;
    box-shadow: 0 30px 60px rgba(37, 99, 235, 0.25);
}

.promo-text p,
.cta-box p {
    color: rgba(255, 255, 255, 0.82);
}

.promo-actions,
.cta-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.benefit-card {
    padding: 28px 22px;
    text-align: center;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.84);
    backdrop-filter: blur(12px);
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
}

.benefit-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    display: grid;
    place-items: center;
    border-radius: 20px;
    font-size: 28px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(79, 70, 229, 0.14));
}

.benefit-card h3 {
    margin: 0 0 10px;
}

.benefit-card p {
    margin: 0;
    color: #64748b;
    line-height: 1.7;
    font-size: 14px;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.news-card {
    overflow: hidden;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.84);
    backdrop-filter: blur(14px);
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
    transition: all 0.3s ease;
}

.news-card:hover {
    transform: translateY(-8px);
}

.news-thumb img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

.news-body {
    padding: 22px;
}

.news-tag {
    display: inline-block;
    margin-bottom: 12px;
    color: #4338ca;
    font-size: 12px;
    font-weight: 800;
}

.news-body h3 {
    margin: 0 0 10px;
    font-size: 21px;
    line-height: 1.4;
}

.news-body p {
    margin: 0 0 16px;
    color: #64748b;
    line-height: 1.7;
    font-size: 14px;
}

.news-body a {
    color: #4338ca;
    text-decoration: none;
    font-weight: 700;
}

.review-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.review-card {
    padding: 28px 24px;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(255, 255, 255, 0.84);
    backdrop-filter: blur(12px);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
}

.stars {
    margin-bottom: 14px;
    color: #f59e0b;
    font-size: 18px;
    letter-spacing: 2px;
}

.review-content {
    min-height: 90px;
    margin: 0 0 18px;
    color: #334155;
    line-height: 1.8;
    font-size: 14px;
}

.review-user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-user img {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    object-fit: cover;
}

.review-user strong {
    display: block;
    margin-bottom: 4px;
}

.review-user span {
    color: #64748b;
    font-size: 13px;
}

.cta {
    padding: 10px 0 90px;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.5s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(18px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-18px);
}

@media (max-width: 1100px) {

    .hero-content,
    .stats-grid,
    .category-grid,
    .product-grid,
    .benefits-grid,
    .review-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .news-grid {
        grid-template-columns: 1fr;
    }

    .hero-left h1 {
        font-size: 52px;
    }
}

@media (max-width: 768px) {
    .section {
        padding: 64px 0;
    }

    .hero {
        min-height: auto;
        padding-top: 30px;
    }

    .hero-content {
        grid-template-columns: 1fr;
    }

    .hero-left h1 {
        font-size: 40px;
    }

    .hero-image-card img {
        height: 320px;
    }

    .floating-card {
        display: none;
    }

    .stats-grid,
    .category-grid,
    .product-grid,
    .benefits-grid,
    .news-grid,
    .review-grid {
        grid-template-columns: 1fr;
    }

    .section-head,
    .promo-box,
    .cta-box {
        flex-direction: column;
        align-items: flex-start;
    }

    .section-head.center {
        align-items: center;
    }

    .hero-metrics {
        display: grid;
        grid-template-columns: 1fr;
    }

    .product-actions {
        flex-direction: column;
    }

    .promo-box,
    .cta-box {
        padding: 28px;
    }
}
</style>