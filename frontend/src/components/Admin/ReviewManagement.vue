<template>
    <div class="page">
        <div class="container">

            <!-- ── Top Header ── -->
            <div class="top-header">
                <div class="header-left">
                    <h1 class="page-title">Quản lý Bình luận<br />&amp; Đánh giá</h1>
                    <p class="page-sub">Theo dõi và phản hồi các đánh giá từ khách hàng của VinaTech</p>
                </div>
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-label">TỔNG CỘNG</div>
                        <div class="stat-value">1,284</div>
                    </div>
                    <div class="stat-card highlight">
                        <div class="stat-label">MỚI NHẤT</div>
                        <div class="stat-value">42</div>
                    </div>
                    <div class="stat-card gold">
                        <div class="stat-label">ĐÁNH GIÁ TB</div>
                        <div class="stat-value">4.8 <span class="star">★</span></div>
                    </div>
                </div>
            </div>

            <!-- ── Table Card ── -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">Danh sách đánh giá gần đây</h3>
                    <div class="filter-tabs">
                        <button v-for="tab in tabs" :key="tab.key" class="tab-btn"
                            :class="{ active: activeTab === tab.key }" @click="activeTab = tab.key">{{ tab.label
                            }}</button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>KHÁCH HÀNG</th>
                                <th>SẢN PHẨM</th>
                                <th>ĐÁNH GIÁ</th>
                                <th>NỘI DUNG</th>
                                <th>NGÀY</th>
                                <th>TRẠNG THÁI</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="review in filteredReviews" :key="review.id" class="review-row">

                                <!-- Customer -->
                                <td class="td-customer">
                                    <div class="customer-info">
                                        <div class="avatar" :style="{ background: review.avatarColor }">
                                            <span v-if="review.avatar">{{ review.avatar }}</span>
                                            <svg v-else viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="customer-name" :class="{ anonymous: review.anonymous }">{{
                                                review.name }}</div>
                                            <div class="customer-email">{{ review.email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Product -->
                                <td><span class="product-link">{{ review.product }}</span></td>

                                <!-- Stars -->
                                <td>
                                    <div class="stars">
                                        <span v-for="s in 5" :key="s" class="star-icon"
                                            :class="s <= review.stars ? 'filled' : 'empty'">★</span>
                                    </div>
                                </td>

                                <!-- Content -->
                                <td>
                                    <p class="review-text" :class="{ spam: review.status === 'spam' }">{{ review.content
                                        }}</p>
                                </td>

                                <!-- Date -->
                                <td><span class="date-text">{{ review.date }}</span></td>

                                <!-- Status -->
                                <td>
                                    <span class="status-badge" :class="review.status">
                                        {{ statusLabel(review.status) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="action-btns">
                                        <button v-if="review.status === 'pending'" class="action-btn approve"
                                            @click="approveReview(review)">DUYỆT<br />NGAY</button>

                                        <button class="action-btn icon-btn reply" title="Trả lời">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                            </svg>
                                        </button>

                                        <button class="action-btn icon-btn delete" title="Xoá"
                                            @click="deleteReview(review.id)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14H6L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M9 6V4h6v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <span class="page-info">Hiển thị 1 – {{ filteredReviews.length }} trong tổng số 1,284 bình
                        luận</span>
                    <div class="page-btns">
                        <button class="page-btn arrow">&lt;</button>
                        <button v-for="p in 4" :key="p" class="page-btn" :class="{ active: currentPage === p }"
                            @click="currentPage = p">{{ p }}</button>
                        <span class="page-dots">...</span>
                        <button class="page-btn">42</button>
                        <button class="page-btn arrow">&gt;</button>
                    </div>
                </div>
            </div>

            <!-- ── Bottom Banners ── -->
            <div class="bottom-row">
                <div class="banner-card ai-ready">
                    <div class="banner-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M9 12l2 2 4-4" />
                            <path d="M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z" />
                        </svg>
                    </div>
                    <h4>Tất cả đã sẵn sàng!</h4>
                    <p>Hiện không có bình luận nào cần kiểm duyệt gấp. Hệ thống AI đang tự động lọc các nội dung spam
                        thô tục.</p>
                    <button class="banner-btn outline">KIỂM TRA BỘ LỌC AI</button>
                </div>

                <div class="banner-card smart-reply">
                    <div class="banner-badge">✦ AI</div>
                    <h4>Trợ lý AI<br />Smart Reply</h4>
                    <p>Tự động soạn thảo câu trả lời dựa trên nội dung khách hàng bình luận.</p>
                    <button class="banner-btn primary">KÍCH HOẠT NGAY</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'ReviewManagement',

    data() {
        return {
            activeTab: 'all',
            currentPage: 1,

            tabs: [
                { key: 'all', label: 'Tất cả' },
                { key: 'pending', label: 'Chờ duyệt' },
                { key: 'spam', label: 'Spam' },
            ],

            reviews: [
                {
                    id: 1,
                    name: 'Lê Minh Hoàng',
                    email: 'hoang.lm@email.com',
                    avatar: 'L',
                    avatarColor: '#4f8ef7',
                    anonymous: false,
                    product: 'ZenBook Pro 16X',
                    stars: 5,
                    content: 'Máy chạy cực mượt, màn hình OLED 4K quá đẹp cho dân design. Hàng VinaTech chuẩn 100%.',
                    date: '12 TH04, 2024',
                    status: 'approved',
                },
                {
                    id: 2,
                    name: 'Nguyễn Thảo Vy',
                    email: 'thaovy.ng@email.com',
                    avatar: 'N',
                    avatarColor: '#f97316',
                    anonymous: false,
                    product: 'AirPods Pro Gen 3',
                    stars: 4,
                    content: 'Chất âm hay, chống ồn tốt nhưng hộp sạc hơi dễ trầy xước. Shop giao hàng rất nhanh.',
                    date: '11 TH04, 2024',
                    status: 'pending',
                },
                {
                    id: 3,
                    name: 'Người dùng ẩn danh',
                    email: 'unknown@spam.net',
                    avatar: null,
                    avatarColor: '#94a3b8',
                    anonymous: true,
                    product: 'iPhone 15 Pro Max',
                    stars: 0,
                    content: 'Click vào link này để nhận: Khuyến mãi không: bit.ly/spam-link-example',
                    date: '10 TH04, 2024',
                    status: 'spam',
                },
            ],
        }
    },

    computed: {
        filteredReviews() {
            if (this.activeTab === 'all') return this.reviews
            return this.reviews.filter(r => r.status === this.activeTab)
        },
    },

    methods: {
        statusLabel(status) {
            const map = { approved: 'ĐÃ DUYỆT', pending: 'CHỜ DUYỆT', spam: 'SPAM' }
            return map[status] || status
        },
        approveReview(review) {
            review.status = 'approved'
        },
        deleteReview(id) {
            this.reviews = this.reviews.filter(r => r.id !== id)
        },
    },
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');

*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ── Layout ── */
.page {
    font-family: 'Be Vietnam Pro', sans-serif;
    background: #f0f2f7;
    min-height: 100vh;
    padding: 32px 16px 48px;
    color: #1a1a2e;
}

.container {
    max-width: 1060px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ── Header ── */
.top-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.25;
    color: #0f172a;
}

.page-sub {
    font-size: 13px;
    color: #64748b;
    margin-top: 6px;
    max-width: 240px;
}

/* ── Stat Cards ── */
.stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 14px 22px;
    text-align: center;
    min-width: 110px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
}

.stat-card.highlight {
    background: #1e293b;
}

.stat-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .8px;
    color: #94a3b8;
    margin-bottom: 6px;
}

.stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}

.stat-card.highlight .stat-value {
    color: #fff;
}

.stat-card.gold .stat-value {
    color: #f59e0b;
}

.star {
    font-size: 18px;
}

/* ── Table Card ── */
.table-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
    overflow: hidden;
}

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px 14px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 10px;
}

