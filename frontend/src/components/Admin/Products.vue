<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
})

/* ═══════════════════════════════════════
   DANH SÁCH SẢN PHẨM
═══════════════════════════════════════ */
const searchQuery = ref('')
const selectedStatus = ref('')
const selectedCategory = ref('')

const currentPage = ref(1)
const PER_PAGE = 10

const products = ref([])
const categories = ref([])
const brands = ref([])
const colors = ref([])
const readingExtraImages = ref(false)
const variantLoading = ref(false)
const filteredProducts = computed(() =>
  products.value.filter(p => {
    const s = searchQuery.value.toLowerCase()

    return (!s || p.name.toLowerCase().includes(s) || p.sku.toLowerCase().includes(s))
      && (!selectedStatus.value || p.status === selectedStatus.value)
      && (!selectedCategory.value || String(p.categoryId) === String(selectedCategory.value))
  })
)

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredProducts.value.length / PER_PAGE))
)

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * PER_PAGE
  return filteredProducts.value.slice(start, start + PER_PAGE)
})

const pageItems = computed(() => {
  const total = totalPages.value
  const current = currentPage.value

  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }

  if (current <= 3) {
    return [1, 2, 3, '...', total - 2, total - 1, total]
  }

  if (current >= total - 2) {
    return [1, 2, 3, '...', total - 2, total - 1, total]
  }

  return [1, '...', current - 1, current, current + 1, '...', total]
})

const goToPage = (page) => {
  if (page < 1) {
    currentPage.value = 1
    return
  }
  if (page > totalPages.value) {
    currentPage.value = totalPages.value
    return
  }
  currentPage.value = page
}

watch([searchQuery, selectedStatus, selectedCategory], () => {
  currentPage.value = 1
})

const getErrorMessage = (error, fallback) => {
  if (error?.response?.data?.message) return error.response.data.message

  const errors = error?.response?.data?.errors
  if (errors && typeof errors === 'object') {
    const firstKey = Object.keys(errors)[0]
    if (firstKey && Array.isArray(errors[firstKey]) && errors[firstKey][0]) {
      return errors[firstKey][0]
    }
  }

  return fallback
}

const fetchProducts = async () => {
  try {
    const res = await api.get('/sanpham')

    products.value = res.data.map(p => {
      const variantCount = Array.isArray(p.bien_thes) ? p.bien_thes.length : 0

      return {
        id: p.id_sanpham,
        name: p.tenSP,
        sku: p.SKU || '',
        category: p.danh_muc?.ten_danhmuc || 'Chưa có danh mục',
        categoryId: p.id_danhmuc ?? '',
        brand: p.thuong_hieu?.ten_thuonghieu || 'Chưa có thương hiệu',
        totalVariants: variantCount,
        status: String(p.trangthai) === '1' ? 'Đang bán' : 'Nháp',
        img: p.hinhanh
          ? `http://127.0.0.1:8000/storage/${p.hinhanh}`
          : 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200',
      }
    })
  } catch (error) {
    formError.value = getErrorMessage(error, 'Không tải được danh sách sản phẩm.')
  }
}

const fetchCategories = async () => {
  try {
    const res = await api.get('/danhmuc')

    categories.value = Array.isArray(res.data)
      ? res.data
      : Array.isArray(res.data?.data)
        ? res.data.data
        : []
  } catch (error) {
    console.error(error)
  }
}

const fetchBrands = async () => {
  try {
    const res = await api.get('/thuonghieu')

    brands.value = Array.isArray(res.data)
      ? res.data
      : Array.isArray(res.data?.data)
        ? res.data.data
        : []
  } catch (error) {
    console.error(error)
  }
}

const fetchColors = async () => {
  try {
    const res = await api.get('/colors')

    colors.value = Array.isArray(res.data)
      ? res.data
      : Array.isArray(res.data?.data)
        ? res.data.data
        : []

    buildAttributeGroups()
  } catch (error) {
    console.error(error)
  }
}

