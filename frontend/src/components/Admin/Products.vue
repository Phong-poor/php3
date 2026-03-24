<script setup>
import { ref, computed } from 'vue'

/* ═══════════════════════════════════════
   DANH SÁCH SẢN PHẨM
═══════════════════════════════════════ */
const searchQuery = ref('')
const selectedStatus = ref('')

const products = ref([
  { name: 'VinaPro Laptop X1', sku: 'VN-LP-2026', category: 'Máy tính xách tay', price: '45.990.000', stock: 124, status: 'Đang bán', img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200' },
  { name: 'VinaPhone Ultra S', sku: 'VN-PH-2026', category: 'Điện thoại', price: '22.500.000', stock: 12, status: 'Nháp', img: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200' },
  { name: 'VinaTab Air G3', sku: 'VN-TB-2026', category: 'Máy tính bảng', price: '15.200.000', stock: 56, status: 'Đang bán', img: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=200' },
  { name: 'VinaSound Studio H1', sku: 'VN-AC-2026', category: 'Phụ kiện', price: '5.800.000', stock: 210, status: 'Đang bán', img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200' },
])

const filteredProducts = computed(() =>
  products.value.filter(p => {
    const s = searchQuery.value.toLowerCase()
    return (!s || p.name.toLowerCase().includes(s) || p.sku.toLowerCase().includes(s))
      && (!selectedStatus.value || p.status === selectedStatus.value)
  })
)
const stockPercent = s => Math.min((s / 250) * 100, 100)
const removeProduct = i => products.value.splice(i, 1)

/* ═══════════════════════════════════════
   DỮ LIỆU NHÓM & LOẠI THUỘC TÍNH
   Logic: chọn 1 NHÓM → hiện TẤT CẢ loại
   thuộc tính của nhóm đó thành CỘT trong
   bảng biến thể. Mỗi DÒNG = 1 cấu hình
   đầy đủ (CPU + RAM + SSD + GPU + ...)
═══════════════════════════════════════ */
const attributeGroups = ref([
  {
    id: 1, name: 'Cấu hình Laptop', icon: '💻',
    attrTypes: [
      { id: 'cpu', label: 'CPU', options: ['Intel Core i5-13500H', 'Intel Core i7-13700H', 'Intel Core i9-14900H', 'Apple M3', 'Apple M3 Pro', 'Apple M3 Max', 'AMD Ryzen 5 7535HS', 'AMD Ryzen 7 7745HX'] },
      { id: 'ram', label: 'RAM', options: ['8GB DDR5', '16GB DDR5', '32GB DDR5', '64GB DDR5', '16GB Unified', '32GB Unified', '48GB Unified', '96GB Unified'] },
      { id: 'ssd', label: 'SSD', options: ['256GB NVMe', '512GB NVMe', '1TB NVMe Gen5', '2TB NVMe Gen5'] },
      { id: 'gpu', label: 'GPU', options: ['Intel Iris Xe', 'NVIDIA RTX 4060', 'NVIDIA RTX 4070', 'NVIDIA RTX 4080', 'NVIDIA RTX 4090', 'AMD Radeon RX 7600M', 'Apple GPU 18-core', 'Apple GPU 40-core'] },
      { id: 'os', label: 'OS', options: ['Windows 11 Home', 'Windows 11 Pro', 'macOS Sonoma', 'Ubuntu 24.04', 'Không có OS'] },
    ]
  },
  {
    id: 2, name: 'Điện thoại', icon: '📱',
    attrTypes: [
      { id: 'chip', label: 'Chip', options: ['Snapdragon 8 Gen 3', 'Dimensity 9300', 'Apple A17 Pro', 'Apple A16 Bionic', 'Exynos 2400'] },
      { id: 'ram', label: 'RAM', options: ['8GB', '12GB', '16GB'] },
      { id: 'storage', label: 'Bộ nhớ', options: ['128GB', '256GB', '512GB', '1TB'] },
      { id: 'network', label: 'Mạng', options: ['4G LTE', '5G Sub-6', '5G mmWave'] },
    ]
  },
  {
    id: 3, name: 'Máy tính bảng', icon: '📟',
    attrTypes: [
      { id: 'chip', label: 'Chip', options: ['Apple M2', 'Apple M4', 'Snapdragon 8s Gen 3', 'Dimensity 9000'] },
      { id: 'ram', label: 'RAM', options: ['8GB', '16GB', '32GB'] },
      { id: 'storage', label: 'Bộ nhớ', options: ['128GB', '256GB', '512GB', '1TB', '2TB'] },
      { id: 'connect', label: 'Kết nối', options: ['WiFi only', 'WiFi + 5G', 'WiFi + 4G'] },
      { id: 'screen', label: 'Màn hình', options: ['11 inch LCD', '11 inch OLED', '13 inch OLED', '13 inch Mini-LED'] },
    ]
  },
  {
    id: 4, name: 'Phụ kiện', icon: '🖱️',
    attrTypes: [
      { id: 'type', label: 'Loại', options: ['Chuột không dây', 'Chuột có dây', 'Bàn phím cơ', 'Bàn phím mỏng', 'Tai nghe over-ear', 'Tai nghe in-ear', 'Màn hình', 'Loa bluetooth'] },
      { id: 'conn', label: 'Kết nối', options: ['USB-A', 'USB-C', 'Bluetooth 5.0', 'Bluetooth 5.3', '2.4GHz Dongle', '3.5mm Jack', 'Thunderbolt 4'] },
    ]
  },
])

const colorList = ref([
  { id: 1, name: 'Space Black', hex: '#1a1a1a' },
  { id: 2, name: 'Silver', hex: '#C0C0C0' },
  { id: 3, name: 'Midnight', hex: '#1c2951' },
  { id: 4, name: 'Starlight', hex: '#e8dcc8' },
  { id: 5, name: 'Rose Gold', hex: '#c9768f' },
  { id: 6, name: 'Pacific Blue', hex: '#1f4f8a' },
  { id: 7, name: 'Alpine Green', hex: '#4a7c59' },
  { id: 8, name: 'Purple', hex: '#7c3aed' },
])

const colorHex = name => colorList.value.find(c => c.name === name)?.hex || '#cbd5e1'

/* ═══════════════════════════════════════
   MODAL & FORM
═══════════════════════════════════════ */
const showModal = ref(false)
const formError = ref('')
const imgPreview = ref('')
const fileInputRef = ref(null)

const defaultForm = () => ({ name: '', sku: '', category: '', price: '', stock: '', status: 'Đang bán', img: '' })
const form = ref(defaultForm())

const onFileChange = e => {
  const file = e.target.files[0]; if (!file) return
  const r = new FileReader()
  r.onload = ev => { imgPreview.value = ev.target.result; form.value.img = ev.target.result }
  r.readAsDataURL(file)
}
const triggerFileInput = () => fileInputRef.value.click()
const removeImg = () => {
  imgPreview.value = ''; form.value.img = ''
  if (fileInputRef.value) fileInputRef.value.value = ''
}

/* ═══════════════════════════════════════
   VARIANT STATE
   variantStep: 1 = chọn nhóm  |  2 = bảng
   Mỗi dòng variantRows = {
     id, attrs: { [typeId]: 'giá trị' },
     color, price, stock, sku
   }
═══════════════════════════════════════ */
const variantStep = ref(1)
const selectedGroupId = ref(null)
const variantRows = ref([])

const activeGroup = computed(() =>
  attributeGroups.value.find(g => g.id === selectedGroupId.value) || null
)

const makeEmptyRow = () => {
  const attrs = {}
  activeGroup.value?.attrTypes.forEach(t => { attrs[t.id] = '' })
  return { id: Date.now() + Math.random(), attrs, color: '', price: '', stock: 0, sku: '' }
}

const goStep2 = groupId => {
  selectedGroupId.value = groupId
  const g = attributeGroups.value.find(x => x.id === groupId)
  const attrs = {}
  g?.attrTypes.forEach(t => { attrs[t.id] = '' })
  variantRows.value = [{ id: Date.now(), attrs, color: '', price: '', stock: 0, sku: '' }]
  variantStep.value = 2
}

const backStep1 = () => { variantStep.value = 1; selectedGroupId.value = null }

const addVariantRow = () => variantRows.value.push(makeEmptyRow())
const removeVariantRow = i => { if (variantRows.value.length > 1) variantRows.value.splice(i, 1) }

// Nhân bản dòng – copy toàn bộ attrs, chỉ reset stock về 0
const cloneRow = i => {
  const src = variantRows.value[i]
  variantRows.value.splice(i + 1, 0, {
    id: Date.now() + Math.random(),
    attrs: { ...src.attrs },
    color: src.color, price: src.price, stock: 0, sku: ''
  })
}

/* ═══════════════════════════════════════
   OPEN / CLOSE / SUBMIT
═══════════════════════════════════════ */
const openModal = () => {
  form.value = defaultForm(); imgPreview.value = ''; formError.value = ''
  variantStep.value = 1; selectedGroupId.value = null; variantRows.value = []
  showModal.value = true
}
const closeModal = () => { showModal.value = false }

const submitForm = () => {
  if (!form.value.name.trim()) { formError.value = 'Vui lòng nhập tên sản phẩm.'; return }
  if (!form.value.sku.trim()) { formError.value = 'Vui lòng nhập SKU.'; return }
  if (!form.value.category) { formError.value = 'Vui lòng chọn danh mục.'; return }
  if (!form.value.price.trim()) { formError.value = 'Vui lòng nhập giá.'; return }
  if (!form.value.stock) { formError.value = 'Vui lòng nhập số lượng kho.'; return }
  products.value.unshift({
    name: form.value.name.trim(), sku: form.value.sku.trim(),
    category: form.value.category, price: form.value.price.trim(),
    stock: Number(form.value.stock), status: form.value.status,
    img: form.value.img || 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
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
      <div class="stat-card"><span class="stat-icon blue">📦</span>
        <div>
          <p>Tổng sản phẩm</p><b>1,284</b>
        </div>
      </div>
      <div class="stat-card"><span class="stat-icon green">🔥</span>
        <div>
          <p>Bán chạy</p><b>312</b>
        </div>
      </div>
      <div class="stat-card"><span class="stat-icon red">⚠️</span>
        <div>
          <p>Sắp hết hàng</p><b>18</b>
        </div>
      </div>
      <div class="stat-card"><span class="stat-icon purple">🏭</span>
        <div>
          <p>Kho lưu trữ</p><b>45</b>
        </div>
      </div>
    </div>

    <!-- FILTER -->
    <div class="filter-bar">
      <div class="search-wrap">
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
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
          <tr v-if="!filteredProducts.length">
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
            <td><span class="status-badge" :class="p.status === 'Đang bán' ? 'active' : 'draft'">{{ p.status }}</span></td>
            <td>
              <div class="actions">
                <button class="act-btn" title="Xem">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
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
      <button class="pg-active">1</button><button>2</button><button>3</button>
      <button>›</button>
    </div>

    <!-- ══════════════════════════════════════════════════════
         MODAL THÊM SẢN PHẨM
    ═══════════════════════════════════════════════════════ -->
    <Teleport to="body">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal modal-wide">

          <div class="modal-header">
            <h3>Thêm sản phẩm mới</h3>
            <button class="modal-close" @click="closeModal">×</button>
          </div>

          <div class="modal-body">

            <!-- ẢNH -->
            <div class="form-group">
              <label>Ảnh sản phẩm</label>
              <input ref="fileInputRef" type="file" accept="image/*" style="display:none" @change="onFileChange" />
              <div v-if="!imgPreview" class="upload-zone" @click="triggerFileInput">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="17 8 12 3 7 8" />
                  <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                <p>Kéo thả hoặc <span>bấm để chọn ảnh</span></p>
                <small>PNG, JPG, WEBP — tối đa 5MB</small>
              </div>
              <div v-else class="img-preview-wrap">
                <img :src="imgPreview" class="img-preview" alt="preview" />
                <div class="img-actions">
                  <button class="img-change" @click="triggerFileInput">Đổi ảnh</button>
                  <button class="img-remove-btn" @click="removeImg">Xóa</button>
                </div>
              </div>
            </div>

            <!-- THÔNG TIN CƠ BẢN -->
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

            <!-- ════════════════════════════════════════
                 BIẾN THỂ SẢN PHẨM
            ═══════════════════════════════════════════ -->
            <div class="vs-wrapper">

              <!-- HEADER -->
              <div class="vs-header">
                <div class="vs-title">
                  <span class="vs-bar"></span>
                  Biến thể sản phẩm
                </div>
                <div v-if="variantStep === 2" class="vs-active-group">
                  <span class="vag-icon">{{ activeGroup?.icon }}</span>
                  <span class="vag-name">{{ activeGroup?.name }}</span>
                  <button class="vag-change" @click="backStep1">Đổi nhóm ↩</button>
                </div>
              </div>

              <!-- ─── STEP 1: CHỌN NHÓM ─── -->
              <div v-if="variantStep === 1">
                <p class="step-hint">
                  Chọn nhóm sản phẩm. Hệ thống sẽ hiển thị <strong>tất cả loại thuộc tính</strong> của nhóm đó
                  thành các cột — mỗi dòng biến thể bạn tự chọn giá trị tương ứng cho từng cột (CPU, RAM, SSD, GPU...).
                </p>
                <div class="group-list">
                  <button v-for="g in attributeGroups" :key="g.id" class="group-card" @click="goStep2(g.id)">
                    <span class="gc-icon">{{ g.icon }}</span>
                    <div class="gc-body">
                      <span class="gc-name">{{ g.name }}</span>
                      <div class="gc-chips">
                        <span v-for="t in g.attrTypes" :key="t.id" class="gc-chip">{{ t.label }}</span>
                      </div>
                    </div>
                    <span class="gc-count">{{ g.attrTypes.length }} thuộc tính / dòng</span>
                    <span class="gc-arrow">›</span>
                  </button>
                </div>
              </div>

              <!-- ─── STEP 2: BẢNG BIẾN THỂ ─── -->
              <div v-if="variantStep === 2 && activeGroup">

                <!-- Chú thích cột -->
                <div class="col-legend">
                  <span class="cl-label">Cột hiển thị:</span>
                  <span v-for="t in activeGroup.attrTypes" :key="t.id" class="cl-chip cl-attr">{{ t.label }}</span>
                  <span class="cl-sep">＋</span>
                  <span class="cl-chip cl-color">Màu</span>
                  <span class="cl-chip cl-price">Giá riêng</span>
                  <span class="cl-chip cl-stock">Kho</span>
                  <span class="cl-chip cl-sku">SKU</span>
                </div>

                <!-- Bảng cuộn ngang -->
                <div class="vt-scroll">
                  <table class="vt-table">
                    <thead>
                      <tr>
                        <th class="th-no">#</th>
                        <th v-for="t in activeGroup.attrTypes" :key="t.id" class="th-attr">
                          <span class="th-attr-pill" :data-type="t.id">{{ t.label }}</span>
                        </th>
                        <th class="th-color">Màu sắc</th>
                        <th class="th-price">Giá riêng (₫)</th>
                        <th class="th-stock">Kho</th>
                        <th class="th-sku">SKU biến thể</th>
                        <th class="th-act"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, ri) in variantRows" :key="row.id" class="vt-row">
                        <!-- STT -->
                        <td class="td-no"><span class="row-no">{{ ri + 1 }}</span></td>

                        <!-- Cột mỗi loại thuộc tính → dropdown chọn giá trị -->
                        <td v-for="t in activeGroup.attrTypes" :key="t.id" class="td-attr">
                          <select v-model="row.attrs[t.id]" class="vt-select">
                            <option value="">— {{ t.label }} —</option>
                            <option v-for="opt in t.options" :key="opt" :value="opt">{{ opt }}</option>
                          </select>
                        </td>

                        <!-- Màu -->
                        <td class="td-color">
                          <div class="color-wrap">
                            <span v-if="row.color" class="cswatch" :style="{ background: colorHex(row.color) }"></span>
                            <select v-model="row.color" class="vt-select">
                              <option value="">Không chọn</option>
                              <option v-for="c in colorList" :key="c.id" :value="c.name">{{ c.name }}</option>
                            </select>
                          </div>
                        </td>

                        <!-- Giá riêng -->
                        <td class="td-price">
                          <input v-model="row.price" class="vt-input" placeholder="45.990.000" />
                        </td>

                        <!-- Kho -->
                        <td class="td-stock">
                          <input v-model="row.stock" type="number" min="0" class="vt-input vt-num" />
                        </td>

                        <!-- SKU biến thể -->
                        <td class="td-sku">
                          <input v-model="row.sku" class="vt-input" placeholder="VN-LP-i7-16-512" />
                        </td>

                        <!-- Actions: clone + xóa -->
                        <td class="td-act">
                          <div class="row-acts">
                            <button class="ra-btn ra-clone" title="Nhân bản dòng này" @click="cloneRow(ri)">
                              <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" width="13" height="13">
                                <rect x="5" y="5" width="8" height="8" rx="1.5" />
                                <path d="M3 11V3a1 1 0 0 1 1-1h8" />
                              </svg>
                            </button>
                            <button class="ra-btn ra-del" title="Xóa dòng" @click="removeVariantRow(ri)">
                              <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" width="11" height="11">
                                <line x1="2" y1="2" x2="12" y2="12" />
                                <line x1="12" y1="2" x2="2" y2="12" />
                              </svg>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Preview các dòng đã điền -->
                <div v-if="variantRows.some(r => Object.values(r.attrs).some(v => v))" class="vt-preview">
                  <div class="vtp-label">Xem trước cấu hình</div>
                  <div v-for="(row, ri) in variantRows" :key="row.id" class="vtp-row">
                    <span class="vtp-no">{{ ri + 1 }}</span>
                    <template v-for="t in activeGroup.attrTypes" :key="t.id">
                      <span v-if="row.attrs[t.id]" class="vtp-chip" :class="'c-' + t.id">
                        <span class="vtp-type">{{ t.label }}</span>
                        {{ row.attrs[t.id] }}
                      </span>
                    </template>
                    <span v-if="row.color" class="vtp-color-wrap">
                      <span class="vtp-dot" :style="{ background: colorHex(row.color) }"></span>
                      {{ row.color }}
                    </span>
                    <span v-if="row.price" class="vtp-price">{{ row.price }} ₫</span>
                    <span v-if="row.stock" class="vtp-stock">Kho: {{ row.stock }}</span>
                    <span v-if="row.sku" class="vtp-sku">{{ row.sku }}</span>
                  </div>
                </div>

                <!-- Footer -->
                <div class="vt-footer">
                  <button class="btn-add-row" @click="addVariantRow">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                      width="11" height="11">
                      <line x1="7" y1="1" x2="7" y2="13" />
                      <line x1="1" y1="7" x2="13" y2="7" />
                    </svg>
                    Thêm dòng biến thể
                  </button>
                  <span class="vt-stat">
                    <b>{{ variantRows.length }}</b> biến thể ·
                    <b>{{ activeGroup.attrTypes.length }}</b> thuộc tính / dòng
                  </span>
                </div>

              </div>
              <!-- /step2 -->

            </div>
            <!-- /vs-wrapper -->

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
/* ══ BASE ══ */
.admin {
  padding: 32px 48px;
  background: #f5f7fb;
  min-height: 100vh;
  font-family: 'Segoe UI', sans-serif;
}

/* HEADER */
.top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
}

.top h1 {
  font-size: 24px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 4px;
}

.top p {
  font-size: 13px;
  color: #64748b;
  margin: 0;
}

.add-btn {
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity .2s, transform .2s;
}

.add-btn:hover {
  opacity: .9;
  transform: translateY(-1px);
}

/* STATS */
.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 18px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  border: 1px solid #f1f5f9;
}

.stat-card p {
  font-size: 12px;
  color: #64748b;
  margin: 0 0 4px;
}

.stat-card b {
  font-size: 22px;
  font-weight: 700;
  color: #0f172a;
}

.stat-icon {
  font-size: 22px;
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon.blue {
  background: #dbeafe
}

.stat-icon.green {
  background: #dcfce7
}

.stat-icon.red {
  background: #fee2e2
}

.stat-icon.purple {
  background: #ede9fe
}

/* FILTER */
.filter-bar {
  display: flex;
  gap: 10px;
  margin-bottom: 16px;
}

.search-wrap {
  flex: 1;
  position: relative;
}

.search-icon {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  width: 16px;
  height: 16px;
  color: #94a3b8;
  pointer-events: none;
}

.search-wrap input {
  width: 100%;
  padding: 10px 14px 10px 38px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  background: white;
  font-size: 13px;
  color: #0f172a;
  outline: none;
  transition: border-color .2s;
  box-sizing: border-box;
}

.search-wrap input:focus {
  border-color: #2563eb;
}

.filter-bar select {
  padding: 10px 14px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  background: white;
  font-size: 13px;
  color: #334155;
  outline: none;
  cursor: pointer;
  min-width: 160px;
}

/* TABLE */
.table-wrap {
  background: white;
  border-radius: 14px;
  border: 1px solid #f1f5f9;
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead tr {
  background: #f8fafc;
}

thead th {
  padding: 13px 16px;
  font-size: 12px;
  font-weight: 600;
  color: #64748b;
  text-align: left;
  letter-spacing: .04em;
  border-bottom: 1px solid #f1f5f9;
}

tbody tr {
  border-bottom: 1px solid #f8fafc;
  transition: background .15s;
}

tbody tr:last-child {
  border-bottom: none;
}

tbody tr:hover {
  background: #fafbff;
}

tbody td {
  padding: 14px 16px;
  font-size: 13px;
  color: #334155;
  vertical-align: middle;
}

.empty {
  text-align: center;
  color: #94a3b8;
  padding: 40px !important;
}

.product-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.product-cell img {
  width: 42px;
  height: 42px;
  border-radius: 8px;
  object-fit: cover;
  flex-shrink: 0;
}

.product-cell b {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 2px;
}

.product-cell span {
  font-size: 11px;
  color: #94a3b8;
}

.badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 500;
  padding: 4px 10px;
  border-radius: 6px;
  background: #dbeafe;
  color: #1d4ed8;
}

.price {
  font-weight: 600;
  color: #0f172a;
}

.stock-cell span {
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
  display: block;
  margin-bottom: 5px;
}

.bar {
  height: 4px;
  background: #e2e8f0;
  border-radius: 2px;
  width: 80px;
}

.fill {
  height: 100%;
  background: #2563eb;
  border-radius: 2px;
  transition: width .4s;
}

.status-badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 20px;
}

.status-badge.active {
  background: #dcfce7;
  color: #16a34a;
}

.status-badge.draft {
  background: #f1f5f9;
  color: #64748b;
}

.actions {
  display: flex;
  gap: 6px;
}

.act-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
  transition: all .2s;
}

.act-btn svg {
  width: 14px;
  height: 14px;
}

.act-btn:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
  color: #2563eb;
}

.act-btn.danger:hover {
  background: #fee2e2;
  border-color: #fecaca;
  color: #ef4444;
}

/* PAGINATION */
.pagination {
  display: flex;
  gap: 6px;
  margin-top: 20px;
  justify-content: flex-end;
}

.pagination button {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: white;
  font-size: 13px;
  cursor: pointer;
  color: #334155;
  transition: all .2s;
}

.pagination button:hover {
  border-color: #2563eb;
  color: #2563eb;
}

.pg-active {
  background: #2563eb !important;
  border-color: #2563eb !important;
  color: white !important;
}

/* ══ MODAL BASE ══ */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, .6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 16px;
}

.modal {
  background: white;
  border-radius: 18px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 32px 80px rgba(0, 0, 0, .22);
  animation: modalIn .22s cubic-bezier(.22, 1, .36, 1);
  max-height: 94vh;
  overflow-y: auto;
}

.modal-wide {
  max-width: 1100px;
}

@keyframes modalIn {
  from {
    opacity: 0;
    transform: translateY(16px) scale(.97)
  }

  to {
    opacity: 1;
    transform: none
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px 16px;
  border-bottom: 1px solid #f1f5f9;
  position: sticky;
  top: 0;
  background: white;
  z-index: 2;
  border-radius: 18px 18px 0 0;
}

.modal-header h3 {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #94a3b8;
  cursor: pointer;
  line-height: 1;
  padding: 0;
  transition: color .2s;
}

.modal-close:hover {
  color: #0f172a;
}

.modal-body {
  padding: 20px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 16px 24px 20px;
  border-top: 1px solid #f1f5f9;
  position: sticky;
  bottom: 0;
  background: white;
  z-index: 2;
  border-radius: 0 0 18px 18px;
}

/* UPLOAD */
.upload-zone {
  border: 2px dashed #cbd5e1;
  border-radius: 10px;
  padding: 26px 20px;
  text-align: center;
  cursor: pointer;
  transition: border-color .2s, background .2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.upload-zone:hover {
  border-color: #2563eb;
  background: #f0f6ff;
}

.upload-zone svg {
  width: 36px;
  height: 36px;
  color: #94a3b8;
}

.upload-zone p {
  font-size: 14px;
  color: #475569;
  margin: 0;
}

.upload-zone p span {
  color: #2563eb;
  font-weight: 600;
}

.upload-zone small {
  font-size: 12px;
  color: #94a3b8;
}

.img-preview-wrap {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #f8fafc;
  border-radius: 10px;
  padding: 14px;
  border: 1px solid #e2e8f0;
}

.img-preview {
  width: 88px;
  height: 88px;
  object-fit: cover;
  border-radius: 8px;
  flex-shrink: 0;
}

.img-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.img-change {
  padding: 7px 14px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  background: white;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
  transition: all .2s;
}

.img-change:hover {
  border-color: #2563eb;
  color: #2563eb;
}

.img-remove-btn {
  padding: 7px 14px;
  border-radius: 7px;
  border: 1px solid #fecaca;
  background: #fef2f2;
  font-size: 12px;
  font-weight: 600;
  color: #ef4444;
  cursor: pointer;
}

.img-remove-btn:hover {
  background: #fee2e2;
}

/* FORM */
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  letter-spacing: .03em;
}

.required {
  color: #ef4444;
}

.form-group input,
.form-group select {
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  font-size: 13px;
  color: #0f172a;
  outline: none;
  transition: border-color .2s;
  background: #fff;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}

.form-error {
  font-size: 12px;
  color: #ef4444;
  background: #fef2f2;
  border: 1px solid #fecaca;
  padding: 8px 12px;
  border-radius: 8px;
  margin: 0;
}

.btn-cancel {
  padding: 10px 20px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: white;
  font-size: 13px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
  transition: all .2s;
}

.btn-cancel:hover {
  background: #f8fafc;
}

.btn-submit {
  padding: 10px 22px;
  border-radius: 8px;
  border: none;
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: white;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity .2s, transform .2s;
}

.btn-submit:hover {
  opacity: .9;
  transform: translateY(-1px);
}

/* ══════════════════════════════════════════
   VARIANT SECTION
══════════════════════════════════════════ */
.vs-wrapper {
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  padding: 20px;
  background: #fafbff;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

/* header */
.vs-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}

.vs-title {
  display: flex;
  align-items: center;
  gap: 9px;
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
}

.vs-bar {
  display: inline-block;
  width: 3px;
  height: 17px;
  background: linear-gradient(180deg, #2563eb, #4f46e5);
  border-radius: 2px;
  flex-shrink: 0;
}

/* active group badge */
.vs-active-group {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 5px 10px 5px 12px;
  border-radius: 20px;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
}

.vag-icon {
  font-size: 16px;
}

.vag-name {
  font-size: 12px;
  font-weight: 700;
  color: #1d4ed8;
}

.vag-change {
  background: none;
  border: none;
  font-size: 11px;
  color: #2563eb;
  cursor: pointer;
  padding: 0;
  text-decoration: underline;
}

.vag-change:hover {
  color: #1d4ed8;
}

.step-hint {
  font-size: 12px;
  color: #64748b;
  margin: 0;
  line-height: 1.65;
}

.step-hint strong {
  color: #0f172a;
}

/* GROUP LIST */
.group-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.group-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  border-radius: 12px;
  border: 1.5px solid #e2e8f0;
  background: white;
  cursor: pointer;
  text-align: left;
  width: 100%;
  transition: all .2s;
}

.group-card:hover {
  border-color: #2563eb;
  background: #f0f6ff;
  box-shadow: 0 2px 14px rgba(37, 99, 235, .1);
  transform: translateY(-1px);
}

.gc-icon {
  font-size: 26px;
  flex-shrink: 0;
}

.gc-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.gc-name {
  font-size: 13px;
  font-weight: 700;
  color: #0f172a;
}

.gc-chips {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.gc-chip {
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 4px;
  background: #f1f5f9;
  color: #475569;
}

.gc-count {
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
  white-space: nowrap;
  padding: 3px 10px;
  background: #f8fafc;
  border-radius: 6px;
}

.gc-arrow {
  color: #94a3b8;
  font-size: 20px;
  flex-shrink: 0;
}

/* COL LEGEND */
.col-legend {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
  padding: 10px 14px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #f1f5f9;
}

.cl-label {
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
  margin-right: 2px;
  white-space: nowrap;
}

.cl-chip {
  font-size: 11px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 5px;
}

.cl-attr {
  background: #dbeafe;
  color: #1d4ed8;
}

.cl-color {
  background: #fce7f3;
  color: #be185d;
}

.cl-price {
  background: #dcfce7;
  color: #166534;
}

.cl-stock {
  background: #fef3c7;
  color: #92400e;
}

.cl-sku {
  background: #f3e8ff;
  color: #6b21a8;
}

.cl-sep {
  font-size: 13px;
  color: #94a3b8;
  font-weight: 700;
  margin: 0 2px;
}

/* VARIANT TABLE */
.vt-scroll {
  overflow-x: auto;
  border: 1px solid #e8edf5;
  border-radius: 12px;
  background: white;
}

.vt-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}

.vt-table thead tr {
  background: #f8fafc;
}

.vt-table thead th {
  padding: 10px 12px;
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-align: left;
  letter-spacing: .07em;
  border-bottom: 1px solid #f1f5f9;
  white-space: nowrap;
}

.th-no {
  width: 40px;
  text-align: center;
}

.th-attr {
  min-width: 155px;
}

.th-color {
  min-width: 145px;
}

.th-price {
  min-width: 145px;
}

.th-stock {
  width: 80px;
}

.th-sku {
  min-width: 160px;
}

.th-act {
  width: 72px;
  text-align: center;
}

.th-attr-pill {
  display: inline-flex;
  padding: 2px 9px;
  border-radius: 4px;
  background: #dbeafe;
  color: #1d4ed8;
  font-size: 10px;
  font-weight: 700;
}

/* Different pastel per attr type */
.th-attr-pill[data-type="cpu"] {
  background: #dbeafe;
  color: #1d4ed8;
}

.th-attr-pill[data-type="ram"] {
  background: #dcfce7;
  color: #166534;
}

.th-attr-pill[data-type="ssd"] {
  background: #fef3c7;
  color: #92400e;
}

.th-attr-pill[data-type="gpu"] {
  background: #fce7f3;
  color: #be185d;
}

.th-attr-pill[data-type="os"] {
  background: #f3e8ff;
  color: #6b21a8;
}

.th-attr-pill[data-type="chip"] {
  background: #dbeafe;
  color: #1d4ed8;
}

.th-attr-pill[data-type="storage"] {
  background: #fef3c7;
  color: #92400e;
}

.th-attr-pill[data-type="network"] {
  background: #dcfce7;
  color: #166534;
}

.th-attr-pill[data-type="connect"] {
  background: #dcfce7;
  color: #166534;
}

.th-attr-pill[data-type="screen"] {
  background: #f3e8ff;
  color: #6b21a8;
}

.th-attr-pill[data-type="type"] {
  background: #f1f5f9;
  color: #334155;
}

.th-attr-pill[data-type="conn"] {
  background: #dbeafe;
  color: #1d4ed8;
}

.vt-row {
  border-bottom: 1px solid #f8fafc;
  transition: background .15s;
}

.vt-row:last-child {
  border-bottom: none;
}

.vt-row:hover {
  background: #fafbff;
}

.vt-table tbody td {
  padding: 9px 12px;
  vertical-align: middle;
}

.td-no {
  text-align: center;
}

.row-no {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: #f1f5f9;
  color: #64748b;
  font-size: 11px;
  font-weight: 700;
}

.vt-select {
  width: 100%;
  padding: 7px 10px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  font-size: 12px;
  color: #0f172a;
  outline: none;
  background: #fafbff;
  cursor: pointer;
  transition: border-color .2s;
}

.vt-select:focus {
  border-color: #2563eb;
}

.vt-input {
  width: 100%;
  padding: 7px 10px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  font-size: 12px;
  color: #0f172a;
  outline: none;
  background: #fafbff;
  transition: border-color .2s;
  box-sizing: border-box;
}

.vt-input:focus {
  border-color: #2563eb;
}

.vt-num {
  text-align: right;
}

.color-wrap {
  display: flex;
  align-items: center;
  gap: 7px;
}

.cswatch {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  flex-shrink: 0;
  border: 2px solid rgba(0, 0, 0, .1);
}

.row-acts {
  display: flex;
  gap: 5px;
  justify-content: center;
}

.ra-btn {
  width: 28px;
  height: 28px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all .2s;
  color: #94a3b8;
}

.ra-clone:hover {
  border-color: #2563eb;
  background: #eff6ff;
  color: #2563eb;
}

.ra-del:hover {
  border-color: #fecaca;
  background: #fef2f2;
  color: #ef4444;
}

/* PREVIEW */
.vt-preview {
  display: flex;
  flex-direction: column;
  gap: 0;
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 10px;
  overflow: hidden;
}

.vtp-label {
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .07em;
  padding: 9px 14px;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
}

.vtp-row {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
  padding: 8px 14px;
  border-bottom: 1px solid #f8fafc;
}

.vtp-row:last-child {
  border-bottom: none;
}

.vtp-no {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #f1f5f9;
  color: #64748b;
  font-size: 10px;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.vtp-chip {
  font-size: 11px;
  font-weight: 500;
  padding: 3px 9px;
  border-radius: 5px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.vtp-type {
  font-size: 9px;
  font-weight: 700;
  opacity: .7;
  text-transform: uppercase;
  letter-spacing: .04em;
}

.vtp-chip.c-cpu {
  background: #dbeafe;
  color: #1d4ed8;
}

.vtp-chip.c-ram {
  background: #dcfce7;
  color: #166534;
}

.vtp-chip.c-ssd {
  background: #fef3c7;
  color: #92400e;
}

.vtp-chip.c-gpu {
  background: #fce7f3;
  color: #be185d;
}

.vtp-chip.c-os {
  background: #f3e8ff;
  color: #6b21a8;
}

.vtp-chip.c-chip {
  background: #dbeafe;
  color: #1d4ed8;
}

.vtp-chip.c-storage {
  background: #fef3c7;
  color: #92400e;
}

.vtp-chip.c-network {
  background: #dcfce7;
  color: #166534;
}

.vtp-chip.c-connect {
  background: #dcfce7;
  color: #166534;
}

.vtp-chip.c-screen {
  background: #f3e8ff;
  color: #6b21a8;
}

.vtp-chip.c-type {
  background: #f1f5f9;
  color: #334155;
}

.vtp-chip.c-conn {
  background: #dbeafe;
  color: #1d4ed8;
}

.vtp-color-wrap {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 11px;
  color: #334155;
  padding: 3px 9px;
  border-radius: 5px;
  background: #fce7f3;
}

.vtp-dot {
  width: 11px;
  height: 11px;
  border-radius: 50%;
  border: 1.5px solid rgba(0, 0, 0, .1);
  flex-shrink: 0;
}

.vtp-price {
  font-size: 11px;
  font-weight: 700;
  color: #16a34a;
  padding: 3px 8px;
  background: #dcfce7;
  border-radius: 5px;
}

.vtp-stock {
  font-size: 11px;
  color: #92400e;
  padding: 3px 8px;
  background: #fef3c7;
  border-radius: 5px;
}

.vtp-sku {
  font-size: 11px;
  color: #6b21a8;
  padding: 3px 8px;
  background: #f3e8ff;
  border-radius: 5px;
  font-family: monospace;
}

/* FOOTER */
.vt-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  padding-top: 4px;
}

.btn-add-row {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 8px 16px;
  border-radius: 8px;
  border: 1.5px dashed #22c55e;
  background: white;
  font-size: 12px;
  font-weight: 600;
  color: #16a34a;
  cursor: pointer;
  transition: all .2s;
}

.btn-add-row:hover {
  background: #f0fdf4;
  border-color: #16a34a;
}

.vt-stat {
  font-size: 12px;
  color: #64748b;
}

.vt-stat b {
  color: #0f172a;
}

/* ══ RESPONSIVE ══ */
@media (max-width:768px) {
  .admin {
    padding: 20px 16px;
  }

  .stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .filter-bar {
    flex-wrap: wrap;
  }

  .table-wrap {
    overflow-x: auto;
  }

  table {
    min-width: 700px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .modal-wide {
    max-width: 98vw;
  }

  .gc-chips,
  .gc-count {
    display: none;
  }
}
</style>