.table-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

/* Filter tabs */
.filter-tabs {
    display: flex;
    gap: 6px;
}

.tab-btn {
    padding: 6px 16px;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all .18s;
}

.tab-btn.active,
.tab-btn:hover {
    background: #1e293b;
    border-color: #1e293b;
    color: #fff;
}

/* Table */
.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead tr {
    background: #f8fafc;
}

th {
    padding: 10px 16px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .6px;
    color: #94a3b8;
    text-align: left;
    white-space: nowrap;
}

td {
    padding: 14px 16px;
    vertical-align: middle;
}

.review-row {
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
}

.review-row:last-child {
    border-bottom: none;
}

.review-row:hover {
    background: #f8fafc;
}

/* Customer cell */
.customer-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 160px;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    color: #fff;
    flex-shrink: 0;
}

.avatar svg {
    width: 20px;
    height: 20px;
}

.customer-name {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
}

.customer-name.anonymous {
    color: #94a3b8;
    font-style: italic;
}

.customer-email {
    font-size: 11.5px;
    color: #94a3b8;
}

/* Product */
.product-link {
    font-size: 12.5px;
    font-weight: 600;
    color: #3b82f6;
    white-space: nowrap;
}

/* Stars */
.stars {
    display: flex;
    gap: 1px;
}