const removeProduct = async (id) => {
  try {
    await api.delete(`/sanpham/${id}`)
    await fetchProducts()
  } catch (error) {
    formError.value = getErrorMessage(error, 'Không xóa được sản phẩm.')
  }
}
const resetForm = () => {
  form.value = defaultForm()
  imgPreview.value = ''
  extraImagePreviews.value = []
  formError.value = ''

  vsPhase.value = 1
  selectedOptions.value = {}
  generatedRows.value = []
  editVariantHeaders.value = []
  bulkPrice.value = ''
  bulkStock.value = ''

  selectedGroupId.value = attributeGroups.value.length > 0
    ? attributeGroups.value[0].id
    : null

  if (fileInputRef.value) fileInputRef.value.value = ''
  if (extraFileInputRef.value) extraFileInputRef.value.value = ''
}
const parseVariantName = (name) => {
  if (!name) return {}
  const parts = String(name).split(' - ')
  const obj = {}

  parts.forEach((part, index) => {
    obj[`attr_${index}`] = part
  })

  return obj
}
/* ═══════════════════════════════════════
   NHÓM THUỘC TÍNH & LOẠI THUỘC TÍNH
═══════════════════════════════════════ */
const baseAttributeGroups = ref([])
const attributeGroups = ref([])

const groupIconMap = {
  'Cấu hình': '💻',
  'Màn hình': '🖥️',
  'Pin & Sạc': '🔋',
  'Kết nối': '📡',
  'Phụ kiện': '🖱️',
  'Màu sắc': '🎨',
}

const colorPool = ['blue', 'green', 'amber', 'pink', 'purple', 'teal']

const getGroupIcon = (name) => {
  return groupIconMap[name] || '📦'
}

const getTypeColor = (name) => {
  if (!name) return 'blue'
  const index =
    String(name)
      .split('')
      .reduce((sum, ch) => sum + ch.charCodeAt(0), 0) % colorPool.length

  return colorPool[index]
}

const buildAttributeGroups = () => {
  const colorGroup =
    colors.value.length > 0
      ? {
          id: 'color-group',
          name: 'Màu sắc',
          icon: getGroupIcon('Màu sắc'),
          attrTypes: [
            {
              id: 'color-type',
              label: 'Màu sắc',
              color: 'pink',
              options: colors.value.map((c) => ({
                label: c.name,
                value: c.name,
                hex: c.hex_code,
              })),
            },
          ],
        }
      : null

  attributeGroups.value = colorGroup
    ? [...baseAttributeGroups.value, colorGroup]
    : [...baseAttributeGroups.value]

  if (!selectedGroupId.value && attributeGroups.value.length > 0) {
    selectedGroupId.value = attributeGroups.value[0].id
  }
}

const normalizeAttributeGroups = (payload) => {
  const nhoms = Array.isArray(payload) ? payload : []

  baseAttributeGroups.value = nhoms.map((group) => {
    const thuocTinhs = Array.isArray(group.thuoc_tinhs)
      ? group.thuoc_tinhs
      : Array.isArray(group.thuocTinhs)
        ? group.thuocTinhs
        : []

    return {
      id: group.id_nhom,
      name: group.ten_nhom,
      icon: getGroupIcon(group.ten_nhom),
      attrTypes: thuocTinhs.map((attr) => {
        const giaTris = Array.isArray(attr.giatri_thuoc_tinhs)
          ? attr.giatri_thuoc_tinhs
          : Array.isArray(attr.giatriThuocTinhs)
            ? attr.giatriThuocTinhs
            : []

        return {
          id: attr.id_thuoctinh,
          label: attr.ten_thuoctinh,
          color: getTypeColor(attr.ten_thuoctinh),
          options: giaTris.map((item) => item.giatri),
        }
      }),
    }
  })

  buildAttributeGroups()
}

const fetchAttributeGroups = async () => {
  variantLoading.value = true

  try {
    const res = await api.get('/thuoctinh-all')
    normalizeAttributeGroups(res.data)

    if (!selectedGroupId.value && attributeGroups.value.length > 0) {
      selectedGroupId.value = attributeGroups.value[0].id
    }
  } catch (error) {
    formError.value = getErrorMessage(error, 'Không tải được dữ liệu biến thể.')
  } finally {
    variantLoading.value = false
  }
}

/* ═══════════════════════════════════════
   MODAL & FORM
═══════════════════════════════════════ */
const showModal = ref(false)
const formError = ref('')
const isEditMode = ref(false)
const editingProductId = ref(null)
const imgPreview = ref('')
const fileInputRef = ref(null)
const extraFileInputRef = ref(null)
const extraImagePreviews = ref([])

const defaultForm = () => ({
  name: '',
  category: '',
  brand: '',
  status: 'Đang bán',
  img: '',
  images: [],
  weight: '',
})

const form = ref(defaultForm())

