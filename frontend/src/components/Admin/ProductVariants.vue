<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('Biến thể cấu hình')
const showModal = ref(false)
const formError = ref('')

const typeStyle = {
    'RAM':   { bg: '#dbeafe', color: '#1d4ed8' },
    'Ổ CỨNG': { bg: '#dcfce7', color: '#15803d' },
    'CPU':   { bg: '#ede9fe', color: '#6d28d9' },
}

const variants = ref([
    { id: 1, name: '16GB Unified Memory', type: 'RAM', status: 'Hoạt động' },
    { id: 2, name: '512GB SSD Storage', type: 'Ổ CỨNG', status: 'Hoạt động' },
    { id: 3, name: 'Apple M3 Pro (12-core)', type: 'CPU', status: 'Nháp' },
    { id: 4, name: '1TB NVMe Gen5', type: 'Ổ CỨNG', status: 'Hoạt động' },
])

const colors = ref([
    { id: 1, name: 'Space Black', hex: '#1a1a1a', count: 6, stock: 'Khả dụng' },
    { id: 2, name: 'Silver', hex: '#c0c0c0', count: 4, stock: 'Khả dụng' },
    { id: 3, name: 'Midnight', hex: '#1c2b3a', count: 5, stock: 'Khả dụng' },
    { id: 4, name: 'Starlight', hex: '#e8e0d0', count: 3, stock: 'Hết hàng' },
])

const selectedColor = ref(colors.value[0])

const removeVariant = (i) => variants.value.splice(i, 1)
const removeColor = (i) => colors.value.splice(i, 1)

// MODAL
const modalType = ref('variant') // 'variant' | 'color'
const formError2 = ref('')

const defaultVariantForm = () => ({ name: '', type: 'RAM', status: 'Hoạt động' })
const defaultColorForm = () => ({ name: '', hex: '#000000', stock: 'Khả dụng' })

const variantForm = ref(defaultVariantForm())
const colorForm = ref(defaultColorForm())

const openModal = (type) => {
    modalType.value = type
    variantForm.value = defaultVariantForm()
    colorForm.value = defaultColorForm()
    formError.value = ''
    showModal.value = true
}
const closeModal = () => showModal.value = false

