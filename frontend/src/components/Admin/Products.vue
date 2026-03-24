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

/* ───────────── MODAL ───────────── */
const showModal = ref(false)
const formError = ref('')
const imgPreview = ref('')
const fileInputRef = ref(null)

const defaultForm = () => ({
  name: '', sku: '', category: '', price: '', stock: '', status: 'Đang bán', img: ''
})
const form = ref(defaultForm())

/* ───────────── DỮ LIỆU BIẾN THỂ ───────────── */
const attributeGroups = ref([
  {
    id: 1, name: 'Cấu hình Laptop', icon: '⚙️',
    attrs: [
      { id: 101, name: 'RAM 8GB',        type: 'RAM' },
      { id: 102, name: 'RAM 16GB',       type: 'RAM' },
      { id: 103, name: 'RAM 32GB',       type: 'RAM' },
      { id: 104, name: 'SSD 256GB',      type: 'Ổ cứng' },
      { id: 105, name: 'SSD 512GB',      type: 'Ổ cứng' },
      { id: 106, name: 'SSD 1TB',        type: 'Ổ cứng' },
      { id: 107, name: 'Intel Core i5',  type: 'CPU' },
      { id: 108, name: 'Intel Core i7',  type: 'CPU' },
      { id: 109, name: 'Apple M3 Pro',   type: 'CPU' },
    ]
  },
  {
    id: 2, name: 'Màn hình', icon: '🖥️',
    attrs: [
      { id: 201, name: '13.3 inch FHD',  type: 'Kích thước' },
      { id: 202, name: '14 inch 2K',     type: 'Kích thước' },
      { id: 203, name: '15.6 inch FHD',  type: 'Kích thước' },
      { id: 204, name: '16 inch Retina', type: 'Kích thước' },
    ]
  },
  {
    id: 3, name: 'Pin & Sạc', icon: '🔋',
    attrs: [
      { id: 301, name: 'Pin 45Wh',           type: 'Dung lượng' },
      { id: 302, name: 'Pin 70Wh',           type: 'Dung lượng' },
      { id: 303, name: 'Sạc 45W',            type: 'Adapter' },
      { id: 304, name: 'Sạc 67W',            type: 'Adapter' },
      { id: 305, name: 'Sạc 96W MagSafe',    type: 'Adapter' },
    ]
  },
  {
    id: 4, name: 'Kết nối', icon: '📡',
    attrs: [
      { id: 401, name: 'WiFi 6',                  type: 'WiFi' },
      { id: 402, name: 'WiFi 6E',                 type: 'WiFi' },
      { id: 403, name: 'Bluetooth 5.0',           type: 'Bluetooth' },
      { id: 404, name: 'Bluetooth 5.3',           type: 'Bluetooth' },
      { id: 405, name: 'USB-C Thunderbolt 4',     type: 'Cổng' },
    ]
  },
  {
    id: 5, name: 'Phụ kiện', icon: '🖱️',
    attrs: [
      { id: 501, name: 'Chuột không dây', type: 'Chuột' },
      { id: 502, name: 'Bàn phím cơ',    type: 'Bàn phím' },
      { id: 503, name: 'Túi chống sốc',  type: 'Túi' },
    ]
  },
])

const colorList = ref([
  { id: 1, name: 'Space Black', hex: '#1a1a1a' },
  { id: 2, name: 'Silver',      hex: '#C0C0C0' },
  { id: 3, name: 'Midnight',    hex: '#1c2951' },
  { id: 4, name: 'Starlight',   hex: '#e8dcc8' },
  { id: 5, name: 'Rose Gold',   hex: '#c9768f' },
  { id: 6, name: 'Pacific Blue',hex: '#1f4f8a' },
])

/* ───────────── VARIANT STATE ───────────── */
const variantStep        = ref(1)   // 1=chọn nhóm | 2=chọn loại | 3=cấu hình bảng
const selectedGroupId    = ref(null)
const selectedAttrIds    = ref([])
const variantRows        = ref([])
const configuredGroups   = ref([])  // các nhóm đã xác nhận

const activeGroup = computed(() =>
  attributeGroups.value.find(g => g.id === selectedGroupId.value) || null
)

const attrsByType = computed(() => {
  if (!activeGroup.value) return {}
  return activeGroup.value.attrs.reduce((acc, a) => {
    if (!acc[a.type]) acc[a.type] = []
    acc[a.type].push(a)
    return acc
  }, {})
})

const selectedAttrs = computed(() =>
  activeGroup.value
    ? activeGroup.value.attrs.filter(a => selectedAttrIds.value.includes(a.id))
    : []
)

const goStep2 = (groupId) => {
  selectedGroupId.value = groupId
  selectedAttrIds.value = []
  variantStep.value = 2
}

const toggleAttr = (id) => {
  const idx = selectedAttrIds.value.indexOf(id)
  if (idx >= 0) selectedAttrIds.value.splice(idx, 1)
  else selectedAttrIds.value.push(id)
}

const goStep3 = () => {
  if (!selectedAttrIds.value.length) return
  variantRows.value = selectedAttrs.value.map(attr => ({
    id: Date.now() + attr.id,
    attrName: attr.name,
    attrType: attr.type,
    color: '',
    price: '',
    stock: 0,
    sku: '',
  }))
  variantStep.value = 3
}