const onFileChange = e => {
  const file = e.target.files[0]
  if (!file) return

  const r = new FileReader()
  r.onload = ev => {
    imgPreview.value = ev.target.result
    form.value.img = ev.target.result
  }
  r.readAsDataURL(file)
}

const onExtraFilesChange = async (e) => {
  const files = Array.from(e.target.files || [])
  if (!files.length) return

  readingExtraImages.value = true

  try {
    const base64Images = await Promise.all(
      files.map(file => {
        return new Promise((resolve, reject) => {
          const reader = new FileReader()

          reader.onload = (ev) => resolve(ev.target.result)
          reader.onerror = () => reject(new Error('Không đọc được file ảnh'))

          reader.readAsDataURL(file)
        })
      })
    )

    form.value.images = [...form.value.images, ...files]
    extraImagePreviews.value = [...extraImagePreviews.value, ...base64Images]
  } catch (error) {
    console.error(error)
    alert('Không đọc được một hoặc nhiều ảnh phụ')
  } finally {
    readingExtraImages.value = false

    if (extraFileInputRef.value) {
      extraFileInputRef.value.value = ''
    }
  }
}

const triggerFileInput = () => fileInputRef.value?.click()
const triggerExtraFileInput = () => extraFileInputRef.value?.click()

const removeImg = () => {
  imgPreview.value = ''
  form.value.img = ''
  if (fileInputRef.value) fileInputRef.value.value = ''
}

const removeExtraImage = index => {
  extraImagePreviews.value.splice(index, 1)
  form.value.images.splice(index, 1)

  if (form.value.images.length === 0 && extraFileInputRef.value) {
    extraFileInputRef.value.value = ''
  }
}

/* ═══════════════════════════════════════
   VARIANT STATE — FLAT TABLE + COMBO FLOW
═══════════════════════════════════════ */
const vsPhase = ref(1)
const selectedGroupId = ref(null)
const selectedOptions = ref({})
const generatedRows = ref([])
const editVariantHeaders = ref([])
const bulkPrice = ref('')
const bulkStock = ref('')

const selectedGroup = computed(() =>
  attributeGroups.value.find(g => g.id === selectedGroupId.value) || null
)

const allAttrTypes = computed(() => {
  return attributeGroups.value.flatMap(group =>
    (group.attrTypes || []).map(type => ({
      ...type,
      groupId: group.id,
      groupName: group.name,
    }))
  )
})

const activeAttrTypes = computed(() => {
  return allAttrTypes.value.filter(
    t => selectedOptions.value[t.id]?.size > 0
  )
})
const tableHeaders = computed(() => {
  if (!isEditMode.value) return activeAttrTypes.value

  return editVariantHeaders.value.map((label, index) => ({
    id: `attr_${index}`,
    label,
    color: colorPool[index % colorPool.length],
  }))
})
const totalSelected = computed(() =>
  Object.values(selectedOptions.value).reduce((s, set) => s + (set?.size ?? 0), 0)
)

const comboCount = computed(() => {
  if (activeAttrTypes.value.length === 0) return 0
  return activeAttrTypes.value.reduce(
    (prod, t) => prod * (selectedOptions.value[t.id]?.size ?? 0),
    1
  )
})

const selectGroup = groupId => {
  if (selectedGroupId.value === groupId) return
  selectedGroupId.value = groupId
}

const displayAttrTypes = computed(() => {
  if (!selectedGroup.value) return []
  return selectedGroup.value.attrTypes || []
})

