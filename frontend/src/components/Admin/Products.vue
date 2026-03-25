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
   NHÓM THUỘC TÍNH & LOẠI THUỘC TÍNH
═══════════════════════════════════════ */
const attributeGroups = ref([
  {
    id: 1, name: 'Cấu hình Laptop', icon: '💻',
    attrTypes: [
      { id: 'cpu', label: 'CPU', color: 'blue', options: ['Intel Core i5-13500H', 'Intel Core i7-13700H', 'Intel Core i9-14900H', 'Apple M3 Pro', 'AMD Ryzen 7 7745HX'] },
      { id: 'ram', label: 'RAM', color: 'green', options: ['8GB DDR5', '16GB DDR5', '32GB DDR5', '64GB DDR5'] },
      { id: 'ssd', label: 'SSD', color: 'amber', options: ['256GB NVMe', '512GB NVMe', '1TB NVMe Gen5', '2TB NVMe Gen5'] },
      { id: 'gpu', label: 'GPU', color: 'pink', options: ['Intel Iris Xe', 'NVIDIA RTX 4060', 'NVIDIA RTX 4070', 'NVIDIA RTX 4080'] },
      { id: 'os', label: 'OS', color: 'purple', options: ['Windows 11 Home', 'Windows 11 Pro', 'macOS Sonoma', 'Không có OS'] },
    ]
  },
  {
    id: 2, name: 'Điện thoại', icon: '📱',
    attrTypes: [
      { id: 'chip', label: 'Chip', color: 'blue', options: ['Snapdragon 8 Gen 3', 'Dimensity 9300', 'Apple A17 Pro', 'Apple A16 Bionic'] },
      { id: 'ram', label: 'RAM', color: 'green', options: ['8GB', '12GB', '16GB'] },
      { id: 'storage', label: 'Bộ nhớ', color: 'amber', options: ['128GB', '256GB', '512GB', '1TB'] },
      { id: 'network', label: 'Mạng', color: 'teal', options: ['4G LTE', '5G Sub-6', '5G mmWave'] },
    ]
  },
  {
    id: 3, name: 'Màn hình', icon: '🖥️',
    attrTypes: [
      { id: 'screen_size', label: 'Kích thước', color: 'purple', options: ['13.3 inch', '14 inch', '15.6 inch', '16 inch', '27 inch'] },
      { id: 'screen_res', label: 'Độ phân giải', color: 'pink', options: ['FHD 1920×1080', '2K 2560×1440', '4K 3840×2160'] },
      { id: 'screen_panel', label: 'Tấm nền', color: 'blue', options: ['IPS', 'OLED', 'AMOLED', 'Mini-LED'] },
    ]
  },
  {
    id: 4, name: 'Pin & Sạc', icon: '🔋',
    attrTypes: [
      { id: 'battery', label: 'Pin', color: 'green', options: ['40Wh', '50Wh', '60Wh', '72Wh', '100Wh'] },
      { id: 'charger', label: 'Sạc', color: 'amber', options: ['30W USB-C', '45W USB-C', '65W USB-C', '140W MagSafe'] },
    ]
  },
  {
    id: 5, name: 'Kết nối', icon: '📡',
    attrTypes: [
      { id: 'wifi', label: 'WiFi', color: 'blue', options: ['WiFi 5', 'WiFi 6', 'WiFi 6E', 'WiFi 7'] },
      { id: 'bt', label: 'Bluetooth', color: 'purple', options: ['Bluetooth 5.0', 'Bluetooth 5.2', 'Bluetooth 5.3'] },
      { id: 'port', label: 'Cổng', color: 'pink', options: ['USB-A x2', 'USB-C x2', 'Thunderbolt 4', 'HDMI 2.1'] },
    ]
  },
  {
    id: 6, name: 'Phụ kiện', icon: '🖱️',
    attrTypes: [
      { id: 'acc_type', label: 'Loại', color: 'teal', options: ['Chuột không dây', 'Chuột có dây', 'Bàn phím cơ', 'Tai nghe over-ear', 'Tai nghe in-ear'] },
      { id: 'acc_conn', label: 'Kết nối', color: 'blue', options: ['USB-A', 'USB-C', 'Bluetooth 5.3', '2.4GHz Dongle', '3.5mm Jack'] },
    ]
  },
])

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
   VARIANT STATE — FLAT TABLE + COMBO FLOW
   
   vsPhase 1: chọn nhóm + chọn giá trị (bảng phẳng)
   vsPhase 2: bảng tổ hợp tự động
   
   selectedGroupId: nhóm đang xem
   selectedOptions : { [typeId]: Set<value> }
   generatedRows   : [{ id, attrs, price, stock }]
