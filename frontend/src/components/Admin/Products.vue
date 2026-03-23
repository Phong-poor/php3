<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedStatus = ref('')

const products = ref([
    {
        name: 'VinaPro Laptop X1',
        sku: 'VN-LP-2026',
        category: 'Máy tính xách tay',
        price: '45.990.000',
        stock: 124,
        status: 'Đang bán',
        img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
    },
    {
        name: 'VinaPhone Ultra S',
        sku: 'VN-PH-2026',
        category: 'Điện thoại',
        price: '22.500.000',
        stock: 12,
        status: 'Nháp',
        img: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200'
    },
    {
        name: 'VinaTab Air G3',
        sku: 'VN-TB-2026',
        category: 'Máy tính bảng',
        price: '15.200.000',
        stock: 56,
        status: 'Đang bán',
        img: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=200'
    },
    {
        name: 'VinaSound Studio H1',
        sku: 'VN-AC-2026',
        category: 'Phụ kiện',
        price: '5.800.000',
        stock: 210,
        status: 'Đang bán',
        img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200'
    },
])

const filteredProducts = computed(() =>
    products.value.filter(p => {
        const matchSearch =
            p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            p.sku.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchStatus = !selectedStatus.value || p.status === selectedStatus.value
        return matchSearch && matchStatus
    })
)

const stockPercent = (stock) => Math.min((stock / 250) * 100, 100)
const removeProduct = (index) => products.value.splice(index, 1)

/* ===== MODAL ===== */
const showModal = ref(false)
const formError = ref('')
const imgPreview = ref('')
const fileInputRef = ref(null)

const defaultForm = () => ({
    name: '', sku: '', category: '', price: '', stock: '', status: 'Đang bán', img: ''
})
const form = ref(defaultForm())

const onFileChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    const reader = new FileReader()
    reader.onload = (ev) => {
        imgPreview.value = ev.target.result
        form.value.img = ev.target.result
    }
    reader.readAsDataURL(file)
}

const triggerFileInput = () => fileInputRef.value.click()

const removeImg = () => {
    imgPreview.value = ''
    form.value.img = ''
    if (fileInputRef.value) fileInputRef.value.value = ''
}

const openModal = () => {
    form.value = defaultForm()
    imgPreview.value = ''
    formError.value = ''
    showModal.value = true
}

const closeModal = () => { showModal.value = false }

const submitForm = () => {
    if (!form.value.name.trim())  { formError.value = 'Vui lòng nhập tên sản phẩm.'; return }
    if (!form.value.sku.trim())   { formError.value = 'Vui lòng nhập SKU.'; return }
    if (!form.value.category)     { formError.value = 'Vui lòng chọn danh mục.'; return }
    if (!form.value.price.trim()) { formError.value = 'Vui lòng nhập giá.'; return }
    if (!form.value.stock)        { formError.value = 'Vui lòng nhập số lượng kho.'; return }

    products.value.unshift({
        name:     form.value.name.trim(),
        sku:      form.value.sku.trim(),
        category: form.value.category,
        price:    form.value.price.trim(),
        stock:    Number(form.value.stock),
        status:   form.value.status,
        img:      form.value.img || 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
    })
    closeModal()
}
</script>