const backStep1 = () => {
  selectedGroupId.value = null
  selectedAttrIds.value = []
  variantStep.value = 1
}
const backStep2 = () => { variantStep.value = 2 }

const addVariantRow = () => {
  variantRows.value.push({
    id: Date.now(),
    attrName: '', attrType: '', color: '', price: '', stock: 0, sku: ''
  })
}
const removeVariantRow = (i) => {
  if (variantRows.value.length > 1) variantRows.value.splice(i, 1)
}

const confirmVariants = () => {
  const existing = configuredGroups.value.findIndex(cg => cg.groupName === activeGroup.value.name)
  const entry = {
    groupName: activeGroup.value.name,
    groupIcon: activeGroup.value.icon,
    rows: variantRows.value.map(r => ({ ...r }))
  }
  if (existing >= 0) configuredGroups.value[existing] = entry
  else configuredGroups.value.push(entry)
  backStep1()
}

const removeConfiguredGroup = (i) => configuredGroups.value.splice(i, 1)

/* ───────────── ẢNH SẢN PHẨM ───────────── */
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

/* ───────────── OPEN / CLOSE ───────────── */
const openModal = () => {
  form.value = defaultForm()
  imgPreview.value = ''
  formError.value = ''
  variantStep.value = 1
  selectedGroupId.value = null
  selectedAttrIds.value = []
  variantRows.value = []
  configuredGroups.value = []
  showModal.value = true
}
const closeModal = () => { showModal.value = false }

const submitForm = () => {
  if (!form.value.name.trim())     { formError.value = 'Vui lòng nhập tên sản phẩm.'; return }
  if (!form.value.sku.trim())      { formError.value = 'Vui lòng nhập SKU.'; return }
  if (!form.value.category)        { formError.value = 'Vui lòng chọn danh mục.'; return }
  if (!form.value.price.trim())    { formError.value = 'Vui lòng nhập giá.'; return }
  if (!form.value.stock)           { formError.value = 'Vui lòng nhập số lượng kho.'; return }
  products.value.unshift({
    name: form.value.name.trim(),
    sku: form.value.sku.trim(),
    category: form.value.category,
    price: form.value.price.trim(),
    stock: Number(form.value.stock),
    status: form.value.status,
    img: form.value.img || 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200'
  })
  closeModal()
}