═══════════════════════════════════════ */
const vsPhase = ref(1)
const selectedGroupId = ref(null)
const selectedOptions = ref({})
const generatedRows = ref([])
const bulkPrice = ref('')
const bulkStock = ref('')

const selectedGroup = computed(() =>
  attributeGroups.value.find(g => g.id === selectedGroupId.value) || null
)

/* Các loại thuộc tính đang có giá trị được chọn */
const activeAttrTypes = computed(() => {
  if (!selectedGroup.value) return []
  return selectedGroup.value.attrTypes.filter(
    t => selectedOptions.value[t.id]?.size > 0
  )
})

const totalSelected = computed(() =>
  Object.values(selectedOptions.value).reduce((s, set) => s + (set?.size ?? 0), 0)
)

const comboCount = computed(() => {
  if (activeAttrTypes.value.length === 0) return 0
  return activeAttrTypes.value.reduce(
    (prod, t) => prod * (selectedOptions.value[t.id]?.size ?? 0), 1
  )
})

const selectGroup = groupId => {
  if (selectedGroupId.value === groupId) return
  selectedGroupId.value = groupId
  selectedOptions.value = {}
}

const toggleOption = (typeId, value) => {
  if (!selectedOptions.value[typeId]) selectedOptions.value[typeId] = new Set()
  const set = selectedOptions.value[typeId]
  if (set.has(value)) set.delete(value)
  else set.add(value)
  selectedOptions.value = { ...selectedOptions.value }
}

const isSelected = (typeId, value) =>
  selectedOptions.value[typeId]?.has(value) ?? false

const selectedCountInGroup = g =>
  g.attrTypes.reduce((s, t) => s + (selectedOptions.value[t.id]?.size ?? 0), 0)

/* Cartesian product */
const cartesian = arrays => {
  if (arrays.length === 0) return [[]]
  const [first, ...rest] = arrays
  const tail = cartesian(rest)
  return first.flatMap(v => tail.map(c => [v, ...c]))
}

const generateVariants = () => {
  if (!activeAttrTypes.value.length) return
  const arrays = activeAttrTypes.value.map(t => [...selectedOptions.value[t.id]])
  const combos = cartesian(arrays)
  generatedRows.value = combos.map((combo, i) => {
    const attrs = {}
    activeAttrTypes.value.forEach((t, ti) => { attrs[t.id] = combo[ti] })
    return { id: Date.now() + i, attrs, price: '', stock: 0 }
  })
  vsPhase.value = 2
}

const backToSelect = () => { vsPhase.value = 1 }
const removeRow = i => { if (generatedRows.value.length > 1) generatedRows.value.splice(i, 1) }

const applyBulkAll = () => {
  generatedRows.value.forEach(r => {
    if (bulkPrice.value) r.price = bulkPrice.value
    if (bulkStock.value !== '') r.stock = Number(bulkStock.value)
  })
}

