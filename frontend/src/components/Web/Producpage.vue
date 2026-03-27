<script setup>
import { ref, onMounted } from 'vue'
import Header from '../Layout/Header.vue'
import Footer from '../Layout/Footer.vue'
import api from '../../services/api'


const selectedBrands = ref(['Apple'])
const selectedSort = ref('Mới nhất')

const brands = ['Apple', 'ASUS', 'Dell', 'HP', 'Lenovo', 'MSI']

const toggleBrand = (b) => {
    const idx = selectedBrands.value.indexOf(b)
    if (idx === -1) selectedBrands.value.push(b)
    else selectedBrands.value.splice(idx, 1)
}

const products = ref([])

onMounted(async () => {
    try {
        const response = await api.get('/sanpham')
        products.value = response.data.map(p => ({
            id_sanpham: p.id_sanpham,
            name: p.tenSP,
            brand: `${p.thuong_hieu?.ten_thuonghieu || ''} ${p.khoiluong ? '· ' + p.khoiluong + 'kg' : ''}`,
            price: new Intl.NumberFormat('vi-VN').format(p.bien_thes?.[0]?.gia || 0) + 'đ',
            oldPrice: '', 
            img: p.hinhanh ? 'http://127.0.0.1:8000/storage/' + p.hinhanh : '',
            badge: p.trangthai === 'Hot' ? 'HOT' : (p.trangthai === 'Mới' ? 'NEW' : ''),
            badgeColor: p.trangthai === 'Hot' ? '#dc2626' : '#2563eb'
        }))
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error)
    }
})
</script>

<template>
     <Header />
    <div class="page">
        <div class="container layout">

            <!-- SIDEBAR -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                        <line x1="11" y1="18" x2="13" y2="18"/>
                    </svg>
                    <h3>Bộ lọc sản phẩm</h3>
                </div>

                <!-- DANH MỤC -->
                <div class="filter-group">
                    <p class="group-label">Danh mục</p>
                    <label class="check-label" v-for="c in ['Gaming','Văn phòng','Cao cấp']" :key="c">
                        <input type="checkbox" />
                        <span class="checkmark"></span>
                        {{ c }}
                    </label>
                </div>

                <div class="divider"></div>

                <!-- THƯƠNG HIỆU -->
                <div class="filter-group">
                    <p class="group-label">Thương hiệu</p>
                    <div class="brands">
                        <span
                            v-for="b in brands" :key="b"
                            class="brand-tag"
                            :class="{ active: selectedBrands.includes(b) }"
                            @click="toggleBrand(b)"
                        >{{ b }}</span>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- RAM -->
                <div class="filter-group">
                    <p class="group-label">RAM</p>
                    <label class="check-label" v-for="r in ['16GB','32GB','64GB']" :key="r">
                        <input type="checkbox" />
                        <span class="checkmark"></span>
                        {{ r }}
                    </label>
                </div>

                <div class="divider"></div>

                <!-- GIÁ -->
                <div class="filter-group">
                    <p class="group-label">Khoảng giá</p>
                    <label class="check-label" v-for="g in ['Dưới 20 triệu','20 – 50 triệu','Trên 50 triệu']" :key="g">
                        <input type="radio" name="price" />
                        <span class="checkmark"></span>
                        {{ g }}
                    </label>
                </div>

                <button class="apply-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Áp dụng bộ lọc
                </button>
            </aside>

            <!-- CONTENT -->
            <main class="content">

                <!-- TOP BAR -->
                <div class="top-bar">
                    <div>
                        <h1>Danh sách Laptop</h1>
                        <p>Tìm thấy <b>{{ products.length }}</b> sản phẩm phù hợp</p>
                    </div>

                    <div class="sort-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M3 6h18M6 12h12M9 18h6"/>
                        </svg>
                        <select v-model="selectedSort">
                            <option>Mới nhất</option>
                            <option>Giá thấp → cao</option>
                            <option>Giá cao → thấp</option>
                            <option>Phổ biến nhất</option>
                        </select>
                        <svg class="chevron" viewBox="0 0 20 20" fill="none">
                            <path d="M5 7L10 12L15 7" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <!-- GRID -->
                <div class="grid">
                    <div class="card" v-for="(p, i) in products" :key="p.id_sanpham || i">

                        <span v-if="p.badge" class="badge" :style="{ background: p.badgeColor }">{{ p.badge }}</span>

                        <div class="img-box">
                            <img :src="p.img" :alt="p.name" />
                        </div>

                        <div class="card-body">
                            <h3>{{ p.name }}</h3>
                            <p class="brand-txt">{{ p.brand }}</p>

                            <div class="price-row">
                                <span class="price">{{ p.price }}</span>
                                <span v-if="p.oldPrice" class="old-price">{{ p.oldPrice }}</span>
                            </div>

                            <div class="card-actions">
                                <button class="btn-detail">Chi tiết</button>
                                <button class="btn-cart">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- PAGINATION -->
                <div class="pagination">
                    <button class="pg-btn">‹</button>
                    <button class="pg-btn active">1</button>
                    <button class="pg-btn">2</button>
                    <button class="pg-btn">3</button>
                    <span class="pg-dots">...</span>
                    <button class="pg-btn">12</button>
                    <button class="pg-btn">›</button>
                </div>

            </main>
        </div>
    </div>
    <Footer />
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; }

.page {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    padding: 32px 0 60px;
    min-height: 100vh;
}

.container { width: min(1200px, 95%); margin: auto; }
.layout { display: flex; gap: 24px; align-items: flex-start; }

/* ===== SIDEBAR ===== */
.sidebar {
    width: 248px; flex-shrink: 0;
    background: white; border-radius: 18px;
    border: 1px solid #f1f5f9; padding: 22px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    position: sticky; top: 20px;
}

