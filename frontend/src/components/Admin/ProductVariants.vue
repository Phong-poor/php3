<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('Biến thể cấu hình')

const showModal = ref(false)
const modalType = ref('variant')
const formError = ref('')

const typeStyle = {
  'RAM':    { bg: '#dbeafe', color: '#1d4ed8' },
  'Ổ CỨNG': { bg: '#dcfce7', color: '#15803d' },
  'CPU':    { bg: '#ede9fe', color: '#6d28d9' },
}

// ── Data ──
const variants = ref([
  { id:1,  name:'16GB Unified Memory',    type:'RAM',    status:'Hoạt động' },
  { id:2,  name:'512GB SSD Storage',      type:'Ổ CỨNG', status:'Hoạt động' },
  { id:3,  name:'Apple M3 Pro (12-core)', type:'CPU',    status:'Nháp'      },
  { id:4,  name:'1TB NVMe Gen5',          type:'Ổ CỨNG', status:'Hoạt động' },
  { id:5,  name:'32GB DDR5 RAM',          type:'RAM',    status:'Hoạt động' },
  { id:6,  name:'Intel Core i9-14900H',   type:'CPU',    status:'Hoạt động' },
  { id:7,  name:'2TB PCIe SSD',           type:'Ổ CỨNG', status:'Nháp'      },
  { id:8,  name:'8GB LPDDR5',             type:'RAM',    status:'Hoạt động' },
  { id:9,  name:'AMD Ryzen 9 7940HX',     type:'CPU',    status:'Hoạt động' },
  { id:10, name:'512GB NVMe Gen4',        type:'Ổ CỨNG', status:'Nháp'      },
])

const colors = ref([
  { id:1, name:'Space Black', hex:'#1a1a1a', count:6, stock:'Khả dụng' },
  { id:2, name:'Silver',      hex:'#c0c0c0', count:4, stock:'Khả dụng' },
  { id:3, name:'Midnight',    hex:'#1c2b3a', count:5, stock:'Khả dụng' },
  { id:4, name:'Starlight',   hex:'#e8e0d0', count:3, stock:'Hết hàng' },
  { id:5, name:'Rose Gold',   hex:'#b76e79', count:2, stock:'Khả dụng' },
  { id:6, name:'Pacific Blue',hex:'#1e5799', count:7, stock:'Khả dụng' },
  { id:7, name:'Alpine Green',hex:'#4a7c59', count:3, stock:'Hết hàng' },
])

const groups = ref([
  { id:1, name:'Cấu hình Laptop', desc:'RAM, CPU, SSD dành cho laptop',  attrCount:3 },
  { id:2, name:'Phụ kiện',        desc:'Chuột, bàn phím, màn hình',      attrCount:2 },
  { id:3, name:'Màn hình',        desc:'Kích thước và độ phân giải',     attrCount:4 },
  { id:4, name:'Pin & Sạc',       desc:'Dung lượng pin và adapter sạc',  attrCount:2 },
  { id:5, name:'Kết nối',         desc:'WiFi, Bluetooth, cổng USB',      attrCount:5 },
  { id:6, name:'Âm thanh',        desc:'Loa, micro, jack cắm tai nghe',  attrCount:3 },
  { id:7, name:'Bảo mật',         desc:'Vân tay, nhận diện khuôn mặt',  attrCount:2 },
])

const attrs = ref([
  { id:1,  name:'RAM 8GB',      group:'Cấu hình Laptop', status:'Hoạt động' },
  { id:2,  name:'RAM 16GB',     group:'Cấu hình Laptop', status:'Hoạt động' },
  { id:3,  name:'SSD 256GB',    group:'Cấu hình Laptop', status:'Nháp'      },
  { id:4,  name:'Chuột không dây', group:'Phụ kiện',     status:'Hoạt động' },
  { id:5,  name:'Bàn phím cơ',  group:'Phụ kiện',        status:'Hoạt động' },
  { id:6,  name:'15.6 inch FHD',group:'Màn hình',        status:'Hoạt động' },
  { id:7,  name:'14 inch 2K',   group:'Màn hình',        status:'Nháp'      },
  { id:8,  name:'Pin 72Wh',     group:'Pin & Sạc',       status:'Hoạt động' },
])

const selectedColor = ref(colors.value[0])

// ── Pagination ──
const PER_PAGE = 6

const variantPage  = ref(1)
const colorPage    = ref(1)
const groupPage    = ref(1)
const attrPage     = ref(1)

const paginate = (list, page) => {
  const s = (page - 1) * PER_PAGE
  return list.slice(s, s + PER_PAGE)
}
const totalPages = (list) => Math.max(1, Math.ceil(list.length / PER_PAGE))

const pagedVariants = computed(() => paginate(variants.value, variantPage.value))
const pagedColors   = computed(() => paginate(colors.value,   colorPage.value))
const pagedGroups   = computed(() => paginate(groups.value,   groupPage.value))
const pagedAttrs    = computed(() => paginate(attrs.value,    attrPage.value))

const variantPages = computed(() => totalPages(variants.value))
const colorPages   = computed(() => totalPages(colors.value))
const groupPages   = computed(() => totalPages(groups.value))
const attrPages    = computed(() => totalPages(attrs.value))

// ── Forms ──
const defaultVariantForm = () => ({ name:'', type:'RAM', status:'Hoạt động' })
const defaultColorForm   = () => ({ name:'', hex:'#000000', stock:'Khả dụng' })
const defaultGroupForm   = () => ({ name:'', desc:'' })
const defaultAttrForm    = () => ({ name:'', group: groups.value[0]?.name || '', status:'Hoạt động' })

const variantForm = ref(defaultVariantForm())
const colorForm   = ref(defaultColorForm())
const groupForm   = ref(defaultGroupForm())
const attrForm    = ref(defaultAttrForm())
let editingId = null

