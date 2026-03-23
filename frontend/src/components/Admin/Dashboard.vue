<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const searchQuery = ref('')
const chartPeriod = ref('Tuần này')

const today = new Date().toLocaleDateString('vi-VN', { day: '2-digit', month: 'long', year: 'numeric' })

const stats = [
    { label: 'Doanh thu tổng', value: '1.2 tỷ đ', change: '+12.5%', up: true, icon: '💰', color: '#dbeafe' },
    { label: 'Đơn hàng mới', value: '120', change: '+8.2%', up: true, icon: '📦', color: '#dcfce7' },
    { label: 'Khách hàng', value: '450', change: '-2.4%', up: false, icon: '👥', color: '#ede9fe' },
    { label: 'Sản phẩm kho', value: '215', change: 'Ổn định', up: null, icon: '🗃️', color: '#f1f5f9' },
]

const chartData = [
    { day: 'THỨ 2', val: 55 },
    { day: 'THỨ 3', val: 70 },
    { day: 'THỨ 4', val: 48 },
    { day: 'THỨ 5', val: 95, highlight: true },
    { day: 'THỨ 6', val: 62 },
    { day: 'THỨ 7', val: 40 },
    { day: 'CN', val: 30 },
]

const donutData = [
    { label: 'Hoàn thành', val: 78, color: '#2563eb' },
    { label: 'Chờ xử lý', val: 10, color: '#93c5fd' },
    { label: 'Đang giao', val: 8, color: '#60a5fa' },
    { label: 'Hủy đơn', val: 4, color: '#e2e8f0' },
]

const orders = ref([
    { id: '#VT-0021', customer: 'Nguyễn Văn An', total: '24.500.000đ', status: 'Chờ xác nhận', statusClass: 'warn' },
    { id: '#VT-0020', customer: 'Lê Thị Bích', total: '12.200.000đ', status: 'Hoàn thành', statusClass: 'ok' },
    { id: '#VT-0019', customer: 'Trần Minh Tâm', total: '45.000.000đ', status: 'Đang giao', statusClass: 'info' },
    { id: '#VT-0018', customer: 'Phạm Hoàng Nam', total: '8.900.000đ', status: 'Hoàn thành', statusClass: 'ok' },
])