/* ═══════════════════════════════════════
   OPEN / CLOSE / SUBMIT
═══════════════════════════════════════ */
const openModal = () => {
  form.value = defaultForm()
  imgPreview.value = ''
  formError.value = ''
  vsPhase.value = 1
  selectedGroupId.value = null
  selectedOptions.value = {}
  generatedRows.value = []
  bulkPrice.value = ''
  bulkStock.value = ''
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
        <option>Màn hình</option>
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
            <td><span class="status-badge" :class="p.status === 'Đang bán' ? 'active' : 'draft'">{{ p.status }}</span>
            </td>
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
                  <option>Màn hình</option>
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
                <div class="vs-steps">
                  <span class="vss" :class="{ active: vsPhase === 1, done: vsPhase === 2 }">
                    <span class="vss-dot">{{ vsPhase === 2 ? '✓' : '1' }}</span>
                    Chọn giá trị
                  </span>
                  <span class="vss-line"></span>
                  <span class="vss" :class="{ active: vsPhase === 2 }">
                    <span class="vss-dot">2</span>
                    Điền giá &amp; kho
                  </span>
                </div>
              </div>

              <!-- ══════════ PHASE 1 ══════════ -->
              <template v-if="vsPhase === 1">

                <!-- Chọn nhóm thuộc tính -->
                <div class="group-tabs">
                  <button v-for="g in attributeGroups" :key="g.id" class="gtab"
                    :class="{ 'gtab-active': selectedGroupId === g.id }" @click="selectGroup(g.id)">
                    <span class="gtab-icon">{{ g.icon }}</span>
                    <span class="gtab-name">{{ g.name }}</span>
                    <span v-if="selectedCountInGroup(g) > 0" class="gtab-badge">
                      {{ selectedCountInGroup(g) }}
                    </span>
                  </button>
                </div>

                <!-- Bảng chọn giá trị (flat table) -->
                <div v-if="selectedGroup" class="flat-select-table">
                  <div v-for="t in selectedGroup.attrTypes" :key="t.id" class="fst-row">
                    <!-- Label loại -->
                    <div class="fst-label">
                      <span class="type-pill" :class="'tp-' + t.color">{{ t.label }}</span>
                      <span v-if="selectedOptions[t.id]?.size" class="fst-count">
                        {{ selectedOptions[t.id].size }}
                      </span>
                    </div>

                    <!-- Nút toggle giá trị -->
                    <div class="fst-options">
                      <button v-for="opt in t.options" :key="opt" class="vbtn"
                        :class="['vbtn-' + t.color, { 'vbtn-on': isSelected(t.id, opt) }]"
                        @click="toggleOption(t.id, opt)">
                        <svg v-if="isSelected(t.id, opt)" viewBox="0 0 10 10" fill="none" stroke="currentColor"
                          stroke-width="2.2" stroke-linecap="round" width="9" height="9">
                          <polyline points="1,5 3.5,7.5 9,2" />
                        </svg>
                        {{ opt }}
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Placeholder khi chưa chọn nhóm -->
                <div v-else class="group-placeholder">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                    width="28" height="28">
                    <rect x="3" y="3" width="18" height="18" rx="3" />
                    <path d="M9 12h6M12 9v6" />
                  </svg>
                  <span>Chọn nhóm thuộc tính ở trên để bắt đầu</span>
                </div>

                <!-- Footer phase 1 -->
                <div v-if="selectedGroup" class="p1-footer">
                  <!-- Combo preview -->
                  <div v-if="activeAttrTypes.length > 0" class="combo-bar">
                    <span class="combo-formula">
                      <template v-for="(t, i) in activeAttrTypes" :key="t.id">
                        <span class="cf-item">
                          <span class="type-pill-sm" :class="'tp-' + t.color">{{ t.label }}</span>
                          <b>{{ selectedOptions[t.id]?.size }}</b>
                        </span>
                        <span v-if="i < activeAttrTypes.length - 1" class="cf-x">×</span>
                      </template>
                      <span class="cf-eq">= <b>{{ comboCount }} biến thể</b></span>
                    </span>
                  </div>

                  <div class="p1-actions">
                    <span v-if="!activeAttrTypes.length" class="p1-hint">
                      Chọn ít nhất 1 giá trị từ bất kỳ loại nào
                    </span>
                    <button class="btn-generate" :disabled="!activeAttrTypes.length" @click="generateVariants">
                      <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" width="13" height="13">
                        <rect x="1" y="1" width="12" height="12" rx="2" />
                        <polyline points="3.5,7 5.5,9 10.5,4.5" />
                      </svg>
                      Tạo {{ comboCount > 0 ? comboCount + ' ' : '' }}biến thể
                    </button>
                  </div>
                </div>

              </template>

              <!-- ══════════ PHASE 2 ══════════ -->
              <template v-if="vsPhase === 2">

                <!-- Toolbar -->
                <div class="p2-toolbar">
                  <button class="btn-back" @click="backToSelect">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                      width="11" height="11">
                      <polyline points="7.5,1.5 3,6 7.5,10.5" />
                    </svg>
                    Chỉnh lại lựa chọn
                  </button>
                  <div class="bulk-bar">
                    <span class="bulk-lbl">Điền nhanh:</span>
                    <input v-model="bulkPrice" class="bulk-in" placeholder="Giá chung" />
                    <input v-model="bulkStock" class="bulk-in bulk-num" type="number" min="0" placeholder="Kho" />
                    <button class="btn-apply" @click="applyBulkAll">Áp dụng tất cả</button>
                  </div>
                </div>

                <!-- Info -->
                <div class="p2-info">
                  Đã tạo <b>{{ generatedRows.length }}</b> tổ hợp từ
                  <b>{{ activeAttrTypes.length }}</b> loại thuộc tính —
                  mỗi hàng là duy nhất, không trùng lặp.
                </div>

                <!-- Bảng -->
                <div class="vt-scroll">
                  <table class="vt-table">
                    <thead>
                      <tr>
                        <th class="th-no">#</th>
                        <th v-for="t in activeAttrTypes" :key="t.id">
                          <span class="type-pill" :class="'tp-' + t.color">{{ t.label }}</span>
                        </th>
                        <th class="th-price">Giá riêng (₫)</th>
                        <th class="th-stock">Kho</th>
                        <th class="th-act"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, ri) in generatedRows" :key="row.id" class="vt-row">
                        <td class="td-no"><span class="row-no">{{ ri + 1 }}</span></td>
                        <td v-for="t in activeAttrTypes" :key="t.id">
                          <span class="val-chip" :class="'vc-' + t.color">
                            {{ row.attrs[t.id] }}
                          </span>
                        </td>
                        <td><input v-model="row.price" class="vt-input" placeholder="45.990.000" /></td>
                        <td><input v-model="row.stock" type="number" min="0" class="vt-input vt-num" /></td>
                        <td class="td-act">
                          <button class="ra-del" title="Xóa" @click="removeRow(ri)">
                            <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2"
                              stroke-linecap="round" width="10" height="10">
                              <line x1="1" y1="1" x2="11" y2="11" />
                              <line x1="11" y1="1" x2="1" y2="11" />
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="p2-foot">
                  <span class="p2-count"><b>{{ generatedRows.length }}</b> biến thể</span>
                </div>

              </template>

            </div><!-- /vs-wrapper -->

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

