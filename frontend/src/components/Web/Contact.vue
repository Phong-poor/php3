<script setup>
import { ref } from 'vue'
import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'

const name = ref('')
const phone = ref('')
const email = ref('')
const message = ref('')
const error = ref('')
const success = ref(false)
const loading = ref(false)

async function submitForm() {
    if (!name.value || !phone.value || !email.value || !message.value) {
        error.value = 'Vui lòng nhập đầy đủ thông tin'
        success.value = false
        return
    }
    error.value = ''
    loading.value = true
    await new Promise(r => setTimeout(r, 800))
    loading.value = false
    success.value = true
    name.value = ''
    phone.value = ''
    email.value = ''
    message.value = ''
    setTimeout(() => success.value = false, 4000)
}

const mapLink = 'https://www.google.com/maps?q=123+Đường+Công+Nghệ+Quận+1+TPHCM'

const infos = [
    {
        icon: '📍',
        label: 'Địa chỉ',
        value: 'Tòa nhà VinaTech, 123 Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh',
        color: '#dbeafe',
    },
    {
        icon: '📞',
        label: 'Hotline',
        value: '1900 8888',
        bold: true,
        color: '#dcfce7',
    },
    {
        icon: '✉️',
        label: 'Email',
        value: 'support@vinatech.vn',
        color: '#ede9fe',
    },
    {
        icon: '🕐',
        label: 'Giờ làm việc',
        value: 'T2 – T6: 8:00 – 18:00 | T7: 8:00 – 12:00',
        color: '#fef9c3',
    },
]
</script>

<template>
   <Header />
    <div class="page">

        <!-- HERO -->
        <section class="hero">
            <span class="hero-badge">Liên hệ với chúng tôi</span>
            <h1>Chúng tôi luôn sẵn sàng<br /><span class="gradient-text">hỗ trợ bạn</span></h1>
            <p>Kết nối với đội ngũ chuyên gia VinaTech để nhận tư vấn và giải pháp công nghệ tối ưu cho doanh nghiệp và cá nhân.</p>
        </section>

        <!-- MAIN -->
        <section class="contact-section">
            <div class="container">
                <div class="contact-grid">

                    <!-- FORM -->
                    <div class="form-card">
                        <div class="form-top">
                            <h3>Gửi tin nhắn cho chúng tôi</h3>
                            <p>Chúng tôi sẽ phản hồi trong vòng 24 giờ</p>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Họ tên <span class="req">*</span></label>
                                <input v-model="name" placeholder="Nguyễn Văn A" />
                            </div>
                            <div class="input-group">
                                <label>Số điện thoại <span class="req">*</span></label>
                                <input v-model="phone" placeholder="090 123 4567" />
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Email <span class="req">*</span></label>
                            <input v-model="email" type="email" placeholder="example@vinatech.com" />
                        </div>

                        <div class="input-group">
                            <label>Nội dung <span class="req">*</span></label>
                            <textarea v-model="message" placeholder="Bạn cần chúng tôi hỗ trợ vấn đề gì?"></textarea>
                        </div>

                        <p v-if="error" class="msg error">⚠ {{ error }}</p>
                        <p v-if="success" class="msg success">✓ Gửi thành công! Chúng tôi sẽ liên hệ sớm.</p>

                        <button class="submit-btn" @click="submitForm" :disabled="loading">
                            <span v-if="loading" class="spinner"></span>
                            <span v-else>Gửi yêu cầu →</span>
                        </button>
                    </div>

                    <!-- INFO -->
                    <div class="info-col">

                        <div class="info-card">
                            <h3>Thông tin liên hệ</h3>
                            <div class="info-list">
                                <div class="info-item" v-for="item in infos" :key="item.label">
                                    <div class="info-icon" :style="{ background: item.color }">
                                        {{ item.icon }}
                                    </div>
                                    <div>
                                        <p class="info-label">{{ item.label }}</p>
                                        <p :class="item.bold ? 'info-val bold' : 'info-val'">{{ item.value }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MAP -->
                        <a :href="mapLink" target="_blank" class="map-card">
                            <iframe
                                src="https://www.google.com/maps?q=123+Đường+Công+Nghệ+Quận+1+TPHCM&output=embed"
                                loading="lazy"
                            ></iframe>
                            <div class="map-overlay">
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    Xem bản đồ chi tiết →
                                </span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </section>

    </div>
    <Footer />
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; }

.page {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    min-height: 100vh;
}

/* HERO */
.hero {
    text-align: center;
    padding: 80px 20px 60px;
    background: linear-gradient(160deg, #eef2ff 0%, #f8fafc 60%);
}

.hero-badge {
    display: inline-block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    background: #e0e7ff;
    color: #4f46e5;
    padding: 6px 16px;
    border-radius: 20px;
    margin-bottom: 20px;
}

.hero h1 {
    font-size: 46px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}

.gradient-text {
    background: linear-gradient(90deg, #4f46e5, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero p {
    font-size: 16px;
    color: #64748b;
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.7;
}

/* SECTION */
.contact-section {
    padding: 0 0 80px;
}

.container {
    width: min(1100px, 95%);
    margin: auto;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 24px;
    align-items: start;
}

/* FORM CARD */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 36px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
}

.form-top { margin-bottom: 28px; }
.form-top h3 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
.form-top p  { font-size: 13px; color: #94a3b8; margin: 0; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.input-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.input-group label { font-size: 12px; font-weight: 600; color: #475569; letter-spacing: 0.02em; }
.req { color: #ef4444; }

.input-group input,
.input-group textarea {
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    font-size: 13px;
    color: #0f172a;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    font-family: 'Inter', sans-serif;
    width: 100%;
}

.input-group input:focus,
.input-group textarea:focus {
    border-color: #4f46e5;
    background: white;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.08);
}

.input-group textarea { height: 130px; resize: vertical; }

.msg { font-size: 13px; padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; }
.msg.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.msg.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

.submit-btn {
    width: 100%;
    padding: 13px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.02em;
}
.submit-btn:hover:not(:disabled) { opacity: 0.92; transform: translateY(-1px); }
.submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

.spinner {
    width: 16px; height: 16px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* INFO COL */
.info-col { display: flex; flex-direction: column; gap: 16px; }

.info-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
}
.info-card h3 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 20px; }

.info-list { display: flex; flex-direction: column; gap: 16px; }
.info-item { display: flex; gap: 12px; align-items: flex-start; }

.info-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}

.info-label { font-size: 11px; font-weight: 600; color: #94a3b8; letter-spacing: 0.06em; text-transform: uppercase; margin: 0 0 3px; }
.info-val { font-size: 13px; color: #334155; margin: 0; line-height: 1.5; }
.info-val.bold { font-size: 16px; font-weight: 700; color: #0f172a; }

/* MAP */
.map-card {
    display: block;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.map-card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
.map-card iframe { width: 100%; height: 175px; border: none; display: block; }

.map-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
    padding: 20px 16px 12px;
}
.map-overlay span {
    display: inline-flex; align-items: center; gap: 6px;
    background: white; padding: 7px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 600; color: #0f172a;
}
.map-overlay svg { width: 13px; height: 13px; color: #4f46e5; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero h1 { font-size: 30px; }
    .contact-grid { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .form-card { padding: 24px; }
}
</style>