const products = ref([
    { name: 'MacBook Pro M3 Max 2026', sub: 'Đã bán: 4k đơn vị', price: '98.000.000đ', stock: 'Còn hàng', stockClass: 'ok', img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=80' },
    { name: 'iPhone 17 Pro Ultra', sub: 'Đã bán: 3.2k đơn vị', price: '35.000.000đ', stock: 'Còn hàng', stockClass: 'ok', img: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=80' },
    { name: 'VinaWatch Series 5 Gold', sub: 'Đã bán: 1.8k đơn vị', price: '12.500.000đ', stock: 'Hết hàng', stockClass: 'out', img: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=80' },
])

// Donut SVG
const cx = 60, cy = 60, r = 46
const circumference = 2 * Math.PI * r
const total = donutData.reduce((s, d) => s + d.val, 0)
let offset = 0
const segments = donutData.map(d => {
    const pct = d.val / total
    const dash = pct * circumference
    const seg = { ...d, dash, gap: circumference - dash, offset }
    offset += dash
    return seg
})
</script>

<template>
    <div class="page">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h2>Tổng quan hệ thống</h2>
                <p>Chào mừng trở lại, hôm nay là {{ today }}</p>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input v-model="searchQuery" placeholder="Tìm kiếm dữ liệu..." />
                </div>
                <button class="icon-btn">🔔</button>
                <router-link to="/" class="btn-home">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Trang chủ
                </router-link>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card" v-for="s in stats" :key="s.label">
                <div class="stat-top">
                    <div class="stat-icon-wrap" :style="{ background: s.color }">{{ s.icon }}</div>
                    <span class="stat-change"
                        :class="s.up === true ? 'change-up' : s.up === false ? 'change-down' : 'change-neutral'">
                        {{ s.change }}
                    </span>
                </div>
                <p class="stat-label">{{ s.label }}</p>
                <b class="stat-value">{{ s.value }}</b>
            </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="charts-row">

            <!-- BAR CHART -->
            <div class="card chart-card">
                <div class="chart-header">
                    <span class="chart-title">Biểu đồ doanh thu (7 ngày qua)</span>
                    <select v-model="chartPeriod" class="period-select">
                        <option>Tuần này</option>
                        <option>Tháng này</option>
                        <option>Năm nay</option>
                    </select>
                </div>
                <div class="bar-chart">
                    <div class="bars">
                        <div class="bar-col" v-for="d in chartData" :key="d.day">
                            <div class="bar-wrap">
                                <span v-if="d.highlight" class="bar-tooltip">2.4k</span>
                                <div class="bar-fill"
                                    :style="{ height: d.val + '%', background: d.highlight ? '#2563eb' : '#bfdbfe' }">
                                </div>
                            </div>
                            <span class="bar-label">{{ d.day }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DONUT CHART -->
            <div class="card donut-card">
                <div class="chart-title" style="margin-bottom:16px">Trạng thái đơn hàng</div>
                <div class="donut-wrap">
                    <svg viewBox="0 0 120 120" class="donut-svg">
                        <circle cx="60" cy="60" r="46" fill="none" stroke="#f1f5f9" stroke-width="14"/>
                        <circle v-for="seg in segments" :key="seg.label"
                            cx="60" cy="60" r="46" fill="none"
                            :stroke="seg.color" stroke-width="14"
                            :stroke-dasharray="`${seg.dash} ${seg.gap}`"
                            :stroke-dashoffset="-seg.offset"
                            stroke-linecap="butt"
                            style="transform: rotate(-90deg); transform-origin: 50% 50%;"
                        />
                        <text x="60" y="55" text-anchor="middle" font-size="16" font-weight="800" fill="#0f172a">78%</text>
                        <text x="60" y="70" text-anchor="middle" font-size="7" fill="#94a3b8">THÀNH CÔNG</text>
                    </svg>
                </div>
                <div class="donut-legend">
                    <div class="legend-item" v-for="d in donutData" :key="d.label">
                        <span class="legend-dot" :style="{ background: d.color }"></span>
                        <span>{{ d.label }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- BOTTOM ROW -->
        <div class="bottom-row">

            <!-- ORDERS TABLE -->
            <div class="card orders-card">
                <div class="section-header">
                    <span class="section-title">Đơn hàng mới nhất</span>
                    <a href="#" class="see-all">Xem tất cả</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>MÃ ĐƠN</th>
                            <th>KHÁCH HÀNG</th>
                            <th>TỔNG CỘNG</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="o in orders" :key="o.id">
                            <td class="order-id">{{ o.id }}</td>
                            <td class="order-customer">{{ o.customer }}</td>
                            <td class="order-total">{{ o.total }}</td>
                            <td>
                                <span class="status-badge" :class="o.statusClass">{{ o.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TOP PRODUCTS -->
            <div class="card products-card">
                <div class="section-header">
                    <span class="section-title">Sản phẩm bán chạy</span>
                    <a href="#" class="see-all">Chi tiết</a>
                </div>
                <div class="product-list">
                    <div class="product-item" v-for="p in products" :key="p.name">
                        <img :src="p.img" :alt="p.name" />
                        <div class="product-info">
                            <b>{{ p.name }}</b>
                            <span>{{ p.sub }}</span>
                        </div>
                        <div class="product-right">
                            <b class="product-price">{{ p.price }}</b>
                            <span class="stock-tag" :class="p.stockClass">{{ p.stock }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<style scoped>
* { box-sizing: border-box; }

.page {
    background: #f5f7fb; min-height: 100vh;
    font-family: sans-serif; padding: 0 0 40px;
}

/* TOPBAR */
.topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 28px 16px; background: #f5f7fb;
}
.topbar-left h2 { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 3px; }
.topbar-left p  { font-size: 12px; color: #94a3b8; margin: 0; }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.search-box { position: relative; }
.search-box svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 13px; height: 13px; color: #94a3b8; pointer-events: none; }
.search-box input { padding: 8px 14px 8px 30px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px; color: #0f172a; outline: none; background: white; width: 200px; }
.search-box input:focus { border-color: #2563eb; }
.icon-btn { background: none; border: none; font-size: 16px; cursor: pointer; padding: 6px; border-radius: 8px; }
.icon-btn:hover { background: #e2e8f0; }

.btn-home {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 9px;
    border: 1px solid #e2e8f0; background: white;
    font-size: 13px; font-weight: 600; color: #334155;
    text-decoration: none; cursor: pointer; transition: all 0.2s;
    white-space: nowrap;
}
.btn-home svg { width: 14px; height: 14px; }
.btn-home:hover { border-color: #2563eb; color: #2563eb; background: #f0f6ff; }

/* STATS */
.stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 12px; padding: 0 28px 16px;
}
.stat-card {
    background: white; border-radius: 14px; border: 1px solid #f1f5f9;
    padding: 16px 18px;
}
.stat-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
.stat-icon-wrap { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.stat-change { font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
.change-up      { background: #dcfce7; color: #16a34a; }
.change-down    { background: #fee2e2; color: #dc2626; }
.change-neutral { background: #f1f5f9; color: #64748b; }
.stat-label { font-size: 11px; color: #94a3b8; margin: 0 0 4px; }
.stat-value { font-size: 22px; font-weight: 800; color: #0f172a; }

/* CHARTS ROW */
.charts-row {
    display: grid; grid-template-columns: 1fr 280px;
    gap: 12px; padding: 0 28px 16px;
}

.card {
    background: white; border-radius: 14px;
    border: 1px solid #f1f5f9; padding: 18px 20px;
}

/* BAR CHART */
.chart-card { }
.chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.chart-title { font-size: 14px; font-weight: 700; color: #0f172a; }
.period-select { padding: 5px 10px; border-radius: 7px; border: 1px solid #e2e8f0; font-size: 12px; color: #334155; outline: none; background: white; cursor: pointer; }

.bar-chart { height: 160px; display: flex; align-items: flex-end; }
.bars { display: flex; gap: 10px; align-items: flex-end; width: 100%; height: 100%; }
.bar-col { display: flex; flex-direction: column; align-items: center; flex: 1; height: 100%; justify-content: flex-end; gap: 6px; }
.bar-wrap { position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; flex: 1; }
.bar-fill { width: 80%; border-radius: 5px 5px 0 0; transition: height 0.4s ease; min-height: 4px; }
.bar-label { font-size: 9px; color: #94a3b8; font-weight: 600; letter-spacing: 0.03em; white-space: nowrap; }
.bar-tooltip {
    position: absolute; top: -24px; left: 50%; transform: translateX(-50%);
    background: #0f172a; color: white; font-size: 10px; font-weight: 700;
    padding: 3px 7px; border-radius: 5px; white-space: nowrap;
}
.bar-tooltip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 4px solid transparent; border-top-color: #0f172a; }

/* DONUT */
.donut-card { display: flex; flex-direction: column; }
.donut-wrap { display: flex; justify-content: center; margin-bottom: 14px; }
.donut-svg { width: 130px; height: 130px; }
.donut-legend { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 8px; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; color: #475569; }
.legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

/* BOTTOM ROW */
.bottom-row {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 12px; padding: 0 28px;
}

/* ORDERS TABLE */
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.section-title { font-size: 14px; font-weight: 700; color: #0f172a; }
.see-all { font-size: 12px; font-weight: 600; color: #2563eb; text-decoration: none; }
.see-all:hover { text-decoration: underline; }

table { width: 100%; border-collapse: collapse; }
thead th { padding: 8px 10px; font-size: 10px; font-weight: 700; color: #94a3b8; text-align: left; letter-spacing: 0.06em; border-bottom: 1px solid #f1f5f9; }
tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbff; }
tbody td { padding: 12px 10px; font-size: 12px; vertical-align: middle; }

.order-id { color: #2563eb; font-weight: 700; font-size: 12px; }
.order-customer { color: #0f172a; font-weight: 500; }
.order-total { color: #0f172a; font-weight: 700; }

.status-badge { display: inline-block; font-size: 10px; font-weight: 700; padding: 4px 9px; border-radius: 20px; }
.status-badge.ok   { background: #dcfce7; color: #16a34a; }
.status-badge.warn { background: #fef9c3; color: #a16207; }
.status-badge.info { background: #dbeafe; color: #1d4ed8; }
.status-badge.out  { background: #fee2e2; color: #dc2626; }

/* PRODUCTS */
.product-list { display: flex; flex-direction: column; gap: 12px; }
.product-item { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; transition: background 0.15s; }
.product-item:hover { background: #f8fafc; }
.product-item img { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
.product-info { flex: 1; }
.product-info b { display: block; font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 2px; }
.product-info span { font-size: 11px; color: #94a3b8; }
.product-right { text-align: right; flex-shrink: 0; }
.product-price { display: block; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
.stock-tag { display: inline-block; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
.stock-tag.ok  { background: #dcfce7; color: #16a34a; }
.stock-tag.out { background: #fee2e2; color: #dc2626; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .charts-row { grid-template-columns: 1fr; }
    .bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 560px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .topbar { flex-direction: column; align-items: flex-start; gap: 12px; padding: 16px; }
    .stats-grid, .charts-row, .bottom-row { padding-left: 16px; padding-right: 16px; }
}
</style>