/* ══ MODAL ══ */
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
  max-width: 960px;
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
}

.btn-submit:hover {
  opacity: .9;
  transform: translateY(-1px);
}

/* ══════════════════════════════════════
   VARIANT SECTION
══════════════════════════════════════ */
.vs-wrapper {
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  padding: 18px 20px;
  background: #fafbff;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

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

/* Step indicator */
.vs-steps {
  display: flex;
  align-items: center;
  gap: 8px;
}

.vss {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #94a3b8;
}

.vss.active {
  color: #2563eb;
}

.vss.done {
  color: #16a34a;
}

.vss-dot {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  border: 2px solid #e2e8f0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: 700;
  background: white;
}

.vss.active .vss-dot {
  border-color: #2563eb;
  background: #2563eb;
  color: white;
}

.vss.done .vss-dot {
  border-color: #16a34a;
  background: #16a34a;
  color: white;
}

.vss-line {
  width: 24px;
  height: 2px;
  background: #e2e8f0;
  border-radius: 1px;
}

/* ── GROUP TABS ── */
.group-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.gtab {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 13px;
  border-radius: 8px;
  border: 1.5px solid #e2e8f0;
  background: white;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
  transition: all .15s;
  font-family: inherit;
}

.gtab:hover {
  border-color: #93c5fd;
  color: #2563eb;
}

.gtab-active {
  border-color: #2563eb !important;
  background: #eff6ff !important;
  color: #1d4ed8 !important;
}

.gtab-icon {
  font-size: 15px;
}

.gtab-name {
  white-space: nowrap;
}

.gtab-badge {
  min-width: 18px;
  height: 18px;
  border-radius: 9px;
  padding: 0 5px;
  background: #2563eb;
  color: white;
  font-size: 10px;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* ── FLAT SELECT TABLE ── */
.flat-select-table {
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 12px;
  overflow: hidden;
}

.fst-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 11px 16px;
  border-bottom: 1px solid #f1f5f9;
}

.fst-row:last-child {
  border-bottom: none;
}

.fst-label {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
  min-width: 72px;
  flex-shrink: 0;
  padding-top: 2px;
}

.fst-count {
  font-size: 10px;
  font-weight: 700;
  color: #2563eb;
  background: #dbeafe;
  padding: 1px 6px;
  border-radius: 8px;
}

.fst-options {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  flex: 1;
}

/* Type pill (label) */
.type-pill {
  display: inline-flex;
  align-items: center;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
}

.tp-blue {
  background: #dbeafe;
  color: #1d4ed8;
}

.tp-green {
  background: #dcfce7;
  color: #166534;
}

.tp-amber {
  background: #fef3c7;
  color: #92400e;
}

.tp-pink {
  background: #fce7f3;
  color: #be185d;
}

.tp-purple {
  background: #f3e8ff;
  color: #6b21a8;
}

.tp-teal {
  background: #ccfbf1;
  color: #0f766e;
}

.tp-red {
  background: #fee2e2;
  color: #991b1b;
}

/* Smaller pill for combo formula */
.type-pill-sm {
  display: inline-flex;
  align-items: center;
  padding: 2px 7px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 700;
}

.type-pill-sm.tp-blue {
  background: #dbeafe;
  color: #1d4ed8;
}

.type-pill-sm.tp-green {
  background: #dcfce7;
  color: #166534;
}

.type-pill-sm.tp-amber {
  background: #fef3c7;
  color: #92400e;
}

.type-pill-sm.tp-pink {
  background: #fce7f3;
  color: #be185d;
}

.type-pill-sm.tp-purple {
  background: #f3e8ff;
  color: #6b21a8;
}

.type-pill-sm.tp-teal {
  background: #ccfbf1;
  color: #0f766e;
}

/* Toggle buttons */
.vbtn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 5px 11px;
  border-radius: 6px;
  border: 1.5px solid #e2e8f0;
  background: white;
  font-size: 12px;
  font-weight: 500;
  color: #475569;
  cursor: pointer;
  transition: all .12s;
  white-space: nowrap;
  font-family: inherit;
}