const getOptionValue = (opt) => typeof opt === 'object' ? opt.value : opt
const getOptionLabel = (opt) => typeof opt === 'object' ? opt.label : opt
const getOptionHex = (opt) => typeof opt === 'object' ? opt.hex : null

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
const openEditModal = async (id) => {
  try {
    formError.value = ''
    resetForm()

    const res = await api.get(`/sanpham/${id}`)
    const product = res.data

    isEditMode.value = true
    editingProductId.value = id

    mapProductToForm(product)
    showModal.value = true
  } catch (error) {
    console.error(error)
    alert(getErrorMessage(error, 'Không tải được thông tin sản phẩm để sửa.'))
  }
}
const mapProductToForm = (product) => {
  form.value = {
    name: product?.tenSP || '',
    category: product?.id_danhmuc ? String(product.id_danhmuc) : '',
    brand: product?.id_thuonghieu ? String(product.id_thuonghieu) : '',
    status: String(product?.trangthai) === '1' ? 'Đang bán' : 'Nháp',
    img: '',
    images: [],
    weight: product?.khoiluong ?? '',
  }

  imgPreview.value = product?.hinhanh
    ? `http://127.0.0.1:8000/storage/${product.hinhanh}`
    : ''

  extraImagePreviews.value = Array.isArray(product?.hinh_anhs)
    ? product.hinh_anhs.map(img => `http://127.0.0.1:8000/storage/${img.duongdan}`)
    : []

  const bienThes = Array.isArray(product?.bien_thes) ? product.bien_thes : []

  generatedRows.value = bienThes.map((row, i) => {
    const parts = String(row.ten_bienthe || '').split(' - ').filter(Boolean)

    const attrs = {}
    parts.forEach((part, index) => {
      attrs[`attr_${index}`] = part
    })

    return {
      id: row.id_bienthe ?? Date.now() + i,
      attrs,
      price: row.gia ?? '',
      stock: row.soluong ?? 0,
      ten_bienthe: row.ten_bienthe ?? '',
      isExisting: true,
    }
  })

  const maxParts = bienThes.reduce((max, row) => {
    const count = String(row.ten_bienthe || '').split(' - ').filter(Boolean).length
    return Math.max(max, count)
  }, 0)

  editVariantHeaders.value = Array.from({ length: maxParts }, (_, i) => `Thuộc tính ${i + 1}`)

  selectedOptions.value = {}
  vsPhase.value = 2
}
const openModal = () => {
  form.value = defaultForm()
  imgPreview.value = ''
  extraImagePreviews.value = []
  formError.value = ''

  vsPhase.value = 1
  selectedOptions.value = {}
  generatedRows.value = []
  bulkPrice.value = ''
  bulkStock.value = ''
  isEditMode.value = false
  editingProductId.value = null
  resetForm()
  showModal.value = true
  selectedGroupId.value = null

  if (attributeGroups.value.length > 0) {
    selectedGroupId.value = attributeGroups.value[0].id
  }

  if (fileInputRef.value) fileInputRef.value.value = ''
  if (extraFileInputRef.value) extraFileInputRef.value.value = ''

  showModal.value = true
}

const closeModal = () => { showModal.value = false }

const submitForm = async () => {
  try {
    const payload = {
      id_danhmuc: Number(form.value.category),
      id_thuonghieu: Number(form.value.brand),
      tenSP: form.value.name.trim(),
      trangthai: form.value.status === 'Đang bán' ? 1 : 0,
      hinhanh: form.value.img || imgPreview.value || '',
      khoiluong: form.value.weight ? Number(form.value.weight) : null,

      hinh_anhs: extraImagePreviews.value.map((img, index) => ({
        duongdan: img,
        thutu: index,
      })),

      bienthes: generatedRows.value.map((row) => ({
        ten_bienthe: row.ten_bienthe || Object.values(row.attrs || {}).join(' - '),
        gia: Number(row.price) || 0,
        soluong: Number(row.stock) || 0,
      })),
    }

    if (isEditMode.value && editingProductId.value) {
      await api.put(`/sanpham/${editingProductId.value}`, payload)
      alert('Cập nhật sản phẩm thành công')
    } else {
      await api.post('/sanpham', payload)
      alert('Thêm sản phẩm thành công')
    }

    await fetchProducts()
    resetForm()
    closeModal()
  } catch (error) {
    console.error(error)
    alert(getErrorMessage(error, isEditMode.value
      ? 'Có lỗi xảy ra khi cập nhật sản phẩm'
      : 'Có lỗi xảy ra khi thêm sản phẩm'))
  }
}

onMounted(() => {
  fetchProducts()
  fetchCategories()
  fetchBrands()
  fetchAttributeGroups()
  fetchColors()
})
</script>