.sidebar-header { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.sidebar-header svg { width: 16px; height: 16px; color: #2563eb; }
.sidebar-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; }

.divider { height: 1px; background: #f1f5f9; margin: 16px 0; }
.filter-group { }
.group-label { font-size: 10px; font-weight: 700; color: #94a3b8; letter-spacing: 0.09em; text-transform: uppercase; margin: 0 0 10px; }

/* CUSTOM CHECKBOX */
.check-label {
    display: flex; align-items: center; gap: 9px;
    font-size: 13px; color: #334155; margin-bottom: 9px; cursor: pointer; user-select: none;
}
.check-label input { display: none; }
.checkmark {
    width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
    border: 1.5px solid #cbd5e1; background: white; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.check-label input:checked ~ .checkmark { background: #2563eb; border-color: #2563eb; }
.check-label input:checked ~ .checkmark::after {
    content: ''; width: 8px; height: 5px;
    border-left: 2px solid white; border-bottom: 2px solid white;
    transform: rotate(-45deg) translate(1px,-1px); display: block;
}

/* BRANDS */
.brands { display: flex; flex-wrap: wrap; gap: 6px; }
.brand-tag {
    padding: 5px 12px; border-radius: 20px; border: 1px solid #e2e8f0;
    font-size: 12px; font-weight: 500; color: #475569; cursor: pointer; transition: all 0.2s;
}
.brand-tag:hover { border-color: #2563eb; color: #2563eb; background: #f0f6ff; }
.brand-tag.active { background: linear-gradient(135deg,#2563eb,#4f46e5); color: white; border-color: transparent; }

/* APPLY */
.apply-btn {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 12px; border-radius: 12px; border: none;
    background: linear-gradient(135deg,#2563eb,#4f46e5);
    color: white; font-size: 13px; font-weight: 700;
    cursor: pointer; margin-top: 20px; transition: opacity 0.2s, transform 0.2s;
}
.apply-btn svg { width: 14px; height: 14px; }
.apply-btn:hover { opacity: 0.9; transform: translateY(-1px); }

/* ===== CONTENT ===== */
.content { flex: 1; min-width: 0; }

/* TOP BAR */
.top-bar {
    display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;
}
.top-bar h1 { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
.top-bar p  { font-size: 13px; color: #94a3b8; margin: 0; }
.top-bar b  { color: #2563eb; }

/* SORT */
.sort-wrap {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: 10px;
    border: 1px solid #e2e8f0; background: white;
    transition: border-color 0.2s; cursor: pointer;
}
.sort-wrap:focus-within { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
.sort-wrap > svg:first-child { width: 14px; height: 14px; color: #64748b; flex-shrink: 0; }
.sort-wrap select {
    border: none; outline: none; background: transparent;
    font-size: 13px; font-weight: 500; color: #334155;
    cursor: pointer; appearance: none; min-width: 130px;
    font-family: 'Inter', sans-serif;
}
.chevron { width: 12px; height: 12px; color: #94a3b8; flex-shrink: 0; }

/* GRID */
.grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }

/* CARD */
.card {
    background: white; border-radius: 16px; border: 1px solid #f1f5f9;
    overflow: hidden; position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
.card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(0,0,0,0.1); }

.badge {
    position: absolute; top: 10px; left: 10px;
    color: white; font-size: 10px; font-weight: 700;
    padding: 4px 9px; border-radius: 6px; z-index: 1; letter-spacing: 0.04em;
}

.img-box { background: #f8fafc; padding: 14px; overflow: hidden; }
.img-box img {
    width: 100%; height: 148px; object-fit: cover;
    border-radius: 10px; transition: transform 0.4s ease;
}
.card:hover .img-box img { transform: scale(1.05); }

.card-body { padding: 13px 15px 15px; }
.card-body h3 { font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
.brand-txt { font-size: 11px; color: #94a3b8; margin: 0 0 10px; }

.price-row { display: flex; align-items: baseline; gap: 8px; margin-bottom: 12px; }
.price { font-size: 15px; font-weight: 800; color: #2563eb; }
.old-price { font-size: 11px; color: #cbd5e1; text-decoration: line-through; }

.card-actions { display: flex; gap: 8px; }
.btn-detail {
    flex: 1; padding: 8px 10px; border-radius: 8px;
    border: 1.5px solid #2563eb; background: white;
    color: #2563eb; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;
}
.btn-detail:hover { background: #2563eb; color: white; }
.btn-cart {
    width: 34px; height: 34px; border-radius: 8px; border: none;
    background: linear-gradient(135deg,#2563eb,#4f46e5);
    color: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: opacity 0.2s, transform 0.2s;
}
.btn-cart svg { width: 14px; height: 14px; }
.btn-cart:hover { opacity: 0.9; transform: scale(1.06); }

/* PAGINATION */
.pagination {
    display: flex; justify-content: center; align-items: center;
    gap: 5px; margin-top: 28px;
}
.pg-btn {
    width: 36px; height: 36px; border-radius: 9px;
    border: 1px solid #e2e8f0; background: white;
    font-size: 13px; font-weight: 500; color: #334155;
    cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.pg-btn:hover { border-color: #2563eb; color: #2563eb; }
.pg-btn.active { background: linear-gradient(135deg,#2563eb,#4f46e5); color: white; border-color: transparent; }
.pg-dots { color: #94a3b8; padding: 0 4px; }

/* RESPONSIVE */
@media (max-width: 900px) { .grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 640px) {
    .layout { flex-direction: column; }
    .sidebar { width: 100%; position: static; }
    .grid { grid-template-columns: repeat(2,1fr); }
    .top-bar { flex-direction: column; align-items: flex-start; gap: 12px; }
}
</style>