<template>
    <div class="admin">

        <!-- HEADER -->
        <div class="top">
            <div>
                <h1>Quản lý sản phẩm</h1>
                <p>Cập nhật và theo dõi danh mục thiết bị công nghệ 2026</p>
            </div>
            <button class="add-btn" @click="openModal">+ Thêm sản phẩm</button>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="stat-card">
                <span class="stat-icon blue">📦</span>
                <div><p>Tổng sản phẩm</p><b>1,284</b></div>
            </div>
            <div class="stat-card">
                <span class="stat-icon green">🔥</span>
                <div><p>Bán chạy</p><b>312</b></div>
            </div>
            <div class="stat-card">
                <span class="stat-icon red">⚠️</span>
                <div><p>Sắp hết hàng</p><b>18</b></div>
            </div>
            <div class="stat-card">
                <span class="stat-icon purple">🏭</span>
                <div><p>Kho lưu trữ</p><b>45</b></div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-bar">
            <div class="search-wrap">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" />
                </svg>
                <input v-model="searchQuery" placeholder="Tìm kiếm tên sản phẩm, SKU..." />
            </div>
            <select v-model="selectedStatus">
                <option value="">Tất cả trạng thái</option>
                <option>Đang bán</option>
                <option>Nháp</option>
            </select>
            <select>
                <option>Tất cả danh mục</option>
                <option>Máy tính xách tay</option>
                <option>Điện thoại</option>
                <option>Máy tính bảng</option>
                <option>Phụ kiện</option>
            </select>
        </div>

        <!-- TABLE -->
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá niêm yết</th>
                        <th>Kho hàng</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filteredProducts.length === 0">
                        <td colspan="6" class="empty">Không tìm thấy sản phẩm nào.</td>
                    </tr>
                    <tr v-for="(p, i) in filteredProducts" :key="i">
                        <td>
                            <div class="product-cell">
                                <img :src="p.img" :alt="p.name" />
                                <div><b>{{ p.name }}</b><span>SKU: {{ p.sku }}</span></div>
                            </div>
                        </td>
                        <td><span class="badge">{{ p.category }}</span></td>
                        <td><span class="price">{{ p.price }} ₫</span></td>
                        <td>
                            <div class="stock-cell">
                                <span>{{ p.stock }}</span>
                                <div class="bar">
                                    <div class="fill" :style="{ width: stockPercent(p.stock) + '%' }"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge" :class="p.status === 'Đang bán' ? 'active' : 'draft'">
                                {{ p.status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <button class="act-btn" title="Xem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                                <button class="act-btn" title="Sửa">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <button class="act-btn danger" title="Xóa" @click="removeProduct(i)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pagination">
            <button>‹</button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>›</button>
        </div>

        <!-- MODAL THÊM SẢN PHẨM -->
        <Teleport to="body">
            <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
                <div class="modal">

                    <div class="modal-header">
                        <h3>Thêm sản phẩm mới</h3>
                        <button class="modal-close" @click="closeModal">×</button>
                    </div>

                    <div class="modal-body">

                        <!-- UPLOAD ẢNH -->
                        <div class="form-group">
                            <label>Ảnh sản phẩm</label>

                            <!-- input file ẩn -->
                            <input
                                ref="fileInputRef"
                                type="file"
                                accept="image/*"
                                style="display:none"
                                @change="onFileChange"
                            />

                            <!-- Chưa có ảnh: vùng kéo thả -->
                            <div v-if="!imgPreview" class="upload-zone" @click="triggerFileInput">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                <p>Kéo thả hoặc <span>bấm để chọn ảnh</span></p>
                                <small>PNG, JPG, WEBP — tối đa 5MB</small>
                            </div>

                            <!-- Đã có ảnh: hiển thị preview -->
                            <div v-else class="img-preview-wrap">
                                <img :src="imgPreview" class="img-preview" alt="preview" />
                                <div class="img-actions">
                                    <button class="img-change" @click="triggerFileInput">Đổi ảnh</button>
                                    <button class="img-remove-btn" @click="removeImg">Xóa</button>
                                </div>
                            </div>
                        </div>

                        <!-- THÔNG TIN -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tên sản phẩm <span class="required">*</span></label>
                                <input v-model="form.name" placeholder="VD: VinaPro Laptop X2" />
                            </div>
                            <div class="form-group">
                                <label>SKU <span class="required">*</span></label>
                                <input v-model="form.sku" placeholder="VD: VN-LP-2027" />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Danh mục <span class="required">*</span></label>
                                <select v-model="form.category">
                                    <option value="">-- Chọn danh mục --</option>
                                    <option>Máy tính xách tay</option>
                                    <option>Điện thoại</option>
                                    <option>Máy tính bảng</option>
                                    <option>Phụ kiện</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select v-model="form.status">
                                    <option>Đang bán</option>
                                    <option>Nháp</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Giá niêm yết <span class="required">*</span></label>
                                <input v-model="form.price" placeholder="VD: 45.990.000" />
                            </div>
                            <div class="form-group">
                                <label>Số lượng kho <span class="required">*</span></label>
                                <input v-model="form.stock" type="number" min="0" placeholder="VD: 100" />
                            </div>
                        </div>

                        <p v-if="formError" class="form-error">⚠ {{ formError }}</p>

                    </div>

                    <div class="modal-footer">
                        <button class="btn-cancel" @click="closeModal">Hủy</button>
                        <button class="btn-submit" @click="submitForm">Thêm sản phẩm</button>
                    </div>

                </div>
            </div>
        </Teleport>

    </div>
</template>

<style scoped>
.admin {
    padding: 32px 48px;
    background: #f5f7fb;
    min-height: 100vh;
    font-family: sans-serif;
}

/* HEADER */
.top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
.top h1 { font-size: 24px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
.top p  { font-size: 13px; color: #64748b; margin: 0; }
.add-btn {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; border: none; padding: 10px 20px; border-radius: 10px;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.2s;
}
.add-btn:hover { opacity: 0.9; transform: translateY(-1px); }

/* STATS */
.stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
.stat-card {
    background: white; border-radius: 12px; padding: 18px 20px;
    display: flex; align-items: center; gap: 14px; border: 1px solid #f1f5f9;
}
.stat-card p { font-size: 12px; color: #64748b; margin: 0 0 4px; }
.stat-card b { font-size: 22px; font-weight: 700; color: #0f172a; }
.stat-icon { font-size: 22px; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon.blue   { background: #dbeafe; }
.stat-icon.green  { background: #dcfce7; }
.stat-icon.red    { background: #fee2e2; }
.stat-icon.purple { background: #ede9fe; }

/* FILTER */
.filter-bar { display: flex; gap: 10px; margin-bottom: 16px; }
.search-wrap { flex: 1; position: relative; }
.search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #94a3b8; pointer-events: none; }
.search-wrap input { width: 100%; padding: 10px 14px 10px 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; font-size: 13px; color: #0f172a; outline: none; transition: border-color 0.2s; box-sizing: border-box; }
.search-wrap input:focus { border-color: #2563eb; }
.filter-bar select { padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; font-size: 13px; color: #334155; outline: none; cursor: pointer; min-width: 160px; }
.filter-bar select:focus { border-color: #2563eb; }

/* TABLE */
.table-wrap { background: white; border-radius: 14px; border: 1px solid #f1f5f9; overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
thead tr { background: #f8fafc; }
thead th { padding: 13px 16px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; letter-spacing: 0.04em; border-bottom: 1px solid #f1f5f9; }
tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbff; }
tbody td { padding: 14px 16px; font-size: 13px; color: #334155; vertical-align: middle; }
.empty { text-align: center; color: #94a3b8; padding: 40px !important; }

.product-cell { display: flex; align-items: center; gap: 12px; }
.product-cell img { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
.product-cell b { display: block; font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 2px; }
.product-cell span { font-size: 11px; color: #94a3b8; }

.badge { display: inline-block; font-size: 11px; font-weight: 500; padding: 4px 10px; border-radius: 6px; background: #dbeafe; color: #1d4ed8; }
.price { font-weight: 600; color: #0f172a; }

.stock-cell span { font-size: 13px; font-weight: 600; color: #0f172a; display: block; margin-bottom: 5px; }
.bar { height: 4px; background: #e2e8f0; border-radius: 2px; width: 80px; }
.fill { height: 100%; background: #2563eb; border-radius: 2px; transition: width 0.4s ease; }

.status-badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; letter-spacing: 0.03em; }
.status-badge.active { background: #dcfce7; color: #16a34a; }
.status-badge.draft  { background: #f1f5f9; color: #64748b; }

.actions { display: flex; gap: 6px; }
.act-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.2s; }
.act-btn svg { width: 14px; height: 14px; }
.act-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: #2563eb; }
.act-btn.danger:hover { background: #fee2e2; border-color: #fecaca; color: #ef4444; }

/* PAGINATION */
.pagination { display: flex; gap: 6px; margin-top: 20px; justify-content: flex-end; }
.pagination button { width: 34px; height: 34px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; cursor: pointer; color: #334155; transition: all 0.2s; }
.pagination button:hover { border-color: #2563eb; color: #2563eb; }
.pagination .active { background: #2563eb; border-color: #2563eb; color: white; }

/* ===== MODAL ===== */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(15,23,42,0.55);
    display: flex; align-items: center; justify-content: center;
    z-index: 1000; padding: 20px;
}
.modal {
    background: white; border-radius: 16px; width: 100%; max-width: 600px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.18);
    animation: modalIn 0.22s cubic-bezier(.22,1,.36,1);
    max-height: 90vh; overflow-y: auto;
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.modal-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 24px 16px; border-bottom: 1px solid #f1f5f9;
    position: sticky; top: 0; background: white; z-index: 1;
}
.modal-header h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; }
.modal-close { background: none; border: none; font-size: 22px; color: #94a3b8; cursor: pointer; line-height: 1; padding: 0; transition: color 0.2s; }
.modal-close:hover { color: #0f172a; }

.modal-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 16px; }

/* UPLOAD */
.upload-zone {
    border: 2px dashed #cbd5e1; border-radius: 10px; padding: 32px 20px;
    text-align: center; cursor: pointer; transition: border-color 0.2s, background 0.2s;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.upload-zone:hover { border-color: #2563eb; background: #f0f6ff; }
.upload-zone svg { width: 40px; height: 40px; color: #94a3b8; }
.upload-zone p { font-size: 14px; color: #475569; margin: 0; }
.upload-zone p span { color: #2563eb; font-weight: 600; }
.upload-zone small { font-size: 12px; color: #94a3b8; }

/* PREVIEW */
.img-preview-wrap {
    display: flex; align-items: center; gap: 16px;
    background: #f8fafc; border-radius: 10px; padding: 14px;
    border: 1px solid #e2e8f0;
}
.img-preview { width: 90px; height: 90px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.img-actions { display: flex; flex-direction: column; gap: 8px; }
.img-change {
    padding: 7px 14px; border-radius: 7px; border: 1px solid #e2e8f0;
    background: white; font-size: 12px; font-weight: 600; color: #475569;
    cursor: pointer; transition: all 0.2s;
}
.img-change:hover { border-color: #2563eb; color: #2563eb; }
.img-remove-btn {
    padding: 7px 14px; border-radius: 7px; border: 1px solid #fecaca;
    background: #fef2f2; font-size: 12px; font-weight: 600; color: #ef4444;
    cursor: pointer; transition: all 0.2s;
}
.img-remove-btn:hover { background: #fee2e2; }

/* FORM */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 12px; font-weight: 600; color: #475569; letter-spacing: 0.03em; }
.required { color: #ef4444; }
.form-group input,
.form-group select {
    padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0;
    font-size: 13px; color: #0f172a; outline: none; transition: border-color 0.2s; background: #fff;
}
.form-group input:focus,
.form-group select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }

.form-error {
    font-size: 12px; color: #ef4444; background: #fef2f2;
    border: 1px solid #fecaca; padding: 8px 12px; border-radius: 8px; margin: 0;
}

.modal-footer {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 16px 24px 20px; border-top: 1px solid #f1f5f9;
    position: sticky; bottom: 0; background: white;
}
.btn-cancel {
    padding: 10px 20px; border-radius: 8px; border: 1px solid #e2e8f0;
    background: white; font-size: 13px; font-weight: 600; color: #475569;
    cursor: pointer; transition: all 0.2s;
}
.btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }
.btn-submit {
    padding: 10px 22px; border-radius: 8px; border: none;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: opacity 0.2s, transform 0.2s;
}
.btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

/* RESPONSIVE */
@media (max-width: 768px) {
    .admin { padding: 20px 16px; }
    .stats { grid-template-columns: repeat(2,1fr); }
    .filter-bar { flex-wrap: wrap; }
    .table-wrap { overflow-x: auto; }
    table { min-width: 700px; }
    .form-row { grid-template-columns: 1fr; }
}
</style>