<template>
  <div class="admin">

    <div class="top">
      <div>
        <h1>Quản lý sản phẩm</h1>
        <p>Cập nhật và theo dõi danh mục thiết bị công nghệ 2026</p>
      </div>
      <button class="add-btn" @click="openModal">+ Thêm sản phẩm</button>
    </div>

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
      <select v-model="selectedCategory">
        <option value="">Tất cả danh mục</option>
        <option
          v-for="category in categories"
          :key="category.id_danhmuc"
          :value="category.id_danhmuc"
        >
          {{ category.ten_danhmuc }}
        </option>
      </select>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>Sản phẩm</th>
            <th>Danh mục</th>
            <th>Thương hiệu</th>
            <th>Tổng biến thể</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!paginatedProducts.length">
            <td colspan="7" class="empty">Không tìm thấy sản phẩm nào.</td>
          </tr>
          <tr v-for="(p, i) in paginatedProducts" :key="p.id">
            <td>
              {{ (currentPage - 1) * PER_PAGE + i + 1 }}
            </td>
            <td>
              <div class="product-cell">
                <img :src="p.img" :alt="p.name" />
                <div><b>{{ p.name }}</b><span>SKU: {{ p.sku }}</span></div>
              </div>
            </td>
            <td><span class="badge">{{ p.category }}</span></td>
            <td><span class="price">{{ p.brand }}</span></td>
            <td>
              <div class="stock-cell">
                <span>{{ p.totalVariants }} biến thể</span>
              </div>
            </td>
            <td><span class="status-badge" :class="p.status === 'Đang bán' ? 'active' : 'draft'">{{ p.status }}</span>
            </td>
            <td>
              <div class="actions">
                <button class="act-btn" title="Sửa" @click="openEditModal(p.id)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                </button>
                <button class="act-btn danger" title="Xóa" @click="removeProduct(p.id)">
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

    <div class="pagination">
      <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">‹</button>

      <button
        v-for="(p, index) in pageItems"
        :key="`${p}-${index}`"
        :class="{ 'pg-active': p === currentPage, 'pg-dots': p === '...' }"
        :disabled="p === '...'"
        @click="p !== '...' && goToPage(p)"
      >
        {{ p }}
      </button>

      <button :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">›</button>
    </div>

    <Teleport to="body">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal modal-wide">

          <div class="modal-header">
            <h3>{{ isEditMode ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới' }}</h3>
            <button class="modal-close" @click="closeModal">×</button>
          </div>

          <div class="modal-body">

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

            <div class="form-group">
              <label>Hình ảnh phụ</label>
              <input
                ref="extraFileInputRef"
                type="file"
                accept="image/*"
                multiple
                style="display:none"
                @change="onExtraFilesChange"
              />
              <div class="upload-zone" @click="triggerExtraFileInput">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="17 8 12 3 7 8" />
                  <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                <p>Kéo thả hoặc <span>bấm để chọn nhiều ảnh</span></p>
                <small>PNG, JPG, WEBP — có thể chọn nhiều ảnh</small>
              </div>

              <div v-if="extraImagePreviews.length" class="multi-preview-wrap">
                <div v-for="(img, index) in extraImagePreviews" :key="index" class="multi-preview-item">
                  <img :src="img" class="multi-preview-img" :alt="`preview-${index}`" />
                  <button class="multi-preview-remove" @click="removeExtraImage(index)">×</button>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Tên sản phẩm <span class="required">*</span></label>
                <input v-model="form.name" placeholder="VD: VinaPro Laptop X2" />
              </div>
              <div class="form-group">
                <label>Thương hiệu <span class="required">*</span></label>
                <select v-model="form.brand">
                  <option value="">-- Chọn thương hiệu --</option>
                  <option
                    v-for="brand in brands"
                    :key="brand.id_thuonghieu"
                    :value="brand.id_thuonghieu"
                  >
                    {{ brand.ten_thuonghieu }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Danh mục <span class="required">*</span></label>
                <select v-model="form.category">
                  <option value="">-- Chọn danh mục --</option>
                  <option
                    v-for="category in categories"
                    :key="category.id_danhmuc"
                    :value="category.id_danhmuc"
                  >
                    {{ category.ten_danhmuc }}
                  </option>
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
                <label>Khối lượng</label>
                <input v-model="form.weight" type="number" min="0" step="0.01" placeholder="VD: 2.5" />
              </div>
              <div class="form-group">
                <label>&nbsp;</label>
                <div></div>
              </div>
            </div>

            <div class="vs-wrapper">
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

              <template v-if="vsPhase === 1 && !isEditMode">
                <div v-if="variantLoading" class="group-placeholder">
                  <span>Đang tải dữ liệu biến thể...</span>
                </div>
                <div v-else class="group-tabs">
                  <button v-for="g in attributeGroups" :key="g.id" class="gtab"
                    :class="{ 'gtab-active': selectedGroupId === g.id }" @click="selectGroup(g.id)">
                    <span class="gtab-icon">{{ g.icon }}</span>
                    <span class="gtab-name">{{ g.name }}</span>
                    <span v-if="selectedCountInGroup(g) > 0" class="gtab-badge">
                      {{ selectedCountInGroup(g) }}
                    </span>
                  </button>
                </div>

                <div v-if="displayAttrTypes.length" class="flat-select-table">
                  <div v-for="t in displayAttrTypes" :key="t.id" class="fst-row">
                    <div class="fst-label">
                      <span class="type-pill" :class="'tp-' + t.color">{{ t.label }}</span>
                      <span v-if="selectedOptions[t.id]?.size" class="fst-count">
                        {{ selectedOptions[t.id].size }}
                      </span>
                    </div>

                    <div class="fst-options">
                      <button
                        v-for="opt in t.options"
                        :key="getOptionValue(opt)"
                        class="vbtn"
                        :class="['vbtn-' + t.color, { 'vbtn-on': isSelected(t.id, getOptionValue(opt)) }]"
                        @click="toggleOption(t.id, getOptionValue(opt))"
                      >
                        <svg
                          v-if="isSelected(t.id, getOptionValue(opt))"
                          viewBox="0 0 10 10"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2.2"
                          stroke-linecap="round"
                          width="9"
                          height="9"
                        >
                          <polyline points="1,5 3.5,7.5 9,2" />
                        </svg>

                        <span v-if="getOptionHex(opt)" class="color-option">
                          <span class="color-dot" :style="{ background: getOptionHex(opt) }"></span>
                          {{ getOptionLabel(opt) }}
                        </span>
                        <span v-else>
                          {{ getOptionLabel(opt) }}
                        </span>
                      </button>
                    </div>
                  </div>
                </div>

                <div v-else class="group-placeholder">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                    width="28" height="28">
                    <rect x="3" y="3" width="18" height="18" rx="3" />
                    <path d="M9 12h6M12 9v6" />
                  </svg>
                  <span>Không có dữ liệu loại thuộc tính</span>
                </div>

                <div v-if="selectedGroup" class="p1-footer">
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

              <template v-if="vsPhase === 2">
                <div class="p2-toolbar">
                  <button v-if="!isEditMode" class="btn-back" @click="vsPhase = 1">
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

                <div class="p2-info">
                  <template v-if="isEditMode">
                    Đang hiển thị <b>{{ generatedRows.length }}</b> biến thể hiện có của sản phẩm.
                  </template>
                  <template v-else>
                    Đã tạo <b>{{ generatedRows.length }}</b> tổ hợp từ
                    <b>{{ activeAttrTypes.length }}</b> loại thuộc tính —
                    mỗi hàng là duy nhất, không trùng lặp.
                  </template>
                </div>

                <div class="vt-scroll">
                  <table class="vt-table">
                    <thead>
                      <tr>
                        <th class="th-no">#</th>
                        <th v-for="t in tableHeaders" :key="t.id">
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
                        <td v-for="t in tableHeaders" :key="t.id">
                          <span class="val-chip" :class="'vc-' + t.color">
                            {{ row.attrs[t.id] || '' }}
                          </span>
                        </td>
                        <td><input
                            v-model.number="row.price"
                            type="number"
                            class="vt-input"
                          /></td>
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
            </div>

            <p v-if="formError" class="form-error">⚠ {{ formError }}</p>
          </div>

          <div class="modal-footer">
            <button class="btn-cancel" @click="closeModal">Hủy</button>
            <button class="btn-submit" @click="submitForm">
              {{ isEditMode ? 'Lưu thay đổi' : 'Thêm sản phẩm' }}
            </button>
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

.color-option {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.color-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 1px solid rgba(15, 23, 42, 0.18);
  flex-shrink: 0;
}

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

.p1-footer {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 13px 16px;
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 10px;
}

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
  width: 50px;
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

.multi-preview-wrap {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 12px;
}

.multi-preview-item {
  position: relative;
  width: 88px;
  height: 88px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
}

.multi-preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.multi-preview-remove {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 22px;
  height: 22px;
  border: none;
  border-radius: 50%;
  background: rgba(15, 23, 42, 0.75);
  color: white;
  font-size: 14px;
  line-height: 1;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: .45;
  cursor: not-allowed;
}

.pg-dots {
  border-color: transparent !important;
  background: transparent !important;
  color: #94a3b8 !important;
}

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