const submitVariant = () => {
    if (!variantForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên biến thể.'; return }
    if (!variantForm.value.type) { formError.value = 'Vui lòng chọn loại thuộc tính.'; return }
    variants.value.unshift({ id: Date.now(), name: variantForm.value.name.trim(), type: variantForm.value.type, status: variantForm.value.status })
    closeModal()
}

const submitColor = () => {
    if (!colorForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên màu.'; return }
    colors.value.unshift({ id: Date.now(), name: colorForm.value.name.trim(), hex: colorForm.value.hex, count: 0, stock: colorForm.value.stock })
    closeModal()
}
</script>

<template>
    <div class="page">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input placeholder="Tìm kiếm hệ thống..." />
            </div>
            <div class="topbar-right">
                <span class="nav-link">Inventory</span>
                <span class="nav-link">Orders</span>
                <span class="nav-link">Analytics</span>
                <span class="nav-link">Customers</span>
                <button class="icon-btn">🔔</button>
                <button class="icon-btn">⚙️</button>
                <div class="av">AV</div>
            </div>
        </div>

        <!-- BREADCRUMB -->
        <div class="breadcrumb">
            <span>Hệ thống</span>
            <span class="sep">›</span>
            <span class="active-crumb">Quản lý sản phẩm</span>
        </div>

        <!-- HEADER -->
        <div class="page-header">
            <div>
                <h1>Quản lý biến thể &amp; Màu sắc</h1>
                <p>Cấu hình các thuộc tính kỹ thuật và dải màu sắc dành cho dòng sản phẩm cao cấp VinaTech 2026.</p>
            </div>
            <button class="btn-new" @click="openModal(activeTab === 'Biến thể cấu hình' ? 'variant' : 'color')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Thêm biến thể mới
            </button>
        </div>

        <!-- TABS -->
        <div class="tabs">
            <button class="tab" :class="{ active: activeTab === 'Biến thể cấu hình' }" @click="activeTab = 'Biến thể cấu hình'">Biến thể cấu hình</button>
            <button class="tab" :class="{ active: activeTab === 'Bảng màu sản phẩm' }" @click="activeTab = 'Bảng màu sản phẩm'">Bảng màu sản phẩm</button>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="main-layout">

            <!-- LEFT -->
            <div class="left-col">

                <!-- VARIANT TABLE -->
                <div v-if="activeTab === 'Biến thể cấu hình'" class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="bar blue"></span>
                            Danh sách biến thể
                        </div>
                        <div class="card-tools">
                            <button class="tool-btn" title="Lọc">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                                </svg>
                            </button>
                            <button class="tool-btn" title="Sắp xếp">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M3 6h18M6 12h12M9 18h6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>TÊN BIẾN THỂ</th>
                                <th>LOẠI THUỘC TÍNH</th>
                                <th>TRẠNG THÁI</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="variants.length === 0">
                                <td colspan="4" class="empty">Chưa có biến thể nào.</td>
                            </tr>
                            <tr v-for="(v, i) in variants" :key="v.id">
                                <td class="variant-name">{{ v.name }}</td>
                                <td>
                                    <span class="type-badge"
                                        :style="{ background: typeStyle[v.type]?.bg, color: typeStyle[v.type]?.color }">
                                        {{ v.type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-dot" :class="v.status === 'Hoạt động' ? 'active' : 'draft'">
                                        ● {{ v.status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button class="act-btn" title="Sửa">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </button>
                                        <button class="act-btn danger" title="Xóa" @click="removeVariant(i)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- COLOR TABLE -->
                <div v-else class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="bar purple"></span>
                            Bảng màu sản phẩm
                        </div>
                        <button class="btn-add-color" @click="openModal('color')">+ Thêm màu</button>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>MÀU</th>
                                <th>TÊN</th>
                                <th>SỐ BIẾN THỂ</th>
                                <th>KHO HÀNG</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(c, i) in colors" :key="c.id" @click="selectedColor = c" style="cursor:pointer">
                                <td>
                                    <div class="color-swatch-cell" :style="{ background: c.hex }"></div>
                                </td>
                                <td class="variant-name">{{ c.name }}</td>
                                <td class="muted">{{ c.count }} biến thể</td>
                                <td>
                                    <span :class="c.stock === 'Khả dụng' ? 'stock-ok' : 'stock-out'">{{ c.stock }}</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button class="act-btn" title="Sửa">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </button>
                                        <button class="act-btn danger" title="Xóa" @click.stop="removeColor(i)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="right-col">

                <!-- COLOR PANEL -->
                <div class="card side-card">
                    <div class="side-header">
                        <div class="card-title" style="font-size:14px">
                            <span class="bar purple"></span> Bảng màu sản phẩm
                        </div>
                        <button class="btn-add-sm" @click="openModal('color')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="width:12px;height:12px">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Thêm màu
                        </button>
                    </div>

                    <div class="color-list">
                        <div
                            v-for="c in colors" :key="c.id"
                            class="color-row-item"
                            :class="{ 'color-selected': selectedColor?.id === c.id }"
                            @click="selectedColor = c"
                        >
                            <div class="color-dot" :style="{ background: c.hex }"></div>
                            <div class="color-info">
                                <b>{{ c.name }}</b>
                                <span>{{ c.hex }}</span>
                            </div>
                            <span :class="c.stock === 'Khả dụng' ? 'stock-ok' : 'stock-out'" style="font-size:11px">{{ c.stock === 'Khả dụng' ? '●' : '○' }}</span>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW CARD -->
                <div class="card preview-card">
                    <div class="preview-label">PREVIEW: VINABOOK PRO 2026</div>
                    <div class="preview-img-wrap">
                        <div class="preview-laptop" :style="{ background: `linear-gradient(135deg, ${selectedColor?.hex || '#1a1a1a'}cc, ${selectedColor?.hex || '#1a1a1a'})` }">
                            <div class="laptop-screen"></div>
                            <div class="laptop-base"></div>
                        </div>
                    </div>
                    <div class="preview-footer">
                        <div>
                            <b>{{ selectedColor?.name || 'Space Black' }} Edition</b>
                            <span>{{ selectedColor?.count || 0 }} quản tế khả dụng</span>
                        </div>
                        <span :class="selectedColor?.stock === 'Khả dụng' ? 'chip-ok' : 'chip-out'">
                            {{ selectedColor?.stock || 'Khả dụng' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- BOTTOM CARDS -->
        <div class="bottom-grid">
            <div class="bottom-card">
                <div class="bottom-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                </div>
                <div>
                    <h4>Xuất dữ liệu</h4>
                    <p>Tải xuống danh sách cấu hình (CSV)</p>
                </div>
            </div>

            <div class="bottom-card">
                <div class="bottom-icon purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
                <div>
                    <h4>Tự động hóa</h4>
                    <p>Gán ý biến thể theo xu hướng thị trường</p>
                </div>
            </div>

            <div class="bottom-card dark-bottom">
                <div class="bottom-icon white-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4l3 3"/>
                    </svg>
                </div>
                <div>
                    <h4>Thư viện màu</h4>
                    <p>Quản lý và mở rộng bộ màu sắc toàn bộ thị tác</p>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <Teleport to="body">
            <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
                <div class="modal">

                    <div class="modal-header">
                        <h3>{{ modalType === 'variant' ? 'Thêm biến thể mới' : 'Thêm màu sắc mới' }}</h3>
                        <button class="modal-close" @click="closeModal">×</button>
                    </div>

                    <div class="modal-body">

                        <!-- VARIANT FORM -->
                        <template v-if="modalType === 'variant'">
                            <div class="form-group">
                                <label>TÊN BIẾN THỂ <span class="req">*</span></label>
                                <input v-model="variantForm.name" placeholder="VD: 16GB Unified Memory" />
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>LOẠI THUỘC TÍNH <span class="req">*</span></label>
                                    <select v-model="variantForm.type">
                                        <option>RAM</option>
                                        <option>Ổ CỨNG</option>
                                        <option>CPU</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>TRẠNG THÁI</label>
                                    <select v-model="variantForm.status">
                                        <option>Hoạt động</option>
                                        <option>Nháp</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        <!-- COLOR FORM -->
                        <template v-else>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>TÊN MÀU <span class="req">*</span></label>
                                    <input v-model="colorForm.name" placeholder="VD: Space Black" />
                                </div>
                                <div class="form-group">
                                    <label>MÃ MÀU</label>
                                    <div class="color-input-wrap">
                                        <div class="color-preview-sm" :style="{ background: colorForm.hex }"></div>
                                        <input v-model="colorForm.hex" type="text" placeholder="#000000" />
                                        <input type="color" v-model="colorForm.hex" class="native-color" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>TRẠNG THÁI KHO</label>
                                <select v-model="colorForm.stock">
                                    <option>Khả dụng</option>
                                    <option>Hết hàng</option>
                                </select>
                            </div>
                        </template>

                        <p v-if="formError" class="form-error">⚠ {{ formError }}</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn-cancel" @click="closeModal">Hủy</button>
                        <button class="btn-submit" @click="modalType === 'variant' ? submitVariant() : submitColor()">
                            {{ modalType === 'variant' ? 'Thêm biến thể' : 'Thêm màu sắc' }}
                        </button>
                    </div>

                </div>
            </div>
        </Teleport>

    </div>
</template>

<style scoped>
* { box-sizing: border-box; }

.page { background: #f5f7fb; min-height: 100vh; font-family: sans-serif; padding-bottom: 40px; }

/* TOPBAR */
.topbar { display: flex; align-items: center; justify-content: space-between; padding: 11px 32px; background: white; border-bottom: 1px solid #f1f5f9; }
.search-box { position: relative; width: 220px; }
.search-box svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 13px; height: 13px; color: #94a3b8; pointer-events: none; }
.search-box input { width: 100%; padding: 7px 12px 7px 30px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px; color: #0f172a; outline: none; background: #f8fafc; }
.topbar-right { display: flex; align-items: center; gap: 6px; }
.nav-link { font-size: 13px; font-weight: 500; color: #475569; padding: 6px 10px; border-radius: 6px; cursor: pointer; }
.nav-link:hover { color: #2563eb; background: #f0f6ff; }
.icon-btn { background: none; border: none; font-size: 15px; cursor: pointer; padding: 6px; border-radius: 7px; }
.icon-btn:hover { background: #f1f5f9; }
.av { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg,#2563eb,#4f46e5); color: white; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; }

/* BREADCRUMB */
.breadcrumb { padding: 16px 32px 0; font-size: 12px; color: #94a3b8; display: flex; gap: 6px; }
.sep { color: #cbd5e1; }
.active-crumb { color: #2563eb; font-weight: 500; }

/* HEADER */
.page-header { display: flex; justify-content: space-between; align-items: flex-start; padding: 12px 32px 16px; }
.page-header h1 { font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 5px; letter-spacing: -0.02em; }
.page-header p { font-size: 13px; color: #64748b; margin: 0; max-width: 420px; line-height: 1.5; }
.btn-new { display: flex; align-items: center; gap: 7px; white-space: nowrap; padding: 10px 18px; border-radius: 10px; border: none; background: linear-gradient(135deg,#2563eb,#4f46e5); color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.2s; }
.btn-new svg { width: 14px; height: 14px; }
.btn-new:hover { opacity: 0.9; transform: translateY(-1px); }

/* TABS */
.tabs { display: flex; gap: 4px; padding: 0 32px 16px; }
.tab { padding: 9px 18px; border-radius: 9px; border: 1px solid #e2e8f0; background: white; font-size: 13px; font-weight: 500; color: #64748b; cursor: pointer; transition: all 0.2s; }
.tab:hover { color: #334155; border-color: #cbd5e1; }
.tab.active { background: #2563eb; border-color: #2563eb; color: white; font-weight: 600; }

/* MAIN LAYOUT */
.main-layout { display: grid; grid-template-columns: 1fr 280px; gap: 16px; padding: 0 32px; }

/* CARD */
.card { background: white; border-radius: 14px; border: 1px solid #f1f5f9; padding: 20px; }
.card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.card-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: #0f172a; }
.bar { width: 4px; height: 16px; border-radius: 2px; flex-shrink: 0; }
.bar.blue { background: #2563eb; }
.bar.purple { background: #7c3aed; }
.card-tools { display: flex; gap: 6px; }
.tool-btn { width: 30px; height: 30px; border-radius: 7px; border: 1px solid #e2e8f0; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.2s; }
.tool-btn svg { width: 13px; height: 13px; }
.tool-btn:hover { background: #f1f5f9; color: #2563eb; }

/* TABLE */
table { width: 100%; border-collapse: collapse; }
thead th { padding: 10px 12px; font-size: 10px; font-weight: 700; color: #94a3b8; text-align: left; letter-spacing: 0.07em; border-bottom: 1px solid #f1f5f9; }
tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbff; }
tbody td { padding: 14px 12px; font-size: 13px; vertical-align: middle; }
.empty { text-align: center; color: #94a3b8; padding: 40px !important; }
.variant-name { font-weight: 600; color: #0f172a; font-size: 13px; }
.muted { color: #64748b; font-size: 13px; }

.type-badge { display: inline-block; font-size: 10px; font-weight: 700; padding: 4px 9px; border-radius: 6px; letter-spacing: 0.04em; }
.status-dot { font-size: 12px; font-weight: 600; }
.status-dot.active { color: #16a34a; }
.status-dot.draft  { color: #d97706; }

.color-swatch-cell { width: 28px; height: 28px; border-radius: 50%; border: 2px solid rgba(0,0,0,0.08); }
.stock-ok  { color: #16a34a; font-size: 12px; font-weight: 600; }
.stock-out { color: #dc2626; font-size: 12px; font-weight: 600; }

.actions { display: flex; gap: 5px; }
.act-btn { width: 28px; height: 28px; border-radius: 7px; border: 1px solid #e2e8f0; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.2s; }
.act-btn svg { width: 12px; height: 12px; }
.act-btn:hover { background: #f1f5f9; color: #2563eb; border-color: #cbd5e1; }
.act-btn.danger:hover { background: #fee2e2; border-color: #fecaca; color: #ef4444; }

/* SIDE CARD */
.side-card { padding: 16px; }
.side-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.btn-add-sm { display: flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; font-size: 11px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s; }
.btn-add-sm:hover { border-color: #2563eb; color: #2563eb; }
.btn-add-color { padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; font-size: 11px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s; }
.btn-add-color:hover { border-color: #2563eb; color: #2563eb; }

.color-list { display: flex; flex-direction: column; gap: 6px; }
.color-row-item { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 9px; cursor: pointer; transition: all 0.2s; border: 1px solid transparent; }
.color-row-item:hover { background: #f8fafc; }
.color-row-item.color-selected { background: #f0f6ff; border-color: #bfdbfe; }
.color-dot { width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0; border: 2px solid rgba(0,0,0,0.08); }
.color-info { flex: 1; }
.color-info b { display: block; font-size: 12px; font-weight: 600; color: #0f172a; }
.color-info span { font-size: 10px; color: #94a3b8; }

/* PREVIEW */
.preview-card { padding: 14px; }
.preview-label { font-size: 9px; font-weight: 700; color: #94a3b8; letter-spacing: 0.1em; margin-bottom: 12px; }
.preview-img-wrap { display: flex; justify-content: center; margin-bottom: 14px; }
.preview-laptop { width: 140px; height: 90px; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; transition: background 0.4s; position: relative; }
.laptop-screen { width: 110px; height: 65px; border-radius: 4px; background: rgba(0,0,0,0.3); border: 2px solid rgba(255,255,255,0.15); }
.laptop-base { width: 120px; height: 6px; border-radius: 0 0 4px 4px; background: rgba(0,0,0,0.2); }
.preview-footer { display: flex; align-items: center; justify-content: space-between; }
.preview-footer b { display: block; font-size: 12px; font-weight: 700; color: #0f172a; }
.preview-footer span { font-size: 10px; color: #94a3b8; display: block; margin-top: 2px; }
.chip-ok  { display: inline-block; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 20px; background: #dcfce7; color: #16a34a; white-space: nowrap; }
.chip-out { display: inline-block; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 20px; background: #fee2e2; color: #dc2626; white-space: nowrap; }

/* BOTTOM */
.bottom-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; padding: 16px 32px 0; }
.bottom-card { background: white; border-radius: 12px; border: 1px solid #f1f5f9; padding: 18px; display: flex; gap: 14px; align-items: flex-start; }
.bottom-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bottom-icon svg { width: 18px; height: 18px; }
.bottom-icon.blue   { background: #dbeafe; color: #2563eb; }
.bottom-icon.purple { background: #ede9fe; color: #7c3aed; }
.bottom-card h4 { font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
.bottom-card p  { font-size: 12px; color: #64748b; margin: 0; line-height: 1.4; }
.dark-bottom { background: linear-gradient(135deg,#1e40af,#4f46e5); border-color: transparent; }
.dark-bottom h4 { color: white; }
.dark-bottom p  { color: rgba(255,255,255,0.7); }
.white-icon { background: rgba(255,255,255,0.15); color: white; }

/* MODAL */
.modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.55); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
.modal { background: white; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 24px 60px rgba(0,0,0,0.18); animation: modalIn 0.22s cubic-bezier(.22,1,.36,1); }
@keyframes modalIn { from { opacity:0; transform:translateY(14px) scale(0.97); } to { opacity:1; transform:translateY(0) scale(1); } }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px 14px; border-bottom: 1px solid #f1f5f9; }
.modal-header h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; }
.modal-close { background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 0; line-height: 1; }
.modal-close:hover { color: #0f172a; }
.modal-body { padding: 18px 22px; display: flex; flex-direction: column; gap: 14px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 10px; font-weight: 700; color: #94a3b8; letter-spacing: 0.08em; }
.req { color: #ef4444; }
.form-group input, .form-group select { padding: 9px 11px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; outline: none; background: #f8fafc; transition: border-color 0.2s; }
.form-group input:focus, .form-group select:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
.color-input-wrap { display: flex; align-items: center; gap: 8px; padding: 4px 11px 4px 4px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; }
.color-preview-sm { width: 28px; height: 28px; border-radius: 6px; flex-shrink: 0; border: 1px solid rgba(0,0,0,0.08); }
.color-input-wrap input[type="text"] { flex: 1; border: none; background: transparent; outline: none; font-size: 13px; color: #0f172a; padding: 0; }
.native-color { width: 22px; height: 22px; border: none; padding: 0; cursor: pointer; background: none; border-radius: 4px; }
.form-error { font-size: 12px; color: #ef4444; background: #fef2f2; border: 1px solid #fecaca; padding: 8px 12px; border-radius: 8px; }
.modal-footer { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 22px 18px; border-top: 1px solid #f1f5f9; }
.btn-cancel { padding: 9px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; }
.btn-cancel:hover { background: #f8fafc; }
.btn-submit { padding: 9px 20px; border-radius: 8px; border: none; background: linear-gradient(135deg,#2563eb,#4f46e5); color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
.btn-submit:hover { opacity: 0.9; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .main-layout { grid-template-columns: 1fr; }
    .right-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .bottom-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
    .page-header { flex-direction: column; gap: 12px; padding: 12px 16px 14px; }
    .main-layout, .bottom-grid { padding: 0 16px; }
    .tabs { padding: 0 16px 14px; }
    .bottom-grid { grid-template-columns: 1fr; }
    .right-col { grid-template-columns: 1fr; }
    .topbar { padding: 10px 16px; }
    .breadcrumb { padding: 14px 16px 0; }
    .form-row { grid-template-columns: 1fr; }
}
</style>