/* OFF hover per color */
.vbtn-blue:hover {
  border-color: #93c5fd;
  color: #2563eb;
}

.vbtn-green:hover {
  border-color: #86efac;
  color: #166534;
}

.vbtn-amber:hover {
  border-color: #fcd34d;
  color: #92400e;
}

.vbtn-pink:hover {
  border-color: #f9a8d4;
  color: #be185d;
}

.vbtn-purple:hover {
  border-color: #d8b4fe;
  color: #6b21a8;
}

.vbtn-teal:hover {
  border-color: #5eead4;
  color: #0f766e;
}

.vbtn-red:hover {
  border-color: #fca5a5;
  color: #991b1b;
}

/* ON state per color */
.vbtn-blue.vbtn-on {
  border-color: #2563eb;
  background: #2563eb;
  color: white;
  font-weight: 600;
}

.vbtn-green.vbtn-on {
  border-color: #16a34a;
  background: #16a34a;
  color: white;
  font-weight: 600;
}

.vbtn-amber.vbtn-on {
  border-color: #d97706;
  background: #d97706;
  color: white;
  font-weight: 600;
}

.vbtn-pink.vbtn-on {
  border-color: #db2777;
  background: #db2777;
  color: white;
  font-weight: 600;
}

.vbtn-purple.vbtn-on {
  border-color: #7c3aed;
  background: #7c3aed;
  color: white;
  font-weight: 600;
}

.vbtn-teal.vbtn-on {
  border-color: #0d9488;
  background: #0d9488;
  color: white;
  font-weight: 600;
}

.vbtn-red.vbtn-on {
  border-color: #dc2626;
  background: #dc2626;
  color: white;
  font-weight: 600;
}

/* Placeholder */
.group-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 28px 20px;
  color: #94a3b8;
  font-size: 12px;
  background: white;
  border: 1.5px dashed #e2e8f0;
  border-radius: 10px;
}

/* ── PHASE 1 FOOTER ── */
.p1-footer {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 13px 16px;
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 10px;
}

.combo-bar {}

.combo-formula {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
}

.cf-item {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.cf-item b {
  font-size: 13px;
  color: #0f172a;
}

.cf-x {
  font-size: 13px;
  color: #94a3b8;
  font-weight: 600;
}

.cf-eq {
  font-size: 12px;
  color: #64748b;
  padding-left: 4px;
}

.cf-eq b {
  font-size: 13px;
  color: #2563eb;
}

.p1-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}

.p1-hint {
  font-size: 12px;
  color: #94a3b8;
}

.btn-generate {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 22px;
  border-radius: 10px;
  border: none;
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: white;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
  transition: opacity .2s, transform .2s;
  font-family: inherit;
}

.btn-generate:hover {
  opacity: .9;
  transform: translateY(-1px);
}

