<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('all')
const selectedOrder = ref(null)

const tabs = [
    { key: 'all', label: 'Tất cả' },
    { key: 'pending', label: 'Chờ xác nhận' },
    { key: 'shipping', label: 'Đang giao' },
    { key: 'done', label: 'Hoàn thành' },
    { key: 'cancelled', label: 'Đã hủy' },
]

const statusMap = {
    pending: { label: 'Chờ xác nhận', color: '#f59e0b', bg: '#fef3c7' },
    shipping: { label: 'Đang giao', color: '#2563eb', bg: '#dbeafe' },
    done: { label: 'Hoàn thành', color: '#16a34a', bg: '#dcfce7' },
    cancelled: { label: 'Đã hủy', color: '#dc2626', bg: '#fee2e2' },
}

const orders = ref([
    {
        id: 'NGL-2026-001',
        date: '18/03/2026',
        status: 'done',
        total: '36.990.000đ',
        items: [
            { name: 'VinaBook Pro X14', qty: 1, price: '36.990.000đ', img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200' }
        ],
        steps: [
            { label: 'Đặt hàng', date: '15/03/2026 09:12', done: true },
            { label: 'Xác nhận', date: '15/03/2026 10:30', done: true },
            { label: 'Đang đóng gói', date: '16/03/2026 08:00', done: true },
            { label: 'Đang giao', date: '17/03/2026 14:20', done: true },
            { label: 'Hoàn thành', date: '18/03/2026 11:05', done: true },
        ]
    },
    {
        id: 'NGL-2026-002',
        date: '20/03/2026',
        status: 'shipping',
        total: '62.990.000đ',
        items: [
            { name: 'Zephyrus Titan 16', qty: 1, price: '62.990.000đ', img: 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=200' }
        ],
        steps: [
            { label: 'Đặt hàng', date: '20/03/2026 14:00', done: true },
            { label: 'Xác nhận', date: '20/03/2026 15:10', done: true },
            { label: 'Đang đóng gói', date: '21/03/2026 09:00', done: true },
            { label: 'Đang giao', date: '22/03/2026 08:30', done: true },
            { label: 'Hoàn thành', date: null, done: false },
        ]
    },
    {
        id: 'NGL-2026-003',
        date: '22/03/2026',
        status: 'pending',
        total: '48.490.000đ',
        items: [
            { name: 'Creator Studio 15', qty: 1, price: '48.490.000đ', img: 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=200' }
        ],
        steps: [
            { label: 'Đặt hàng', date: '22/03/2026 20:05', done: true },
            { label: 'Xác nhận', date: null, done: false },
            { label: 'Đang đóng gói', date: null, done: false },
            { label: 'Đang giao', date: null, done: false },
            { label: 'Hoàn thành', date: null, done: false },
        ]
    },
    {
        id: 'NGL-2025-089',
        date: '10/12/2025',
        status: 'cancelled',
        total: '29.990.000đ',
        items: [
            { name: 'SlimBook Air 13', qty: 1, price: '29.990.000đ', img: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=200' }
        ],
        steps: [
            { label: 'Đặt hàng', date: '10/12/2025 11:00', done: true },
            { label: 'Đã hủy', date: '10/12/2025 11:45', done: true },
        ]
    },
])

const filtered = computed(() => {
    if (activeTab.value === 'all') return orders.value
    return orders.value.filter(o => o.status === activeTab.value)
})

const openDetail = (order) => { selectedOrder.value = order }
const closeDetail = () => { selectedOrder.value = null }
</script>

<template>
    <div class="page">
        <!-- Detail Modal -->
        <transition name="fade">
            <div class="overlay" v-if="selectedOrder" @click.self="closeDetail">
                <div class="modal">
                    <div class="modal-head">
                        <div>
                            <h2 class="modal-title">Chi tiết đơn hàng</h2>
                            <p class="modal-id">{{ selectedOrder.id }}</p>
                        </div>
                        <button class="close-btn" @click="closeDetail">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Status badge -->
                        <div class="modal-status"
                            :style="{ color: statusMap[selectedOrder.status].color, background: statusMap[selectedOrder.status].bg }">
                            {{ statusMap[selectedOrder.status].label }}
                        </div>

                        <!-- Timeline -->
                        <div class="timeline">
                            <div class="tl-item" v-for="(step, i) in selectedOrder.steps" :key="i"
                                :class="{ done: step.done, last: i === selectedOrder.steps.length - 1 }">
                                <div class="tl-dot">
                                    <svg v-if="step.done" viewBox="0 0 24 24" fill="none">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="tl-line" v-if="i < selectedOrder.steps.length - 1"></div>
                                <div class="tl-content">
                                    <p class="tl-label">{{ step.label }}</p>
                                    <p class="tl-date">{{ step.date || '—' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="modal-section">
                            <h3 class="section-title">Sản phẩm</h3>
                            <div class="modal-item" v-for="item in selectedOrder.items" :key="item.name">
                                <img :src="item.img" :alt="item.name" />
                                <div class="modal-item-info">
                                    <p class="modal-item-name">{{ item.name }}</p>
                                    <p class="modal-item-qty">Số lượng: {{ item.qty }}</p>
                                </div>
                                <p class="modal-item-price">{{ item.price }}</p>
                            </div>
                        </div>

                        <div class="modal-total">
                            <span>Tổng cộng</span>
                            <span class="total-val">{{ selectedOrder.total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Đơn hàng của tôi</h1>
                <p class="page-sub">Theo dõi và quản lý đơn hàng</p>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button v-for="tab in tabs" :key="tab.key" class="tab" :class="{ active: activeTab === tab.key }"
                    @click="activeTab = tab.key">
                    {{ tab.label }}
                    <span class="tab-count" v-if="tab.key !== 'all'">
                        {{orders.filter(o => o.status === tab.key).length}}
                    </span>
                </button>
            </div>

            <!-- Order list -->
            <div class="orders-list">
                <div v-if="filtered.length === 0" class="empty">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                    </svg>
                    <p>Không có đơn hàng nào</p>
                </div>

                <div class="order-card" v-for="order in filtered" :key="order.id">
                    <div class="order-head">
                        <div class="order-meta">
                            <span class="order-id">{{ order.id }}</span>
                            <span class="order-date">{{ order.date }}</span>
                        </div>
                        <span class="order-badge"
                            :style="{ color: statusMap[order.status].color, background: statusMap[order.status].bg }">
                            {{ statusMap[order.status].label }}
                        </span>
                    </div>

                    <div class="order-items">
                        <div class="order-item" v-for="item in order.items" :key="item.name">
                            <img :src="item.img" :alt="item.name" />
                            <div class="order-item-info">
                                <p class="order-item-name">{{ item.name }}</p>
                                <p class="order-item-qty">x{{ item.qty }}</p>
                            </div>
                            <p class="order-item-price">{{ item.price }}</p>
                        </div>
                    </div>

                    <div class="order-foot">
                        <span class="order-total">Tổng: <strong>{{ order.total }}</strong></span>
                        <button class="btn-detail" @click="openDetail(order)">Xem chi tiết</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page {
    min-height: 100vh;
    background: #f8fafc;
    padding: 32px 30px;
    font-family: system-ui, sans-serif;
}

.container {
    max-width: 860px;
    margin: auto;
}

.page-header {
    margin-bottom: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
}

.page-sub {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

/* TABS */
.tabs {
    display: flex;
    gap: 6px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 6px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab {
    padding: 8px 16px;
    border-radius: 10px;
    border: none;
    background: transparent;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tab:hover {
    background: #f1f5f9;
    color: #374151;
}

.tab.active {
    background: #2563eb;
    color: #fff;
    font-weight: 600;
}

.tab-count {
    background: rgba(255, 255, 255, 0.25);
    padding: 1px 7px;
    border-radius: 99px;
    font-size: 11px;
}

.tab:not(.active) .tab-count {
    background: #f1f5f9;
    color: #64748b;
}

/* ORDER CARDS */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.empty {
    text-align: center;
    padding: 60px 0;
    color: #94a3b8;
}

.empty svg {
    width: 48px;
    height: 48px;
    stroke: #cbd5e1;
    stroke-width: 1.5;
    fill: none;
    margin-bottom: 12px;
}

.empty p {
    font-size: 14px;
}

.order-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.order-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.order-meta {
    display: flex;
    align-items: center;
    gap: 12px;
}

.order-id {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

.order-date {
    font-size: 12px;
    color: #94a3b8;
}

.order-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 99px;
}

.order-items {
    padding: 12px 20px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 8px 0;
}

.order-item img {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.order-item-info {
    flex: 1;
    min-width: 0;
}

.order-item-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 3px;
}

.order-item-qty {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.order-item-price {
    font-size: 14px;
    font-weight: 700;
    color: #2563eb;
    flex-shrink: 0;
}

.order-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
}

.order-total {
    font-size: 13px;
    color: #64748b;
}

.order-total strong {
    color: #0f172a;
    font-size: 15px;
}

.btn-detail {
    padding: 8px 18px;
    border-radius: 9px;
    background: #fff;
    border: 1.5px solid #2563eb;
    color: #2563eb;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-detail:hover {
    background: #2563eb;
    color: #fff;
}

/* MODAL */
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    z-index: 9000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(3px);
}

.modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 24px 24px 0;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px;
}

.modal-id {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.close-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: #f1f5f9;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.close-btn svg {
    width: 14px;
    height: 14px;
    stroke: #64748b;
    stroke-width: 2.5;
}

.modal-body {
    padding: 20px 24px 24px;
}

.modal-status {
    display: inline-block;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 99px;
    margin-bottom: 20px;
}

/* TIMELINE */
.timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin-bottom: 24px;
}

.tl-item {
    display: grid;
    grid-template-columns: 24px auto 1fr;
    gap: 0 12px;
    align-items: start;
}

.tl-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    z-index: 1;
}

.tl-item.done .tl-dot {
    background: #2563eb;
    border-color: #2563eb;
}

.tl-dot svg {
    width: 12px;
    height: 12px;
    stroke: #fff;
    stroke-width: 3;
    fill: none;
}

.tl-line {
    width: 2px;
    background: #e2e8f0;
    min-height: 28px;
    margin: 0 auto;
    grid-column: 1;
    grid-row: 2;
}

.tl-item.done .tl-line {
    background: #2563eb;
}

.tl-content {
    grid-column: 3;
    grid-row: 1;
    padding-bottom: 20px;
}

.tl-label {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    margin: 3px 0 2px;
}

.tl-date {
    font-size: 11px;
    color: #94a3b8;
    margin: 0;
}

.modal-section {
    margin-bottom: 20px;
}

.section-title {
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 12px;
}

.modal-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 8px;
}

.modal-item img {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.modal-item-info {
    flex: 1;
    min-width: 0;
}

.modal-item-name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 3px;
}

.modal-item-qty {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.modal-item-price {
    font-size: 14px;
    font-weight: 700;
    color: #2563eb;
    flex-shrink: 0;
}

.modal-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0 0;
    border-top: 1px solid #f1f5f9;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
}

.total-val {
    font-size: 18px;
    font-weight: 800;
    color: #2563eb;
}

.fade-enter-active {
    transition: all 0.2s ease;
}

.fade-leave-active {
    transition: all 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>