/* ───────────── MODAL THÊM MÀU ───────────── */
const showColorModal = ref(false)
const colorForm = ref({ name: '', hex: '#000000' })
const openColorModal  = () => { colorForm.value = { name: '', hex: '#000000' }; showColorModal.value = true }
const closeColorModal = () => { showColorModal.value = false }
const submitColor = () => {
  if (!colorForm.value.name.trim()) return
  colorList.value.push({ id: Date.now(), name: colorForm.value.name.trim(), hex: colorForm.value.hex })
  closeColorModal()
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
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
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
            <th>Sản phẩm</th><th>Danh mục</th><th>Giá niêm yết</th>
            <th>Kho hàng</th><th>Trạng thái</th><th>Thao tác</th>
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
                <div class="bar"><div class="fill" :style="{ width: stockPercent(p.stock) + '%' }"></div></div>
              </div>
            </td>
            <td>
              <span class="status-badge" :class="p.status === 'Đang bán' ? 'active' : 'draft'">{{ p.status }}</span>
            </td>
            <td>
              <div class="actions">
                <button class="act-btn" title="Xem">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
                <button class="act-btn" title="Sửa">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <button class="act-btn danger" title="Xóa" @click="removeProduct(i)">
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

    <!-- PAGINATION -->
    <div class="pagination">
      <button>‹</button>
      <button class="active">1</button>
      <button>2</button>
      <button>3</button>
      <button>›</button>
    </div>

    <!-- ═══════════════════════════════════════════════
         MODAL THÊM SẢN PHẨM
    ════════════════════════════════════════════════ -->
    <Teleport to="body">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal modal-wide">

          <div class="modal-header">
            <h3>Thêm sản phẩm mới</h3>
            <button class="modal-close" @click="closeModal">×</button>
          </div>

          <div class="modal-body">

            <!-- ── ẢNH ── -->
            <div class="form-group">
              <label>Ảnh sản phẩm</label>
              <input ref="fileInputRef" type="file" accept="image/*" style="display:none" @change="onFileChange" />
              <div v-if="!imgPreview" class="upload-zone" @click="triggerFileInput">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
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

            <!-- ── THÔNG TIN CƠ BẢN ── -->
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

            <!-- ══════════════════════════════════════
                 PHẦN BIẾN THỂ 3 BƯỚC
            ═══════════════════════════════════════ -->
            <div class="vs-wrapper">

              <!-- TIÊU ĐỀ SECTION -->
              <div class="vs-header-row">
                <div class="vs-section-title">
                  <span class="vs-bar"></span>
                  Biến thể sản phẩm
                </div>
                <!-- Tóm tắt nhóm đã cấu hình -->
                <div v-if="configuredGroups.length" class="configured-chips">
                  <div v-for="(cg, ci) in configuredGroups" :key="ci" class="configured-chip">
                    <span>{{ cg.groupIcon }} {{ cg.groupName }}</span>
                    <span class="chip-count">{{ cg.rows.length }} biến thể</span>
                    <button class="chip-remove" @click="removeConfiguredGroup(ci)">×</button>
                  </div>
                </div>
              </div>

              <!-- BREADCRUMB STEPS -->
              <div class="vs-steps">
                <div class="vs-step" :class="{ 'step-active': variantStep >= 1, 'step-done': variantStep > 1 }">
                  <span class="step-dot">
                    <svg v-if="variantStep > 1" viewBox="0 0 12 12" fill="none"><polyline points="2,6 5,9 10,3" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <span v-else>1</span>
                  </span>
                  <span class="step-label">Nhóm thuộc tính</span>
                </div>
                <div class="step-line" :class="{ 'line-done': variantStep > 1 }"></div>
                <div class="vs-step" :class="{ 'step-active': variantStep >= 2, 'step-done': variantStep > 2 }">
                  <span class="step-dot">
                    <svg v-if="variantStep > 2" viewBox="0 0 12 12" fill="none"><polyline points="2,6 5,9 10,3" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <span v-else>2</span>
                  </span>
                  <span class="step-label">Loại thuộc tính</span>
                </div>
                <div class="step-line" :class="{ 'line-done': variantStep > 2 }"></div>
                <div class="vs-step" :class="{ 'step-active': variantStep >= 3 }">
                  <span class="step-dot"><span>3</span></span>
                  <span class="step-label">Cấu hình biến thể</span>
                </div>
              </div>

              <!-- ────────────────────────────────────
                   STEP 1: CHỌN NHÓM THUỘC TÍNH
              ──────────────────────────────────── -->
              <div v-if="variantStep === 1" class="step-body">
                <p class="step-hint">Chọn một nhóm thuộc tính để bắt đầu cấu hình biến thể. Bạn có thể thêm nhiều nhóm.</p>
                <div class="group-grid">
                  <button
                    v-for="g in attributeGroups"
                    :key="g.id"
                    class="group-card"
                    :class="{ 'group-done': configuredGroups.some(cg => cg.groupName === g.name) }"
                    @click="goStep2(g.id)"
                  >
                    <span class="group-icon">{{ g.icon }}</span>
                    <div class="group-info">
                      <b>{{ g.name }}</b>
                      <span>{{ g.attrs.length }} thuộc tính</span>
                    </div>
                    <span v-if="configuredGroups.some(cg => cg.groupName === g.name)" class="group-check">✓ Đã cấu hình</span>
                    <span v-else class="group-arrow">›</span>
                  </button>
                </div>
              </div>

              <!-- ────────────────────────────────────
                   STEP 2: CHỌN LOẠI THUỘC TÍNH
              ──────────────────────────────────── -->
              <div v-if="variantStep === 2" class="step-body">
                <div class="step-nav">
                  <button class="back-btn" @click="backStep1">‹ Quay lại</button>
                  <span class="step-nav-title">{{ activeGroup?.icon }} {{ activeGroup?.name }}</span>
                </div>
                <p class="step-hint">Tick chọn các loại thuộc tính bạn muốn tạo biến thể. Có thể chọn nhiều loại.</p>

                <div class="attr-type-blocks">
                  <div v-for="(attrs, typeName) in attrsByType" :key="typeName" class="attr-type-block">
                    <div class="attr-type-label">
                      <span class="type-dot"></span>{{ typeName }}
                    </div>
                    <div class="attr-chip-list">
                      <button
                        v-for="attr in attrs"
                        :key="attr.id"
                        class="attr-chip"
                        :class="{ 'attr-chip-on': selectedAttrIds.includes(attr.id) }"
                        @click="toggleAttr(attr.id)"
                      >
                        <span class="chip-tick" v-if="selectedAttrIds.includes(attr.id)">
                          <svg viewBox="0 0 12 12" fill="none" width="10" height="10">
                            <polyline points="2,6 5,9 10,3" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                          </svg>
                        </span>
                        {{ attr.name }}
                      </button>
                    </div>
                  </div>
                </div>

                <div class="step2-footer">
                  <span class="selected-count">Đã chọn: <b>{{ selectedAttrIds.length }}</b> thuộc tính</span>
                  <button class="btn-next" :disabled="!selectedAttrIds.length" @click="goStep3">
                    Tiếp theo: Cấu hình biến thể →
                  </button>
                </div>
              </div>

              <!-- ────────────────────────────────────
                   STEP 3: BẢNG BIẾN THỂ
              ──────────────────────────────────── -->
              <div v-if="variantStep === 3" class="step-body">
                <div class="step-nav">
                  <button class="back-btn" @click="backStep2">‹ Quay lại</button>
                  <span class="step-nav-title">{{ activeGroup?.icon }} {{ activeGroup?.name }} — Cấu hình chi tiết</span>
                </div>
                <p class="step-hint">Điền thông tin cho từng biến thể. Các trường màu sắc, giá, SKU có thể để trống.</p>

                <div class="vt-table-wrap">
                  <table class="vt-table">
                    <thead>
                      <tr>
                        <th>LOẠI THUỘC TÍNH</th>
                        <th>NHÓM</th>
                        <th>MÀU SẮC</th>
                        <th>GIÁ RIÊNG (₫)</th>
                        <th>SỐ LƯỢNG</th>
                        <th>SKU BIẾN THỂ</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, ri) in variantRows" :key="row.id">
                        <td>
                          <div v-if="row.attrName" class="vt-attr-tag">{{ row.attrName }}</div>
                          <input v-else v-model="row.attrName" class="vt-input" placeholder="Nhập tên..." />
                        </td>
                        <td>
                          <span v-if="row.attrType" class="vt-type-badge">{{ row.attrType }}</span>
                          <span v-else class="vt-dim">—</span>
                        </td>
                        <td>
                          <div class="color-select-wrap">
                            <span
                              v-if="row.color"
                              class="color-dot-sm"
                              :style="{ background: colorList.find(c=>c.name===row.color)?.hex || '#ccc' }"
                            ></span>
                            <select v-model="row.color" class="vt-select">
                              <option value="">Không chọn</option>
                              <option v-for="c in colorList" :key="c.id" :value="c.name">{{ c.name }}</option>
                            </select>
                          </div>
                        </td>
                        <td><input v-model="row.price" class="vt-input" placeholder="22.000.000" /></td>
                        <td><input v-model="row.stock" type="number" min="0" class="vt-input vt-num" /></td>
                        <td><input v-model="row.sku" class="vt-input" placeholder="VN-LP-RAM16" /></td>
                        <td>
                          <button class="vt-remove" @click="removeVariantRow(ri)" title="Xóa dòng">
                            <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="12" height="12">
                              <line x1="2" y1="2" x2="12" y2="12"/><line x1="12" y1="2" x2="2" y2="12"/>
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- PREVIEW MÀU ĐÃ CHỌN -->
                <div v-if="variantRows.some(r => r.color)" class="color-preview-row">
                  <span class="cp-label">Màu trong biến thể:</span>
                  <div
                    v-for="c in [...new Set(variantRows.filter(r=>r.color).map(r=>r.color))]"
                    :key="c"
                    class="cp-item"
                  >
                    <span class="cp-dot" :style="{ background: colorList.find(x=>x.name===c)?.hex || '#ccc' }"></span>
                    <span>{{ c }}</span>
                  </div>
                </div>

                <div class="step3-footer">
                  <button class="btn-add-row" @click="addVariantRow">+ Thêm dòng</button>
                  <div class="step3-right">
                    <span class="vt-count-badge">{{ variantRows.length }} biến thể</span>
                    <button class="btn-confirm" @click="confirmVariants">✓ Xác nhận nhóm này</button>
                  </div>
                </div>
              </div>

              <!-- BẢNG TÓM TẮT TẤT CẢ BIẾN THỂ ĐÃ CẤU HÌNH -->
              <div v-if="variantStep === 1 && configuredGroups.length" class="summary-section">
                <div class="summary-title">
                  <span class="vs-bar purple"></span>
                  Tổng hợp biến thể đã cấu hình
                </div>
                <div v-for="(cg, ci) in configuredGroups" :key="ci" class="summary-group">
                  <div class="summary-group-header">
                    <span>{{ cg.groupIcon }} {{ cg.groupName }}</span>
                    <span class="sg-count">{{ cg.rows.length }} biến thể</span>
                  </div>
                  <div class="summary-rows">
                    <div v-for="(row, ri) in cg.rows" :key="ri" class="summary-row">
                      <span class="sr-attr">{{ row.attrName }}</span>
                      <span class="sr-type">{{ row.attrType }}</span>
                      <div v-if="row.color" class="sr-color">
                        <span class="sr-dot" :style="{ background: colorList.find(c=>c.name===row.color)?.hex || '#ccc' }"></span>
                        {{ row.color }}
                      </div>
                      <span v-if="row.price" class="sr-price">{{ row.price }} ₫</span>
                      <span class="sr-stock">Kho: {{ row.stock }}</span>
                    </div>
                  </div>
                </div>
              </div>

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

    <!-- MODAL THÊM MÀU (standalone) -->
    <Teleport to="body">
      <div v-if="showColorModal" class="modal-overlay" @click.self="closeColorModal">
        <div class="modal modal-sm">
          <div class="modal-header">
            <h3>Thêm màu sắc mới</h3>
            <button class="modal-close" @click="closeColorModal">×</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Tên màu <span class="required">*</span></label>
              <input v-model="colorForm.name" placeholder="VD: Midnight Green" />
            </div>
            <div class="form-group">
              <label>Chọn màu</label>
              <input v-model="colorForm.hex" type="color"
                style="width:60px;height:36px;padding:2px;border-radius:8px;border:1px solid #e2e8f0;cursor:pointer" />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" @click="closeColorModal">Hủy</button>
            <button class="btn-submit" @click="submitColor">Thêm màu</button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<style scoped>