.btn-generate:disabled {
  background: #e2e8f0;
  color: #94a3b8;
  cursor: not-allowed;
  box-shadow: none;
  transform: none;
}

/* ── PHASE 2 TOOLBAR ── */
.p2-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 13px;
  border-radius: 8px;
  border: 1.5px solid #e2e8f0;
  background: white;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
  transition: all .2s;
  font-family: inherit;
}

.btn-back:hover {
  border-color: #2563eb;
  color: #2563eb;
  background: #eff6ff;
}

.bulk-bar {
  display: flex;
  align-items: center;
  gap: 7px;
  flex-wrap: wrap;
  padding: 7px 11px;
  background: #f8fafc;
  border-radius: 9px;
  border: 1px solid #f1f5f9;
}

.bulk-lbl {
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  white-space: nowrap;
}

.bulk-in {
  padding: 6px 9px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  font-size: 12px;
  color: #0f172a;
  outline: none;
  background: white;
  transition: border-color .2s;
  width: 108px;
  font-family: inherit;
}

.bulk-in:focus {
  border-color: #2563eb;
}

.bulk-num {
  width: 68px;
  text-align: right;
}

.btn-apply {
  padding: 6px 13px;
  border-radius: 7px;
  border: none;
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: white;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  font-family: inherit;
}

.btn-apply:hover {
  opacity: .88;
}

.p2-info {
  font-size: 12px;
  color: #92400e;
  padding: 8px 13px;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 8px;
}

.p2-info b {
  color: #92400e;
}

/* Variant table */
.vt-scroll {
  overflow-x: auto;
  border: 1px solid #e8edf5;
  border-radius: 12px;
  background: white;
}

.vt-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 480px;
}

.vt-table thead tr {
  background: #f8fafc;
}

.vt-table thead th {
  padding: 9px 12px;
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-align: left;
  letter-spacing: .06em;
  border-bottom: 1px solid #f1f5f9;
  white-space: nowrap;
}

.th-no {
  width: 38px;
  text-align: center;
}

.th-price {
  min-width: 138px;
}

.th-stock {
  width: 76px;
}

.th-act {
  width: 44px;
  text-align: center;
}

.vt-row {
  border-bottom: 1px solid #f8fafc;
  transition: background .12s;
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

.td-act {
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

/* Value chip in table */
.val-chip {
  display: inline-block;
  font-size: 12px;
  font-weight: 500;
  padding: 3px 9px;
  border-radius: 5px;
  white-space: nowrap;
  border: 1px solid;
}

.vc-blue {
  background: #eff6ff;
  border-color: #bfdbfe;
  color: #1d4ed8;
}

.vc-green {
  background: #f0fdf4;
  border-color: #bbf7d0;
  color: #166534;
}

.vc-amber {
  background: #fffbeb;
  border-color: #fde68a;
  color: #92400e;
}

.vc-pink {
  background: #fdf2f8;
  border-color: #f9a8d4;
  color: #be185d;
}

.vc-purple {
  background: #faf5ff;
  border-color: #e9d5ff;
  color: #6b21a8;
}

.vc-teal {
  background: #f0fdfa;
  border-color: #99f6e4;
  color: #0f766e;
}

.vc-red {
  background: #fef2f2;
  border-color: #fecaca;
  color: #991b1b;
}

.vt-input {
  width: 100%;
  padding: 7px 9px;
  border-radius: 7px;
  border: 1px solid #e2e8f0;
  font-size: 12px;
  color: #0f172a;
  outline: none;
  background: #fafbff;
  transition: border-color .2s;
  box-sizing: border-box;
  font-family: inherit;
}

.vt-input:focus {
  border-color: #2563eb;
}

.vt-num {
  text-align: right;
}

.ra-del {
  width: 26px;
  height: 26px;
  border-radius: 6px;
  border: 1px solid #e2e8f0;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  transition: all .2s;
}

.ra-del:hover {
  border-color: #fecaca;
  background: #fef2f2;
  color: #ef4444;
}

.p2-foot {
  display: flex;
  justify-content: flex-end;
}

.p2-count {
  font-size: 12px;
  color: #64748b;
}

.p2-count b {
  color: #0f172a;
}

/* ══ RESPONSIVE ══ */
@media (max-width: 768px) {
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

  .vs-steps {
    display: none;
  }

  .p2-toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .fst-row {
    flex-direction: column;
    gap: 8px;
  }
}
</style>