// ── Modal ──
const openModal = (type, item = null) => {
  modalType.value = type; formError.value = ''; editingId = item?.id ?? null
  if      (type === 'variant')    variantForm.value = defaultVariantForm()
  else if (type === 'editVariant' && item) variantForm.value = { ...item }
  else if (type === 'color')      colorForm.value = defaultColorForm()
  else if (type === 'editColor'   && item) colorForm.value = { ...item }
  else if (type === 'group')      groupForm.value = defaultGroupForm()
  else if (type === 'editGroup'   && item) groupForm.value = { name:item.name, desc:item.desc }
  else if (type === 'attr')       attrForm.value = defaultAttrForm()
  else if (type === 'editAttr'    && item) attrForm.value = { name:item.name, group:item.group, status:item.status }
  showModal.value = true
}
const closeModal = () => { showModal.value = false }

// ── Submits ──
const submitVariant = () => {
  if (!variantForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên biến thể.'; return }
  if (modalType.value === 'editVariant') {
    const idx = variants.value.findIndex(v => v.id === editingId)
    if (idx !== -1) variants.value[idx] = { ...variants.value[idx], ...variantForm.value }
  } else { variants.value.unshift({ id: Date.now(), ...variantForm.value }) }
  closeModal()
}
const submitColor = () => {
  if (!colorForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên màu.'; return }
  if (modalType.value === 'editColor') {
    const idx = colors.value.findIndex(c => c.id === editingId)
    if (idx !== -1) colors.value[idx] = { ...colors.value[idx], ...colorForm.value }
  } else { colors.value.unshift({ id: Date.now(), count:0, ...colorForm.value }) }
  closeModal()
}
const submitGroup = () => {
  if (!groupForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên nhóm.'; return }
  if (modalType.value === 'editGroup') {
    const idx = groups.value.findIndex(g => g.id === editingId)
    if (idx !== -1) groups.value[idx] = { ...groups.value[idx], ...groupForm.value }
  } else { groups.value.unshift({ id: Date.now(), attrCount:0, ...groupForm.value }) }
  closeModal()
}
const submitAttr = () => {
  if (!attrForm.value.name.trim()) { formError.value = 'Vui lòng nhập tên thuộc tính.'; return }
  if (!attrForm.value.group) { formError.value = 'Vui lòng chọn nhóm thuộc tính.'; return }
  if (modalType.value === 'editAttr') {
    const idx = attrs.value.findIndex(a => a.id === editingId)
    if (idx !== -1) attrs.value[idx] = { ...attrs.value[idx], ...attrForm.value }
  } else { attrs.value.unshift({ id: Date.now(), ...attrForm.value }) }
  closeModal()
}
const handleSubmit = () => {
  if (['variant','editVariant'].includes(modalType.value)) return submitVariant()
  if (['color','editColor'].includes(modalType.value))     return submitColor()
  if (['group','editGroup'].includes(modalType.value))     return submitGroup()
  if (['attr','editAttr'].includes(modalType.value))       return submitAttr()
}

// ── Delete ──
const removeVariant = (id) => { variants.value = variants.value.filter(v => v.id !== id); if (variantPage.value > variantPages.value) variantPage.value = variantPages.value }
const removeColor   = (id) => {
  if (selectedColor.value?.id === id) selectedColor.value = colors.value.find(c => c.id !== id) || null
  colors.value = colors.value.filter(c => c.id !== id)
  if (colorPage.value > colorPages.value) colorPage.value = colorPages.value
}
const removeGroup = (id) => { groups.value = groups.value.filter(g => g.id !== id); if (groupPage.value > groupPages.value) groupPage.value = groupPages.value }
const removeAttr  = (id) => { attrs.value  = attrs.value.filter(a => a.id !== id);  if (attrPage.value  > attrPages.value)  attrPage.value  = attrPages.value  }

const isEditModal  = computed(() => modalType.value.startsWith('edit'))
const modalTitle   = computed(() => ({
  variant:'Thêm biến thể mới', editVariant:'Chỉnh sửa biến thể',
  color:'Thêm màu sắc mới',   editColor:'Chỉnh sửa màu sắc',
  group:'Tạo nhóm thuộc tính',editGroup:'Chỉnh sửa nhóm thuộc tính',
  attr:'Tạo loại thuộc tính', editAttr:'Chỉnh sửa loại thuộc tính',
}[modalType.value] || ''))
const modalBtnLabel = computed(() => isEditModal.value ? 'Lưu thay đổi' : ({
  variant:'Thêm biến thể', color:'Thêm màu sắc', group:'Tạo nhóm', attr:'Tạo thuộc tính'
}[modalType.value] || 'Lưu'))
</script>

<template>
  <div class="page">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input placeholder="Tìm kiếm hệ thống..."/>
      </div>
      <div class="topbar-right">
        <span class="nav-link">Inventory</span>
        <span class="nav-link">Orders</span>
        <span class="nav-link">Analytics</span>
        <span class="nav-link">Customers</span>
        <button class="icon-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><span class="notif-dot"></span></button>
        <button class="icon-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg></button>
        <div class="av">AV</div>
      </div>
    </div>

    <!-- BREADCRUMB -->
    <div class="breadcrumb"><span>Hệ thống</span><span class="sep">›</span><span class="active-crumb">Quản lý sản phẩm</span></div>

    <!-- PAGE HEADER -->
    <div class="page-header">
      <div>
        <h1>Quản lý biến thể &amp; Màu sắc</h1>
        <p>Cấu hình các thuộc tính kỹ thuật và dải màu sắc dành cho dòng sản phẩm cao cấp VinaTech 2026.</p>
      </div>
      <div class="action-row">
        <button class="btn-secondary" @click="openModal('group')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          Tạo nhóm thuộc tính
        </button>
        <button class="btn-secondary btn-sec-purple" @click="openModal('attr')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          Tạo loại thuộc tính
        </button>
        <button class="btn-new" @click="openModal(activeTab === 'Biến thể cấu hình' ? 'variant' : 'color')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Thêm biến thể mới
        </button>
      </div>
    </div>

    <!-- ══ TOP TABLES: GROUP + ATTR ══ -->
    <div class="top-tables">

      <!-- NHÓM THUỘC TÍNH -->
      <div class="card top-card">
        <div class="card-header">
          <div class="card-title"><span class="bar green"></span>Nhóm thuộc tính</div>
          <button class="btn-new-sm green-btn" @click="openModal('group')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tạo nhóm mới
          </button>
        </div>
        <table>
          <thead><tr><th>TÊN NHÓM</th><th>MÔ TẢ</th><th>SỐ THUỘC TÍNH</th><th>THAO TÁC</th></tr></thead>
          <tbody>
            <tr v-if="pagedGroups.length===0"><td colspan="4" class="empty">Chưa có nhóm nào.</td></tr>
            <tr v-for="g in pagedGroups" :key="g.id">
              <td>
                <div class="name-cell">
                  <div class="cell-icon green-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
                  <span class="variant-name">{{ g.name }}</span>
                </div>
              </td>
              <td class="muted">{{ g.desc || '—' }}</td>
              <td><span class="count-badge">{{ g.attrCount }}</span></td>
              <td>
                <div class="actions">
                  <button class="act-btn" @click="openModal('editGroup',g)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                  <button class="act-btn danger" @click="removeGroup(g.id)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="table-footer">
          <span class="page-info">{{ groups.length }} nhóm</span>
          <div class="pagination">
            <button class="page-btn" :disabled="groupPage===1" @click="groupPage--"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
            <button v-for="p in groupPages" :key="p" class="page-btn" :class="{active:p===groupPage}" @click="groupPage=p">{{p}}</button>
            <button class="page-btn" :disabled="groupPage===groupPages" @click="groupPage++"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
      </div>

      <!-- LOẠI THUỘC TÍNH -->
      <div class="card top-card">
        <div class="card-header">
          <div class="card-title"><span class="bar purple2"></span>Loại thuộc tính</div>
          <button class="btn-new-sm purple-btn" @click="openModal('attr')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tạo loại mới
          </button>
        </div>
        <table>
          <thead><tr><th>TÊN THUỘC TÍNH</th><th>NHÓM</th><th>TRẠNG THÁI</th><th>THAO TÁC</th></tr></thead>
          <tbody>
            <tr v-if="pagedAttrs.length===0"><td colspan="4" class="empty">Chưa có loại thuộc tính nào.</td></tr>
            <tr v-for="a in pagedAttrs" :key="a.id">
              <td>
                <div class="name-cell">
                  <div class="cell-icon purple-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                  <span class="variant-name">{{ a.name }}</span>
                </div>
              </td>
              <td><span class="group-tag">{{ a.group }}</span></td>
              <td><span class="status-dot" :class="a.status==='Hoạt động'?'active':'draft'">● {{ a.status }}</span></td>
              <td>
                <div class="actions">
                  <button class="act-btn" @click="openModal('editAttr',a)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                  <button class="act-btn danger" @click="removeAttr(a.id)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="table-footer">
          <span class="page-info">{{ attrs.length }} thuộc tính</span>
          <div class="pagination">
            <button class="page-btn" :disabled="attrPage===1" @click="attrPage--"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
            <button v-for="p in attrPages" :key="p" class="page-btn" :class="{active:p===attrPage}" @click="attrPage=p">{{p}}</button>
            <button class="page-btn" :disabled="attrPage===attrPages" @click="attrPage++"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
      </div>

    </div>

    <!-- TABS -->
    <div class="tabs">
      <button class="tab" :class="{active:activeTab==='Biến thể cấu hình'}" @click="activeTab='Biến thể cấu hình'">Biến thể cấu hình</button>
      <button class="tab" :class="{active:activeTab==='Bảng màu sản phẩm'}" @click="activeTab='Bảng màu sản phẩm'">Bảng màu sản phẩm</button>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="main-layout">
      <div class="left-col">

        <!-- VARIANT TABLE -->
        <div v-if="activeTab==='Biến thể cấu hình'" class="card">
          <div class="card-header">
            <div class="card-title"><span class="bar blue"></span>Danh sách biến thể</div>
            <div class="card-tools">
              <button class="tool-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg></button>
              <button class="tool-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M6 12h12M9 18h6"/></svg></button>
            </div>
          </div>
          <table>
            <thead><tr><th>TÊN BIẾN THỂ</th><th>LOẠI THUỘC TÍNH</th><th>TRẠNG THÁI</th><th>THAO TÁC</th></tr></thead>
            <tbody>
              <tr v-if="pagedVariants.length===0"><td colspan="4" class="empty">Chưa có biến thể nào.</td></tr>
              <tr v-for="v in pagedVariants" :key="v.id">
                <td class="variant-name">{{ v.name }}</td>
                <td><span class="type-badge" :style="{background:typeStyle[v.type]?.bg,color:typeStyle[v.type]?.color}">{{ v.type }}</span></td>
                <td><span class="status-dot" :class="v.status==='Hoạt động'?'active':'draft'">● {{ v.status }}</span></td>
                <td>
                  <div class="actions">
                    <button class="act-btn" @click="openModal('editVariant',v)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button class="act-btn danger" @click="removeVariant(v.id)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="table-footer">
            <span class="page-info">Hiển thị {{ Math.min((variantPage-1)*6+1, variants.length) }}–{{ Math.min(variantPage*6, variants.length) }} / {{ variants.length }}</span>
            <div class="pagination">
              <button class="page-btn" :disabled="variantPage===1" @click="variantPage--"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
              <button v-for="p in variantPages" :key="p" class="page-btn" :class="{active:p===variantPage}" @click="variantPage=p">{{p}}</button>
              <button class="page-btn" :disabled="variantPage===variantPages" @click="variantPage++"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
            </div>
          </div>
        </div>

        <!-- COLOR TABLE (full width khi active) -->
        <div v-else class="card">
          <div class="card-header">
            <div class="card-title"><span class="bar purple"></span>Bảng màu sản phẩm</div>
            <button class="btn-new-sm purple-btn" @click="openModal('color')">+ Thêm màu</button>
          </div>
          <table>
            <thead><tr><th>MÀU</th><th>TÊN</th><th>SỐ BIẾN THỂ</th><th>KHO HÀNG</th><th>THAO TÁC</th></tr></thead>
            <tbody>
              <tr v-for="c in pagedColors" :key="c.id" @click="selectedColor=c" style="cursor:pointer">
                <td><div class="color-swatch-cell" :style="{background:c.hex}"></div></td>
                <td class="variant-name">{{ c.name }}</td>
                <td class="muted">{{ c.count }} biến thể</td>
                <td><span :class="c.stock==='Khả dụng'?'stock-ok':'stock-out'">{{ c.stock }}</span></td>
                <td>
                  <div class="actions">
                    <button class="act-btn" @click.stop="openModal('editColor',c)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button class="act-btn danger" @click.stop="removeColor(c.id)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="table-footer">
            <span class="page-info">Hiển thị {{ Math.min((colorPage-1)*6+1, colors.length) }}–{{ Math.min(colorPage*6, colors.length) }} / {{ colors.length }}</span>
            <div class="pagination">
              <button class="page-btn" :disabled="colorPage===1" @click="colorPage--"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
              <button v-for="p in colorPages" :key="p" class="page-btn" :class="{active:p===colorPage}" @click="colorPage=p">{{p}}</button>
              <button class="page-btn" :disabled="colorPage===colorPages" @click="colorPage++"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT SIDEBAR -->
      <div class="right-col">
        <div class="card side-card">
          <div class="side-header">
            <div class="card-title" style="font-size:14px"><span class="bar purple"></span>Bảng màu</div>
            <button class="btn-add-sm" @click="openModal('color')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;flex-shrink:0"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Thêm màu
            </button>
          </div>
          <div class="color-list">
            <div v-for="c in colors" :key="c.id" class="color-row-item" :class="{'color-selected':selectedColor?.id===c.id}" @click="selectedColor=c">
              <div class="color-dot" :style="{background:c.hex}"></div>
              <div class="color-info"><b>{{ c.name }}</b><span>{{ c.hex }}</span></div>
              <div class="color-row-actions">
                <span :class="c.stock==='Khả dụng'?'stock-ok':'stock-out'" style="font-size:11px">{{ c.stock==='Khả dụng'?'●':'○' }}</span>
                <button class="color-del-btn" @click.stop="removeColor(c.id)" title="Xóa màu">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card preview-card">
          <div class="preview-label">PREVIEW: VINABOOK PRO 2026</div>
          <div class="preview-img-wrap">
            <div class="preview-laptop" :style="{background:`linear-gradient(135deg,${selectedColor?.hex||'#1a1a1a'}cc,${selectedColor?.hex||'#1a1a1a'})`}">
              <div class="laptop-screen"></div>
              <div class="laptop-base"></div>
            </div>
          </div>
          <div class="preview-footer">
            <div><b>{{ selectedColor?.name||'Space Black' }} Edition</b><span>{{ selectedColor?.count||0 }} biến thể khả dụng</span></div>
            <span :class="selectedColor?.stock==='Khả dụng'?'chip-ok':'chip-out'">{{ selectedColor?.stock||'Khả dụng' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- BOTTOM -->
    <div class="bottom-grid">
      <div class="bottom-card"><div class="bottom-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div><div><h4>Xuất dữ liệu</h4><p>Tải xuống danh sách cấu hình (CSV)</p></div></div>
      <div class="bottom-card"><div class="bottom-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div><div><h4>Tự động hóa</h4><p>Gán ý biến thể theo xu hướng thị trường</p></div></div>
      <div class="bottom-card dark-bottom"><div class="bottom-icon white-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div><div><h4>Thư viện màu</h4><p>Quản lý và mở rộng bộ màu sắc toàn bộ thị tác</p></div></div>
    </div>

    <!-- ══════ MODAL ══════ -->
    <Teleport to="body">
      <transition name="fade">
        <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
          <transition name="slide-up">
            <div class="modal" v-if="showModal">
              <div class="modal-header">
                <div class="modal-header-left">
                  <div class="modal-icon" :class="isEditModal?'icon-edit':['group','editGroup'].includes(modalType)?'icon-group':['attr','editAttr'].includes(modalType)?'icon-attr':'icon-add'">
                    <svg v-if="['group','editGroup'].includes(modalType)" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <svg v-else-if="['attr','editAttr'].includes(modalType)" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    <svg v-else-if="['color','editColor'].includes(modalType)" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="13.5" cy="6.5" r="2.5"/><circle cx="6.5" cy="12" r="2.5"/><circle cx="13.5" cy="17.5" r="2.5"/></svg>
                    <svg v-else viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  </div>
                  <div>
                    <h3 class="modal-title">{{ modalTitle }}</h3>
                    <p class="modal-subtitle">{{ ['group','editGroup'].includes(modalType)?'Nhóm các thuộc tính liên quan vào một danh mục':['attr','editAttr'].includes(modalType)?'Loại thuộc tính thuộc về một nhóm cụ thể':['color','editColor'].includes(modalType)?'Màu sắc hiển thị cho sản phẩm ngoài cửa hàng':'Thông số kỹ thuật áp dụng cho sản phẩm' }}</p>
                  </div>
                </div>
                <button class="modal-close" @click="closeModal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
              </div>

              <div class="modal-body">

                <!-- VARIANT -->
                <template v-if="['variant','editVariant'].includes(modalType)">
                  <div class="form-group"><label>TÊN BIẾN THỂ <span class="req">*</span></label><input v-model="variantForm.name" placeholder="VD: 16GB Unified Memory"/></div>
                  <div class="form-row">
                    <div class="form-group"><label>LOẠI THUỘC TÍNH <span class="req">*</span></label><select v-model="variantForm.type"><option>RAM</option><option>Ổ CỨNG</option><option>CPU</option></select></div>
                    <div class="form-group">
                      <label>TRẠNG THÁI</label>
                      <div class="toggle-group">
                        <button class="toggle-btn" :class="{'tg-green':variantForm.status==='Hoạt động'}" @click="variantForm.status='Hoạt động'"><span class="tdot"></span>Hoạt động</button>
                        <button class="toggle-btn" :class="{'tg-yellow':variantForm.status==='Nháp'}" @click="variantForm.status='Nháp'"><span class="tdot"></span>Nháp</button>
                      </div>
                    </div>
                  </div>
                </template>

                <!-- COLOR -->
                <template v-else-if="['color','editColor'].includes(modalType)">
                  <div class="color-preview-big" :style="{background:colorForm.hex}"><span class="color-preview-name">{{ colorForm.name||'Tên màu' }}</span></div>
                  <div class="form-row">
                    <div class="form-group"><label>TÊN MÀU <span class="req">*</span></label><input v-model="colorForm.name" placeholder="VD: Space Black"/></div>
                    <div class="form-group">
                      <label>MÃ MÀU</label>
                      <div class="color-input-wrap">
                        <div class="color-preview-sm" :style="{background:colorForm.hex}"></div>
                        <input v-model="colorForm.hex" type="text" placeholder="#000000"/>
                        <input type="color" v-model="colorForm.hex" class="native-color"/>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>TRẠNG THÁI KHO</label>
                    <div class="toggle-group">
                      <button class="toggle-btn" :class="{'tg-green':colorForm.stock==='Khả dụng'}" @click="colorForm.stock='Khả dụng'"><span class="tdot"></span>Khả dụng</button>
                      <button class="toggle-btn" :class="{'tg-red':colorForm.stock==='Hết hàng'}" @click="colorForm.stock='Hết hàng'"><span class="tdot"></span>Hết hàng</button>
                    </div>
                  </div>
                </template>

                <!-- GROUP -->
                <template v-else-if="['group','editGroup'].includes(modalType)">
                  <div class="preview-row green-preview">
                    <div class="prev-icon green-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
                    <div><p class="prev-name">{{ groupForm.name||'Tên nhóm thuộc tính' }}</p><p class="prev-desc">{{ groupForm.desc||'Mô tả nhóm...' }}</p></div>
                  </div>
                  <div class="form-group"><label>TÊN NHÓM THUỘC TÍNH <span class="req">*</span></label><input v-model="groupForm.name" placeholder="VD: Cấu hình Laptop"/></div>
                  <div class="form-group"><label>MÔ TẢ</label><textarea v-model="groupForm.desc" rows="3" placeholder="Mô tả về nhóm thuộc tính này..."></textarea></div>
                </template>

                <!-- ATTR -->
                <template v-else-if="['attr','editAttr'].includes(modalType)">
                  <div class="preview-row purple-preview">
                    <div class="prev-icon purple-icon-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                    <div><p class="prev-name">{{ attrForm.name||'Tên loại thuộc tính' }}</p><p class="prev-desc">Thuộc nhóm: <strong>{{ attrForm.group||'—' }}</strong></p></div>
                  </div>
                  <div class="form-group"><label>TÊN LOẠI THUỘC TÍNH <span class="req">*</span></label><input v-model="attrForm.name" placeholder="VD: RAM 16GB"/></div>
                  <div class="form-group"><label>THUỘC NHÓM <span class="req">*</span></label><select v-model="attrForm.group"><option v-for="g in groups" :key="g.id" :value="g.name">{{ g.name }}</option></select></div>
                  <div class="form-group">
                    <label>TRẠNG THÁI</label>
                    <div class="toggle-group">
                      <button class="toggle-btn" :class="{'tg-green':attrForm.status==='Hoạt động'}" @click="attrForm.status='Hoạt động'"><span class="tdot"></span>Hoạt động</button>
                      <button class="toggle-btn" :class="{'tg-yellow':attrForm.status==='Nháp'}" @click="attrForm.status='Nháp'"><span class="tdot"></span>Nháp</button>
                    </div>
                  </div>
                </template>

                <p v-if="formError" class="form-error">⚠ {{ formError }}</p>
              </div>

              <div class="modal-footer">
                <button class="btn-cancel" @click="closeModal">Hủy</button>
                <button class="btn-submit" @click="handleSubmit">
                  <svg v-if="isEditModal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  {{ modalBtnLabel }}
                </button>
              </div>
            </div>
          </transition>
        </div>
      </transition>
    </Teleport>

  </div>
</template>

<style scoped>
* { box-sizing:border-box; }
.page { background:#f5f7fb; min-height:100vh; font-family:'Be Vietnam Pro',sans-serif; padding-bottom:40px; }

/* TOPBAR */
.topbar { display:flex; align-items:center; justify-content:space-between; padding:11px 32px; background:white; border-bottom:1px solid #f1f5f9; }
.search-box { position:relative; width:220px; }
.search-box svg { position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:#94a3b8; pointer-events:none; }
.search-box input { width:100%; padding:7px 12px 7px 30px; border-radius:8px; border:1px solid #e2e8f0; font-size:12px; color:#0f172a; outline:none; background:#f8fafc; font-family:inherit; }
.topbar-right { display:flex; align-items:center; gap:6px; }
.nav-link { font-size:13px; font-weight:500; color:#475569; padding:6px 10px; border-radius:6px; cursor:pointer; }
.nav-link:hover { color:#2563eb; background:#f0f6ff; }
.icon-btn { position:relative; background:none; border:none; cursor:pointer; padding:7px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#64748b; }
.icon-btn svg { width:16px; height:16px; }
.icon-btn:hover { background:#f1f5f9; }
.notif-dot { position:absolute; top:6px; right:6px; width:7px; height:7px; background:#ef4444; border-radius:50%; border:1.5px solid #fff; }
.av { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#4f46e5); color:white; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; }

.breadcrumb { padding:14px 32px 0; font-size:12px; color:#94a3b8; display:flex; gap:6px; }
.sep { color:#cbd5e1; }
.active-crumb { color:#2563eb; font-weight:500; }

/* PAGE HEADER */
.page-header { display:flex; justify-content:space-between; align-items:flex-start; padding:12px 32px 16px; gap:16px; }
.page-header h1 { font-size:26px; font-weight:800; color:#0f172a; margin:0 0 5px; letter-spacing:-0.02em; }
.page-header p { font-size:13px; color:#64748b; margin:0; max-width:420px; line-height:1.5; }
.action-row { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.btn-secondary { display:flex; align-items:center; gap:6px; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:12.5px; font-weight:600; color:#475569; cursor:pointer; white-space:nowrap; transition:all .15s; font-family:inherit; }
.btn-secondary svg { width:14px; height:14px; flex-shrink:0; }
.btn-secondary:hover { border-color:#16a34a; color:#15803d; background:#f0fdf4; }
.btn-sec-purple:hover { border-color:#7c3aed; color:#6d28d9; background:#faf5ff; }
.btn-new { display:flex; align-items:center; gap:7px; padding:10px 18px; border-radius:10px; border:none; background:linear-gradient(135deg,#2563eb,#4f46e5); color:white; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; font-family:inherit; box-shadow:0 4px 12px rgba(37,99,235,.3); transition:transform .15s,opacity .15s; }
.btn-new svg { width:14px; height:14px; }
.btn-new:hover { opacity:.9; transform:translateY(-1px); }

/* TOP TABLES */
.top-tables { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:0 32px 16px; }
.top-card { padding:18px 20px; }

/* TABS */
.tabs { display:flex; gap:4px; padding:0 32px 14px; }
.tab { padding:9px 18px; border-radius:9px; border:1px solid #e2e8f0; background:white; font-size:13px; font-weight:500; color:#64748b; cursor:pointer; transition:all .2s; font-family:inherit; }
.tab:hover { color:#334155; border-color:#cbd5e1; }
.tab.active { background:#2563eb; border-color:#2563eb; color:white; font-weight:600; }

/* MAIN LAYOUT */
.main-layout { display:grid; grid-template-columns:1fr 280px; gap:16px; padding:0 32px; }

/* CARD */
.card { background:white; border-radius:14px; border:1px solid #f1f5f9; padding:20px; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
.card-title { display:flex; align-items:center; gap:8px; font-size:15px; font-weight:700; color:#0f172a; }
.bar { width:4px; height:16px; border-radius:2px; flex-shrink:0; }
.bar.blue   { background:#2563eb; }
.bar.purple { background:#7c3aed; }
.bar.purple2 { background:#9333ea; }
.bar.green  { background:#16a34a; }
.card-tools { display:flex; gap:6px; }
.tool-btn { width:30px; height:30px; border-radius:7px; border:1px solid #e2e8f0; background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; }
.tool-btn svg { width:13px; height:13px; }
.tool-btn:hover { background:#f1f5f9; }

.btn-new-sm { display:flex; align-items:center; gap:5px; padding:6px 12px; border-radius:8px; border:1.5px solid; font-size:12px; font-weight:600; cursor:pointer; font-family:inherit; transition:all .15s; }
.btn-new-sm svg { width:11px; height:11px; }
.green-btn { border-color:#16a34a; background:#f0fdf4; color:#15803d; }
.green-btn:hover { background:#dcfce7; }
.purple-btn { border-color:#7c3aed; background:#faf5ff; color:#6d28d9; }
.purple-btn:hover { background:#ede9fe; }

/* TABLE */
table { width:100%; border-collapse:collapse; }
thead th { padding:9px 12px; font-size:10px; font-weight:700; color:#94a3b8; text-align:left; letter-spacing:.07em; border-bottom:1px solid #f1f5f9; }
tbody tr { border-bottom:1px solid #f8fafc; transition:background .15s; }
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:#fafbff; }
tbody td { padding:12px 12px; font-size:13px; vertical-align:middle; }
.empty { text-align:center; color:#94a3b8; padding:32px !important; }
.variant-name { font-weight:600; color:#0f172a; font-size:13px; }
.muted { color:#64748b; }
.type-badge { display:inline-block; font-size:10px; font-weight:700; padding:4px 9px; border-radius:6px; }
.status-dot { font-size:12px; font-weight:600; }
.status-dot.active { color:#16a34a; }
.status-dot.draft  { color:#d97706; }
.color-swatch-cell { width:28px; height:28px; border-radius:50%; border:2px solid rgba(0,0,0,.08); }
.stock-ok  { color:#16a34a; font-size:12px; font-weight:600; }
.stock-out { color:#dc2626; font-size:12px; font-weight:600; }
.count-badge { background:#f0fdf4; color:#15803d; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; border:1px solid #bbf7d0; }
.group-tag  { background:#ede9fe; color:#6d28d9; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; }

.name-cell { display:flex; align-items:center; gap:9px; }
.cell-icon { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cell-icon svg { width:13px; height:13px; }
.green-icon  { background:#f0fdf4; } .green-icon svg  { stroke:#16a34a; }
.purple-icon { background:#faf5ff; } .purple-icon svg { stroke:#7c3aed; }

.actions { display:flex; gap:5px; }
.act-btn { width:28px; height:28px; border-radius:7px; border:1px solid #e2e8f0; background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .15s; }
.act-btn svg { width:12px; height:12px; }
.act-btn:hover { background:#f1f5f9; color:#2563eb; border-color:#cbd5e1; }
.act-btn.danger:hover { background:#fee2e2; border-color:#fecaca; color:#ef4444; }

/* TABLE FOOTER */
.table-footer { display:flex; align-items:center; justify-content:space-between; padding:12px 12px 2px; border-top:1px solid #f1f5f9; margin-top:4px; }
.page-info { font-size:11.5px; color:#94a3b8; }
.pagination { display:flex; gap:4px; align-items:center; }
.page-btn { width:28px; height:28px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .12s; font-family:inherit; }
.page-btn svg { width:12px; height:12px; }
.page-btn:hover:not(:disabled) { border-color:#2563eb; color:#2563eb; }
.page-btn.active { background:#2563eb; border-color:#2563eb; color:#fff; }
.page-btn:disabled { opacity:.3; cursor:default; }

/* SIDEBAR */
.side-card { padding:16px; }
.side-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
.btn-add-sm { display:flex; align-items:center; gap:4px; padding:5px 9px; border-radius:7px; border:1px solid #e2e8f0; background:white; font-size:11px; font-weight:600; color:#475569; cursor:pointer; font-family:inherit; transition:all .15s; }
.btn-add-sm:hover { border-color:#2563eb; color:#2563eb; }
.color-list { display:flex; flex-direction:column; gap:4px; }
.color-row-item { display:flex; align-items:center; gap:9px; padding:7px 9px; border-radius:9px; cursor:pointer; transition:all .15s; border:1px solid transparent; }
.color-row-item:hover { background:#f8fafc; }
.color-row-item.color-selected { background:#f0f6ff; border-color:#bfdbfe; }
.color-dot { width:26px; height:26px; border-radius:50%; flex-shrink:0; border:2px solid rgba(0,0,0,.08); }
.color-info { flex:1; min-width:0; }
.color-info b { display:block; font-size:12px; font-weight:600; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.color-info span { font-size:10px; color:#94a3b8; }
.color-row-actions { display:flex; align-items:center; gap:4px; flex-shrink:0; }
.color-del-btn { width:22px; height:22px; border-radius:5px; border:1px solid transparent; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#cbd5e1; transition:all .15s; opacity:0; }
.color-del-btn svg { width:10px; height:10px; }
.color-row-item:hover .color-del-btn { opacity:1; }
.color-del-btn:hover { background:#fee2e2; border-color:#fecaca; color:#ef4444; }

/* PREVIEW */
.preview-card { padding:14px; }
.preview-label { font-size:9px; font-weight:700; color:#94a3b8; letter-spacing:.1em; margin-bottom:12px; }
.preview-img-wrap { display:flex; justify-content:center; margin-bottom:12px; }
.preview-laptop { width:140px; height:90px; border-radius:6px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; transition:background .4s; }
.laptop-screen { width:110px; height:65px; border-radius:4px; background:rgba(0,0,0,.3); border:2px solid rgba(255,255,255,.15); }
.laptop-base { width:120px; height:6px; border-radius:0 0 4px 4px; background:rgba(0,0,0,.2); }
.preview-footer { display:flex; align-items:center; justify-content:space-between; }
.preview-footer b { display:block; font-size:12px; font-weight:700; color:#0f172a; }
.preview-footer span { font-size:10px; color:#94a3b8; display:block; margin-top:2px; }
.chip-ok  { display:inline-block; font-size:10px; font-weight:700; padding:3px 8px; border-radius:20px; background:#dcfce7; color:#16a34a; white-space:nowrap; }
.chip-out { display:inline-block; font-size:10px; font-weight:700; padding:3px 8px; border-radius:20px; background:#fee2e2; color:#dc2626; white-space:nowrap; }

/* BOTTOM */
.bottom-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; padding:16px 32px 0; }
.bottom-card { background:white; border-radius:12px; border:1px solid #f1f5f9; padding:18px; display:flex; gap:14px; align-items:flex-start; }
.bottom-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.bottom-icon svg { width:18px; height:18px; }
.bottom-icon.blue   { background:#dbeafe; color:#2563eb; }
.bottom-icon.purple { background:#ede9fe; color:#7c3aed; }
.bottom-card h4 { font-size:13px; font-weight:700; color:#0f172a; margin:0 0 4px; }
.bottom-card p  { font-size:12px; color:#64748b; margin:0; line-height:1.4; }
.dark-bottom { background:linear-gradient(135deg,#1e40af,#4f46e5); border-color:transparent; }
.dark-bottom h4 { color:white; }
.dark-bottom p  { color:rgba(255,255,255,.7); }
.white-icon { background:rgba(255,255,255,.15); color:white; }

/* ══ MODAL ══ */
.modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:1000; padding:20px; }
.modal { background:white; border-radius:18px; width:100%; max-width:500px; max-height:90vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,.18); display:flex; flex-direction:column; }
.modal-header { display:flex; justify-content:space-between; align-items:center; padding:18px 22px 14px; border-bottom:1px solid #f1f5f9; gap:12px; }
.modal-header-left { display:flex; align-items:center; gap:12px; }
.modal-icon { width:40px; height:40px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.modal-icon svg { width:17px; height:17px; }
.icon-add   { background:linear-gradient(135deg,#2563eb,#4f46e5); }
.icon-edit  { background:linear-gradient(135deg,#f59e0b,#f97316); }
.icon-group { background:linear-gradient(135deg,#16a34a,#059669); }
.icon-attr  { background:linear-gradient(135deg,#7c3aed,#6d28d9); }
.modal-title { font-size:15px; font-weight:700; color:#0f172a; margin:0; }
.modal-subtitle { font-size:12px; color:#94a3b8; margin-top:2px; }
.modal-close { width:30px; height:30px; border:1px solid #e2e8f0; border-radius:8px; background:#f8fafc; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all .15s; }
.modal-close svg { width:14px; height:14px; stroke:#64748b; }
.modal-close:hover { background:#fee2e2; border-color:#fecaca; }
.modal-close:hover svg { stroke:#ef4444; }
.modal-body { padding:18px 22px; display:flex; flex-direction:column; gap:14px; }
.modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:14px 22px 18px; border-top:1px solid #f1f5f9; }

/* Forms */
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-group label { font-size:10px; font-weight:700; color:#94a3b8; letter-spacing:.08em; }
.req { color:#ef4444; }
.form-group input,.form-group select,.form-group textarea { padding:9px 11px; border-radius:8px; border:1.5px solid #e2e8f0; font-size:13px; color:#0f172a; outline:none; background:#f8fafc; transition:border-color .2s; font-family:inherit; }
.form-group input:focus,.form-group select:focus,.form-group textarea:focus { border-color:#2563eb; background:white; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
.form-group textarea { resize:vertical; min-height:72px; }
.color-input-wrap { display:flex; align-items:center; gap:8px; padding:4px 11px 4px 4px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; }
.color-preview-sm { width:28px; height:28px; border-radius:6px; flex-shrink:0; border:1px solid rgba(0,0,0,.08); }
.color-input-wrap input[type="text"] { flex:1; border:none; background:transparent; outline:none; font-size:13px; color:#0f172a; padding:0; }
.native-color { width:22px; height:22px; border:none; padding:0; cursor:pointer; background:none; border-radius:4px; }
.color-preview-big { border-radius:12px; height:68px; display:flex; align-items:flex-end; padding:10px 14px; transition:background .3s; }
.color-preview-name { font-size:13px; font-weight:700; color:rgba(255,255,255,.9); text-shadow:0 1px 4px rgba(0,0,0,.3); }

.preview-row { display:flex; align-items:center; gap:12px; border-radius:12px; padding:13px; border:1px solid #e8ecf4; }
.green-preview { background:#f0fdf4; }
.purple-preview { background:#faf5ff; }
.prev-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.prev-icon svg { width:19px; height:19px; }
.green-icon  { background:#dcfce7; } .green-icon svg  { stroke:#16a34a; }
.purple-icon-prev { background:#ede9fe; } .purple-icon-prev svg { stroke:#7c3aed; }
.prev-name { font-size:14px; font-weight:700; color:#0f172a; margin-bottom:2px; }
.prev-desc { font-size:12px; color:#64748b; }

.toggle-group { display:flex; gap:6px; }
.toggle-btn { flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:8px 10px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12.5px; font-weight:500; color:#64748b; cursor:pointer; font-family:inherit; transition:all .15s; }
.tdot { width:7px; height:7px; border-radius:50%; background:#cbd5e1; flex-shrink:0; transition:background .15s; }
.tg-green  { border-color:#16a34a; background:#f0fdf4; color:#15803d; } .tg-green .tdot  { background:#16a34a; }
.tg-yellow { border-color:#d97706; background:#fffbeb; color:#b45309; } .tg-yellow .tdot { background:#d97706; }
.tg-red    { border-color:#dc2626; background:#fef2f2; color:#b91c1c; } .tg-red .tdot    { background:#dc2626; }

.form-error { font-size:12px; color:#ef4444; background:#fef2f2; border:1px solid #fecaca; padding:8px 12px; border-radius:8px; }
.btn-cancel { padding:9px 18px; border-radius:8px; border:1.5px solid #e2e8f0; background:white; font-size:13px; font-weight:600; color:#475569; cursor:pointer; font-family:inherit; }
.btn-cancel:hover { background:#f8fafc; }
.btn-submit { display:flex; align-items:center; gap:7px; padding:9px 20px; border-radius:8px; border:none; background:linear-gradient(135deg,#2563eb,#4f46e5); color:white; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:opacity .2s; }
.btn-submit svg { width:14px; height:14px; }
.btn-submit:hover { opacity:.9; }

/* Transitions */
.fade-enter-active,.fade-leave-active { transition:opacity .22s ease; }
.fade-enter-from,.fade-leave-to { opacity:0; }
.slide-up-enter-active { transition:all .28s cubic-bezier(.34,1.56,.64,1); }
.slide-up-leave-active { transition:all .18s ease; }
.slide-up-enter-from { opacity:0; transform:translateY(24px) scale(.97); }
.slide-up-leave-to { opacity:0; transform:translateY(8px) scale(.98); }

@media (max-width:1100px) {
  .top-tables { grid-template-columns:1fr; }
}
@media (max-width:900px) {
  .main-layout { grid-template-columns:1fr; padding:0 16px; }
  .top-tables { padding:0 16px 14px; }
  .bottom-grid { grid-template-columns:1fr 1fr; padding:14px 16px 0; }
  .page-header { flex-direction:column; gap:12px; padding:12px 16px 14px; }
  .tabs,.breadcrumb { padding-left:16px; padding-right:16px; }
  .action-row { flex-wrap:wrap; }
}
@media (max-width:640px) {
  .bottom-grid { grid-template-columns:1fr; }
  .form-row { grid-template-columns:1fr; }
}
</style>