/* ═══════════════════ BASE ═══════════════════ */
.admin {
  padding: 32px 48px;
  background: #f5f7fb;
  min-height: 100vh;
  font-family: sans-serif;
}

/* HEADER */
.top { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; }
.top h1 { font-size:24px; font-weight:700; color:#0f172a; margin:0 0 4px; }
.top p  { font-size:13px; color:#64748b; margin:0; }

.add-btn {
  background: linear-gradient(135deg,#2563eb,#4f46e5);
  color:white; border:none; padding:10px 20px;
  border-radius:10px; font-size:13px; font-weight:600;
  cursor:pointer; transition:opacity .2s,transform .2s;
}
.add-btn:hover { opacity:.9; transform:translateY(-1px); }

/* STATS */
.stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card {
  background:white; border-radius:12px; padding:18px 20px;
  display:flex; align-items:center; gap:14px; border:1px solid #f1f5f9;
}
.stat-card p { font-size:12px; color:#64748b; margin:0 0 4px; }
.stat-card b { font-size:22px; font-weight:700; color:#0f172a; }
.stat-icon { font-size:22px; width:44px; height:44px; border-radius:10px;
  display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.stat-icon.blue   { background:#dbeafe; }
.stat-icon.green  { background:#dcfce7; }
.stat-icon.red    { background:#fee2e2; }
.stat-icon.purple { background:#ede9fe; }

/* FILTER */
.filter-bar { display:flex; gap:10px; margin-bottom:16px; }
.search-wrap { flex:1; position:relative; }
.search-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%);
  width:16px; height:16px; color:#94a3b8; pointer-events:none; }
.search-wrap input {
  width:100%; padding:10px 14px 10px 38px; border-radius:10px;
  border:1px solid #e2e8f0; background:white; font-size:13px;
  color:#0f172a; outline:none; transition:border-color .2s; box-sizing:border-box;
}
.search-wrap input:focus { border-color:#2563eb; }
.filter-bar select {
  padding:10px 14px; border-radius:10px; border:1px solid #e2e8f0;
  background:white; font-size:13px; color:#334155; outline:none;
  cursor:pointer; min-width:160px;
}
.filter-bar select:focus { border-color:#2563eb; }

/* TABLE */
.table-wrap { background:white; border-radius:14px; border:1px solid #f1f5f9; overflow:hidden; }
table { width:100%; border-collapse:collapse; }
thead tr { background:#f8fafc; }
thead th { padding:13px 16px; font-size:12px; font-weight:600; color:#64748b;
  text-align:left; letter-spacing:.04em; border-bottom:1px solid #f1f5f9; }
tbody tr { border-bottom:1px solid #f8fafc; transition:background .15s; }
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:#fafbff; }
tbody td { padding:14px 16px; font-size:13px; color:#334155; vertical-align:middle; }
.empty { text-align:center; color:#94a3b8; padding:40px !important; }

.product-cell { display:flex; align-items:center; gap:12px; }
.product-cell img { width:42px; height:42px; border-radius:8px; object-fit:cover; flex-shrink:0; }
.product-cell b { display:block; font-size:13px; font-weight:600; color:#0f172a; margin-bottom:2px; }
.product-cell span { font-size:11px; color:#94a3b8; }

.badge { display:inline-block; font-size:11px; font-weight:500; padding:4px 10px;
  border-radius:6px; background:#dbeafe; color:#1d4ed8; }
.price { font-weight:600; color:#0f172a; }

.stock-cell span { font-size:13px; font-weight:600; color:#0f172a; display:block; margin-bottom:5px; }
.bar  { height:4px; background:#e2e8f0; border-radius:2px; width:80px; }
.fill { height:100%; background:#2563eb; border-radius:2px; transition:width .4s; }

.status-badge { display:inline-block; font-size:11px; font-weight:600;
  padding:4px 10px; border-radius:20px; letter-spacing:.03em; }
.status-badge.active { background:#dcfce7; color:#16a34a; }
.status-badge.draft  { background:#f1f5f9; color:#64748b; }

.actions { display:flex; gap:6px; }
.act-btn {
  width:32px; height:32px; border-radius:8px; border:1px solid #e2e8f0;
  background:white; cursor:pointer; display:flex; align-items:center;
  justify-content:center; color:#64748b; transition:all .2s;
}
.act-btn svg { width:14px; height:14px; }
.act-btn:hover { background:#f1f5f9; border-color:#cbd5e1; color:#2563eb; }
.act-btn.danger:hover { background:#fee2e2; border-color:#fecaca; color:#ef4444; }

/* PAGINATION */
.pagination { display:flex; gap:6px; margin-top:20px; justify-content:flex-end; }
.pagination button {
  width:34px; height:34px; border-radius:8px; border:1px solid #e2e8f0;
  background:white; font-size:13px; cursor:pointer; color:#334155; transition:all .2s;
}
.pagination button:hover { border-color:#2563eb; color:#2563eb; }
.pagination .active { background:#2563eb; border-color:#2563eb; color:white; }

/* ═══════════════════ MODAL BASE ═══════════════════ */
.modal-overlay {
  position:fixed; inset:0; background:rgba(15,23,42,.55);
  display:flex; align-items:center; justify-content:center; z-index:1000; padding:20px;
}
.modal {
  background:white; border-radius:16px; width:100%; max-width:600px;
  box-shadow:0 24px 60px rgba(0,0,0,.18);
  animation:modalIn .22s cubic-bezier(.22,1,.36,1);
  max-height:92vh; overflow-y:auto;
}
.modal-wide { max-width:860px; }
.modal-sm   { max-width:420px; }
@keyframes modalIn {
  from { opacity:0; transform:translateY(20px) scale(.97); }
  to   { opacity:1; transform:translateY(0) scale(1); }
}
.modal-header {
  display:flex; justify-content:space-between; align-items:center;
  padding:20px 24px 16px; border-bottom:1px solid #f1f5f9;
  position:sticky; top:0; background:white; z-index:1;
}
.modal-header h3 { font-size:16px; font-weight:700; color:#0f172a; margin:0; }
.modal-close { background:none; border:none; font-size:22px; color:#94a3b8;
  cursor:pointer; line-height:1; padding:0; transition:color .2s; }
.modal-close:hover { color:#0f172a; }
.modal-body { padding:20px 24px; display:flex; flex-direction:column; gap:16px; }

/* UPLOAD */
.upload-zone {
  border:2px dashed #cbd5e1; border-radius:10px; padding:32px 20px;
  text-align:center; cursor:pointer; transition:border-color .2s,background .2s;
  display:flex; flex-direction:column; align-items:center; gap:10px;
}
.upload-zone:hover { border-color:#2563eb; background:#f0f6ff; }
.upload-zone svg { width:40px; height:40px; color:#94a3b8; }
.upload-zone p { font-size:14px; color:#475569; margin:0; }
.upload-zone p span { color:#2563eb; font-weight:600; }
.upload-zone small { font-size:12px; color:#94a3b8; }

.img-preview-wrap {
  display:flex; align-items:center; gap:16px; background:#f8fafc;
  border-radius:10px; padding:14px; border:1px solid #e2e8f0;
}
.img-preview { width:90px; height:90px; object-fit:cover; border-radius:8px; flex-shrink:0; }
.img-actions { display:flex; flex-direction:column; gap:8px; }
.img-change {
  padding:7px 14px; border-radius:7px; border:1px solid #e2e8f0;
  background:white; font-size:12px; font-weight:600; color:#475569;
  cursor:pointer; transition:all .2s;
}
.img-change:hover { border-color:#2563eb; color:#2563eb; }
.img-remove-btn {
  padding:7px 14px; border-radius:7px; border:1px solid #fecaca;
  background:#fef2f2; font-size:12px; font-weight:600; color:#ef4444;
  cursor:pointer; transition:all .2s;
}
.img-remove-btn:hover { background:#fee2e2; }

/* FORM */
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-group label { font-size:12px; font-weight:600; color:#475569; letter-spacing:.03em; }
.required { color:#ef4444; }
.form-group input,
.form-group select {
  padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0;
  font-size:13px; color:#0f172a; outline:none; transition:border-color .2s; background:#fff;
}
.form-group input:focus,
.form-group select:focus {
  border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.08);
}
.form-error {
  font-size:12px; color:#ef4444; background:#fef2f2;
  border:1px solid #fecaca; padding:8px 12px; border-radius:8px; margin:0;
}
.modal-footer {
  display:flex; justify-content:flex-end; gap:10px;
  padding:16px 24px 20px; border-top:1px solid #f1f5f9;
  position:sticky; bottom:0; background:white;
}
.btn-cancel {
  padding:10px 20px; border-radius:8px; border:1px solid #e2e8f0;
  background:white; font-size:13px; font-weight:600; color:#475569;
  cursor:pointer; transition:all .2s;
}
.btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; }
.btn-submit {
  padding:10px 22px; border-radius:8px; border:none;
  background:linear-gradient(135deg,#2563eb,#4f46e5);
  color:white; font-size:13px; font-weight:600;
  cursor:pointer; transition:opacity .2s,transform .2s;
}
.btn-submit:hover { opacity:.9; transform:translateY(-1px); }

/* ═══════════════════ VARIANT SECTION ═══════════════════ */
.vs-wrapper {
  border:1.5px solid #e2e8f0; border-radius:14px;
  padding:20px; background:#fafbff;
  display:flex; flex-direction:column; gap:16px;
}

/* header row */
.vs-header-row { display:flex; flex-direction:column; gap:10px; }
.vs-section-title {
  font-size:14px; font-weight:700; color:#0f172a;
  display:flex; align-items:center; gap:8px;
}
.vs-bar {
  display:inline-block; width:3px; height:17px;
  background:linear-gradient(180deg,#2563eb,#4f46e5); border-radius:2px; flex-shrink:0;
}
.vs-bar.purple { background:linear-gradient(180deg,#7c3aed,#a855f7); }

/* configured chips */
.configured-chips { display:flex; flex-wrap:wrap; gap:8px; }
.configured-chip {
  display:flex; align-items:center; gap:7px;
  padding:5px 10px 5px 12px; border-radius:20px;
  background:#dcfce7; border:1px solid #bbf7d0;
  font-size:12px; font-weight:600; color:#166534;
}
.chip-count {
  background:#16a34a; color:white; border-radius:10px;
  padding:1px 8px; font-size:11px;
}
.chip-remove {
  background:none; border:none; color:#16a34a; cursor:pointer;
  font-size:16px; line-height:1; padding:0; opacity:.7; transition:opacity .2s;
}
.chip-remove:hover { opacity:1; }

/* STEPS BAR */
.vs-steps { display:flex; align-items:center; }
.vs-step { display:flex; align-items:center; gap:7px; font-size:12px; color:#94a3b8; font-weight:500; }
.vs-step.step-active { color:#2563eb; }
.vs-step.step-done   { color:#16a34a; }
.step-dot {
  width:24px; height:24px; border-radius:50%; background:#e2e8f0; color:#94a3b8;
  font-size:11px; font-weight:700; display:flex; align-items:center;
  justify-content:center; flex-shrink:0;
}
.step-active .step-dot { background:#2563eb; color:white; }
.step-done   .step-dot { background:#16a34a; color:white; }
.step-label { white-space:nowrap; }
.step-line { flex:1; height:2px; background:#e2e8f0; margin:0 10px; min-width:20px; }
.step-line.line-done { background:#16a34a; }

/* STEP BODIES */
.step-body { display:flex; flex-direction:column; gap:14px; }
.step-hint { font-size:12px; color:#64748b; margin:0; }

.step-nav { display:flex; align-items:center; gap:12px; }
.step-nav-title { font-size:13px; font-weight:700; color:#0f172a; }
.back-btn {
  padding:6px 12px; border-radius:7px; border:1px solid #e2e8f0;
  background:white; font-size:12px; font-weight:600; color:#475569;
  cursor:pointer; white-space:nowrap; transition:all .2s; flex-shrink:0;
}
.back-btn:hover { border-color:#2563eb; color:#2563eb; }

/* GROUP GRID */
.group-grid { display:flex; flex-direction:column; gap:7px; }
.group-card {
  display:flex; align-items:center; gap:12px;
  padding:11px 14px; border-radius:10px; border:1.5px solid #e2e8f0;
  background:white; cursor:pointer; text-align:left; width:100%;
  transition:all .2s;
}
.group-card:hover { border-color:#2563eb; background:#f0f6ff; }
.group-card.group-done { border-color:#bbf7d0; background:#f0fdf4; }
.group-icon { font-size:20px; flex-shrink:0; }
.group-info { flex:1; display:flex; flex-direction:column; gap:2px; }
.group-info b    { font-size:13px; color:#0f172a; font-weight:700; }
.group-info span { font-size:11px; color:#64748b; }
.group-arrow { color:#94a3b8; font-size:18px; }
.group-check { font-size:11px; font-weight:700; color:#16a34a;
  background:#dcfce7; padding:3px 10px; border-radius:20px; white-space:nowrap; }

/* ATTR TYPE BLOCKS */
.attr-type-blocks { display:flex; flex-direction:column; gap:12px; }
.attr-type-block {
  background:white; border:1px solid #e8edf5; border-radius:10px; padding:14px 16px;
}
.attr-type-label {
  font-size:11px; font-weight:700; color:#475569;
  text-transform:uppercase; letter-spacing:.06em;
  margin-bottom:10px; display:flex; align-items:center; gap:7px;
}
.type-dot {
  display:inline-block; width:6px; height:6px; border-radius:50%; background:#2563eb;
}
.attr-chip-list { display:flex; flex-wrap:wrap; gap:7px; }
.attr-chip {
  padding:7px 14px; border-radius:20px; border:1.5px solid #e2e8f0;
  background:white; font-size:12px; font-weight:500; color:#334155;
  cursor:pointer; transition:all .18s; display:flex; align-items:center; gap:5px;
}
.attr-chip:hover { border-color:#2563eb; color:#2563eb; }
.attr-chip.attr-chip-on {
  background:#eff6ff; border-color:#2563eb; color:#1d4ed8; font-weight:700;
}
.chip-tick { display:flex; align-items:center; }

/* STEP 2 FOOTER */
.step2-footer {
  display:flex; justify-content:space-between; align-items:center;
  padding-top:8px; border-top:1px solid #e8edf5;
}
.selected-count { font-size:12px; color:#64748b; }
.selected-count b { color:#2563eb; }
.btn-next {
  padding:9px 18px; border-radius:8px; border:none;
  background:linear-gradient(135deg,#2563eb,#4f46e5);
  color:white; font-size:12px; font-weight:700;
  cursor:pointer; transition:opacity .2s;
}
.btn-next:disabled { opacity:.4; cursor:not-allowed; }
.btn-next:not(:disabled):hover { opacity:.9; }

/* VARIANT TABLE */
.vt-table-wrap {
  border:1px solid #e8edf5; border-radius:10px;
  overflow:auto; background:white;
}
.vt-table { width:100%; border-collapse:collapse; min-width:620px; }
.vt-table thead tr { background:#f8fafc; }
.vt-table thead th {
  padding:10px 12px; font-size:10px; font-weight:700; color:#94a3b8;
  text-align:left; letter-spacing:.06em; border-bottom:1px solid #f1f5f9; white-space:nowrap;
}
.vt-table tbody tr { border-bottom:1px solid #f8fafc; transition:background .15s; }
.vt-table tbody tr:last-child { border-bottom:none; }
.vt-table tbody tr:hover { background:#fafbff; }
.vt-table tbody td { padding:9px 12px; vertical-align:middle; }

.vt-input {
  padding:7px 10px; border-radius:7px; border:1px solid #e2e8f0;
  font-size:12px; color:#0f172a; outline:none; background:#fafbff;
  transition:border-color .2s; width:100%; box-sizing:border-box;
}
.vt-input:focus { border-color:#2563eb; }
.vt-num { width:72px !important; }

.vt-select {
  padding:7px 10px; border-radius:7px; border:1px solid #e2e8f0;
  font-size:12px; color:#0f172a; outline:none; background:#fafbff;
  cursor:pointer; min-width:120px; transition:border-color .2s;
}
.vt-select:focus { border-color:#2563eb; }

.color-select-wrap { display:flex; align-items:center; gap:7px; }
.color-dot-sm {
  width:14px; height:14px; border-radius:50%; flex-shrink:0;
  border:1.5px solid rgba(0,0,0,.1);
}
.vt-attr-tag {
  font-size:12px; font-weight:700; color:#1d4ed8;
  background:#eff6ff; padding:4px 10px; border-radius:6px; display:inline-block;
}
.vt-type-badge {
  font-size:11px; font-weight:600; padding:3px 9px; border-radius:5px;
  background:#f3e8ff; color:#7c3aed; white-space:nowrap;
}
.vt-dim { color:#cbd5e1; }
.vt-remove {
  width:26px; height:26px; border-radius:6px; border:1px solid #fecaca;
  background:#fef2f2; color:#ef4444; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s;
}
.vt-remove:hover { background:#fee2e2; }

/* COLOR PREVIEW */
.color-preview-row {
  display:flex; align-items:center; gap:10px; flex-wrap:wrap;
  padding:10px 14px; background:white; border:1px solid #e8edf5;
  border-radius:8px;
}
.cp-label { font-size:11px; font-weight:600; color:#64748b; }
.cp-item { display:flex; align-items:center; gap:5px; font-size:12px; color:#0f172a; }
.cp-dot {
  width:14px; height:14px; border-radius:50%; flex-shrink:0;
  border:1.5px solid rgba(0,0,0,.1);
}

/* STEP 3 FOOTER */
.step3-footer {
  display:flex; justify-content:space-between; align-items:center;
  padding-top:10px; border-top:1px solid #e8edf5; flex-wrap:wrap; gap:10px;
}
.btn-add-row {
  padding:8px 14px; border-radius:8px; border:1.5px dashed #22c55e;
  background:white; font-size:12px; font-weight:600; color:#16a34a;
  cursor:pointer; transition:all .2s;
}
.btn-add-row:hover { background:#f0fdf4; }
.step3-right { display:flex; align-items:center; gap:10px; }
.vt-count-badge {
  font-size:12px; font-weight:600; padding:4px 12px; border-radius:20px;
  background:#eff6ff; color:#2563eb;
}
.btn-confirm {
  padding:9px 18px; border-radius:8px; border:none;
  background:linear-gradient(135deg,#16a34a,#15803d);
  color:white; font-size:12px; font-weight:700;
  cursor:pointer; transition:opacity .2s;
}
.btn-confirm:hover { opacity:.9; }

/* SUMMARY SECTION */
.summary-section {
  border:1px solid #e8edf5; border-radius:10px;
  background:white; overflow:hidden;
}
.summary-title {
  font-size:13px; font-weight:700; color:#0f172a;
  display:flex; align-items:center; gap:8px;
  padding:12px 16px; border-bottom:1px solid #f1f5f9; background:#fafbff;
}
.summary-group { border-bottom:1px solid #f1f5f9; }
.summary-group:last-child { border-bottom:none; }
.summary-group-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:10px 16px; background:#f8fafc;
  font-size:13px; font-weight:700; color:#0f172a;
}
.sg-count {
  font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px;
  background:#dbeafe; color:#1d4ed8;
}
.summary-rows { display:flex; flex-direction:column; gap:0; }
.summary-row {
  display:flex; align-items:center; gap:10px; flex-wrap:wrap;
  padding:8px 16px; border-bottom:1px solid #f8fafc; font-size:12px;
}
.summary-row:last-child { border-bottom:none; }
.sr-attr {
  font-weight:700; color:#0f172a; background:#eff6ff;
  padding:2px 9px; border-radius:5px; font-size:11px;
}
.sr-type {
  padding:2px 9px; border-radius:5px;
  background:#f3e8ff; color:#7c3aed; font-size:11px; font-weight:600;
}
.sr-color { display:flex; align-items:center; gap:5px; font-size:12px; color:#334155; }
.sr-dot {
  width:12px; height:12px; border-radius:50%; flex-shrink:0;
  border:1.5px solid rgba(0,0,0,.1);
}
.sr-price { color:#16a34a; font-weight:600; }
.sr-stock { color:#64748b; }

/* ═══════════════════ RESPONSIVE ═══════════════════ */
@media (max-width:768px) {
  .admin { padding:20px 16px; }
  .stats { grid-template-columns:repeat(2,1fr); }
  .filter-bar { flex-wrap:wrap; }
  .table-wrap { overflow-x:auto; }
  table { min-width:700px; }
  .form-row { grid-template-columns:1fr; }
  .modal-wide { max-width:98vw; }
  .step-label { display:none; }
}
</style>