.star-icon {
    font-size: 14px;
}

.star-icon.filled {
    color: #f59e0b;
}

.star-icon.empty {
    color: #e2e8f0;
}

/* Review text */
.review-text {
    font-size: 12.5px;
    color: #475569;
    max-width: 220px;
    line-height: 1.5;
}

.review-text.spam {
    color: #ef4444;
}

/* Date */
.date-text {
    font-size: 12px;
    color: #94a3b8;
    white-space: nowrap;
}

/* Status badge */
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .4px;
    white-space: nowrap;
}

.status-badge.approved {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.pending {
    background: #fef9c3;
    color: #ca8a04;
}

.status-badge.spam {
    background: #fee2e2;
    color: #dc2626;
}

/* Action buttons */
.action-btns {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    font-size: 10px;
    font-weight: 700;
    transition: all .18s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn.approve {
    padding: 6px 10px;
    background: #1e293b;
    color: #fff;
    line-height: 1.3;
    text-align: center;
    letter-spacing: .4px;
}

.action-btn.approve:hover {
    background: #0f172a;
}

.action-btn.icon-btn {
    width: 30px;
    height: 30px;
    padding: 0;
}

.action-btn.icon-btn svg {
    width: 14px;
    height: 14px;
}

.action-btn.reply {
    background: #eff6ff;
    color: #3b82f6;
}

.action-btn.reply:hover {
    background: #dbeafe;
}

.action-btn.delete {
    background: #fee2e2;
    color: #ef4444;
}

.action-btn.delete:hover {
    background: #fecaca;
}

/* Pagination */
.pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 24px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 10px;
}

.page-info {
    font-size: 12.5px;
    color: #94a3b8;
}

.page-btns {
    display: flex;
    gap: 4px;
    align-items: center;
}

.page-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.page-btn:hover {
    border-color: #1e293b;
    color: #1e293b;
}

.page-btn.active {
    background: #1e293b;
    border-color: #1e293b;
    color: #fff;
}

.page-btn.arrow {
    font-size: 11px;
}

.page-dots {
    font-size: 13px;
    color: #94a3b8;
    padding: 0 4px;
}

/* ── Bottom Banners ── */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.banner-card {
    border-radius: 16px;
    padding: 28px 28px 24px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}

.banner-card.ai-ready {
    background: #fff;
    box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
}

.banner-card.smart-reply {
    background: #0f172a;
    color: #fff;
}

.banner-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #eff6ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
}

.banner-icon svg {
    width: 22px;
    height: 22px;
}

.banner-badge {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    color: #a78bfa;
    background: rgba(167, 139, 250, .12);
    padding: 3px 10px;
    border-radius: 20px;
}

.banner-card h4 {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
}

.banner-card.smart-reply h4 {
    color: #fff;
}

.banner-card p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.6;
}

.banner-card.smart-reply p {
    color: #94a3b8;
}

.banner-btn {
    margin-top: 4px;
    padding: 9px 20px;
    border-radius: 24px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .4px;
    cursor: pointer;
    transition: all .18s;
}

.banner-btn.outline {
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #475569;
}

.banner-btn.outline:hover {
    border-color: #1e293b;
    color: #1e293b;
}

.banner-btn.primary {
    border: none;
    background: #3b82f6;
    color: #fff;
}

.banner-btn.primary:hover {
    background: #2563eb;
    transform: scale(1.03);
}

/* ── Responsive ── */
@media (max-width: 600px) {
    .bottom-row {
        grid-template-columns: 1fr;
    }

    .stats-row {
        flex-wrap: wrap;
    }
}
</style>