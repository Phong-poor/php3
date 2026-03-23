<template>
  <div class="page">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-left">
        <h2 class="topbar-title">Danh mục sản phẩm</h2>
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" placeholder="Tìm kiếm danh mục..." v-model="searchQuery"/>
        </div>
      </div>
      <div class="topbar-right">
        <button class="icon-btn"><svg viewBox="0 0 24 24" fill="none"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button>
        <button class="icon-btn"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 19.07a10 10 0 0 1 0-14.14"/></svg></button>
        <button class="icon-btn"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg></button>
        <div class="admin-info">
          <div class="admin-avatar">AV</div>
          <div>
            <p class="admin-name">Admin Vina</p>
            <p class="admin-role">Quản trị viên</p>
          </div>
        </div>
      </div>
    </div>

    <!-- HERO -->
    <div class="hero">
      <div class="hero-text">
        <h1>Kiến trúc <span class="hero-accent">Hệ sinh thái</span><br/>Laptop</h1>
        <p>Quản lý và tối ưu hóa các phân khúc sản phẩm dựa trên nhu cầu<br/>thực tế của người dùng 2026. Mỗi danh mục là một điểm chạm<br/>quan trọng trong hành trình mua sắm.</p>
      </div>
      <div class="hero-actions">
        <button class="btn-filter">
          <svg viewBox="0 0 24 24" fill="none"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
          Bộ lọc
        </button>
        <button class="btn-primary" @click="openCreate">
          <svg viewBox="0 0 24 24" fill="none"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Tạo danh mục mới
        </button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>ẢNH ĐẠI DIỆN</th>
            <th>TÊN DANH MỤC</th>
            <th>SLUG / ĐƯỜNG DẪN</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in filteredCategories" :key="cat.id">
            <td>
              <div class="cat-thumb" :style="{ background: cat.color }">
                <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
              </div>
            </td>
            <td>
              <p class="cat-name">{{ cat.name }}</p>
              <p class="cat-count">{{ cat.count }} sản phẩm</p>
            </td>
            <td><span class="slug-tag">{{ cat.slug }}</span></td>
            <td>
              <span :class="['status-badge', cat.status === 'active' ? 'status-active' : 'status-hidden']">
                {{ cat.status === 'active' ? '● ĐANG HOẠT ĐỘNG' : '◌ TẠM ẨN' }}
              </span>
            </td>
            <td>
              <div class="actions">
                <button class="action-btn" title="Chỉnh sửa" @click="openEdit(cat)">
                  <svg viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="action-btn action-delete" title="Xóa" @click="deleteCategory(cat.id)">
                  <svg viewBox="0 0 24 24" fill="none"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="filteredCategories.length === 0">
            <td colspan="5" class="empty-row">Không tìm thấy danh mục nào.</td>
          </tr>
        </tbody>
      </table>

      <!-- PAGINATION -->
      <div class="pagination-row">
        <span class="page-info">Đang hiển thị 1 – {{ filteredCategories.length }} của <strong>{{ categories.length }}</strong> danh mục</span>
        <div class="pagination">
          <button class="page-btn">‹</button>
          <button class="page-btn active">1</button>
          <button class="page-btn">2</button>
          <button class="page-btn">3</button>
          <button class="page-btn">›</button>
        </div>
      </div>
    </div>

    <!-- BOTTOM CARDS -->
    <div class="bottom-cards">
      <div class="bcard bcard-blue">
        <p class="bcard-label">TĂNG TRƯỞNG NHANH NHẤT</p>
        <h3>Laptop Gaming</h3>
        <p class="bcard-stat">→ +24% doanh thu tháng này</p>
      </div>
      <div class="bcard bcard-white">
        <div class="bcard-tag">với ✦ AI</div>
        <div class="bcard-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
        </div>
        <h3>Mở rộng danh mục Phụ kiện</h3>
        <p>Nhu cầu mua kèm chuột Gaming và bàn phím cơ đang tăng 15%.</p>
        <a href="#" class="bcard-link">Xem chi tiết →</a>
      </div>
      <div class="bcard bcard-dark">
        <div class="bcard-dark-header">
          <div class="bcard-dark-icon">
            <svg viewBox="0 0 24 24" fill="none"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <span>Hiệu suất sản<br/>phẩm THỰC</span>
        </div>
        <div class="bcard-perf"><span class="perf-num">98.2<span class="perf-unit">%</span></span></div>
        <div class="perf-bar-wrap">
          <div class="perf-bar"><div class="perf-fill" style="width: 98.2%"></div></div>
        </div>
        <p class="perf-label">Độ khớp sản phẩm phân loại hệ thống</p>
      </div>
    </div>

    <!-- ═══════════ MODAL OVERLAY ═══════════ -->
    <transition name="fade">
      <div class="overlay" v-if="showModal" @click.self="closeModal">
        <transition name="slide-up">
          <div class="modal" v-if="showModal">

            <!-- Modal Header -->
            <div class="modal-header">
              <div class="modal-header-left">
                <div class="modal-icon" :class="isEdit ? 'modal-icon-edit' : 'modal-icon-create'">
                  <svg v-if="!isEdit" viewBox="0 0 24 24" fill="none"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <svg v-else viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                  <h3 class="modal-title">{{ isEdit ? 'Chỉnh sửa danh mục' : 'Tạo danh mục mới' }}</h3>
                  <p class="modal-subtitle">{{ isEdit ? 'Cập nhật thông tin danh mục sản phẩm' : 'Điền thông tin để tạo danh mục mới' }}</p>
                </div>
              </div>
              <button class="modal-close" @click="closeModal">
                <svg viewBox="0 0 24 24" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

              <!-- Tên danh mục -->
              <div class="form-group">
                <label class="form-label">
                  Tên danh mục <span class="required">*</span>
                </label>
                <input
                  class="form-input"
                  :class="{ 'input-error': errors.name }"
                  type="text"
                  placeholder="VD: Laptop Gaming"
                  v-model="form.name"
                  @input="autoSlug"
                />
                <p class="error-msg" v-if="errors.name">{{ errors.name }}</p>
              </div>

              <!-- Slug -->
              <div class="form-group">
                <label class="form-label">
                  Slug / Đường dẫn <span class="required">*</span>
                </label>
                <div class="input-with-prefix">
                  <span class="input-prefix">/danh-muc/</span>
                  <input
                    class="form-input prefix-input"
                    :class="{ 'input-error': errors.slug }"
                    type="text"
                    placeholder="laptop-gaming"
                    v-model="form.slug"
                  />
                </div>
                <p class="error-msg" v-if="errors.slug">{{ errors.slug }}</p>
                <p class="form-hint">Tự động tạo từ tên. Chỉ dùng chữ thường, số và dấu gạch ngang.</p>
              </div>

              <!-- Mô tả -->
              <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea
                  class="form-input form-textarea"
                  placeholder="Mô tả ngắn về danh mục này..."
                  v-model="form.description"
                  rows="3"
                ></textarea>
              </div>

              <!-- Màu + Trạng thái (2 cột) -->
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Màu đại diện</label>
                  <div class="color-picker-row">
                    <div
                      v-for="c in colorOptions"
                      :key="c"
                      class="color-dot"
                      :style="{ background: c }"
                      :class="{ 'color-dot-active': form.color === c }"
                      @click="form.color = c"
                    ></div>
                    <input type="color" class="color-custom" v-model="form.color" title="Chọn màu tùy chỉnh"/>
                  </div>
                  <!-- preview -->
                  <div class="color-preview" :style="{ background: form.color }">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Trạng thái</label>
                  <div class="toggle-group">
                    <button
                      class="toggle-btn"
                      :class="{ 'toggle-active-green': form.status === 'active' }"
                      @click="form.status = 'active'"
                    >
                      <span class="toggle-dot"></span>
                      Hoạt động
                    </button>
                    <button
                      class="toggle-btn"
                      :class="{ 'toggle-active-purple': form.status === 'hidden' }"
                      @click="form.status = 'hidden'"
                    >
                      <span class="toggle-dot"></span>
                      Tạm ẩn
                    </button>
                  </div>
                  <p class="form-hint">Danh mục ẩn sẽ không hiển thị ngoài cửa hàng.</p>
                </div>
              </div>

              <!-- Thứ tự hiển thị -->
              <div class="form-group">
                <label class="form-label">Thứ tự hiển thị</label>
                <input
                  class="form-input"
                  type="number"
                  placeholder="0"
                  v-model.number="form.order"
                  min="0"
                />
                <p class="form-hint">Số nhỏ hơn hiển thị trước. Mặc định: 0</p>
              </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button class="btn-cancel" @click="closeModal">Hủy</button>
              <button class="btn-save" @click="saveCategory">
                <svg viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                {{ isEdit ? 'Lưu thay đổi' : 'Tạo danh mục' }}
              </button>
            </div>

          </div>
        </transition>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const showModal = ref(false)
const isEdit = ref(false)
const editId = ref(null)

const colorOptions = ['#dbeafe', '#fce7f3', '#d1fae5', '#ede9fe', '#fef9c3', '#ffedd5']

const defaultForm = () => ({
  name: '',
  slug: '',
  description: '',
  color: '#dbeafe',
  status: 'active',
  order: 0,
})

const form = ref(defaultForm())
const errors = ref({})

const categories = ref([
  { id: 1, name: 'Laptop Gaming', count: 134, slug: 'laptop-gaming', status: 'active', color: '#dbeafe', description: '', order: 1 },
  { id: 2, name: 'Laptop Văn phòng', count: 312, slug: 'laptop-van-phong', status: 'active', color: '#fce7f3', description: '', order: 2 },
  { id: 3, name: 'Laptop Đồ họa', count: 85, slug: 'laptop-do-hoa', status: 'active', color: '#d1fae5', description: '', order: 3 },
  { id: 4, name: 'Laptop Sinh viên', count: 206, slug: 'laptop-sinh-vien', status: 'hidden', color: '#ede9fe', description: '', order: 4 },
])

const filteredCategories = computed(() => {
  if (!searchQuery.value) return categories.value
  return categories.value.filter(c =>
    c.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    c.slug.includes(searchQuery.value.toLowerCase())
  )
})

function autoSlug() {
  if (!isEdit.value || form.value.slug === '') {
    form.value.slug = form.value.name
      .toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/đ/g, 'd').replace(/Đ/g, 'd')
      .replace(/[^a-z0-9\s-]/g, '')
      .trim().replace(/\s+/g, '-')
  }
}

function validate() {
  errors.value = {}
  if (!form.value.name.trim()) errors.value.name = 'Tên danh mục không được để trống.'
  if (!form.value.slug.trim()) errors.value.slug = 'Slug không được để trống.'
  else if (!/^[a-z0-9-]+$/.test(form.value.slug)) errors.value.slug = 'Slug chỉ được dùng chữ thường, số và dấu -'
  return Object.keys(errors.value).length === 0
}

function openCreate() {
  isEdit.value = false
  editId.value = null
  form.value = defaultForm()
  errors.value = {}
  showModal.value = true
}

function openEdit(cat) {
  isEdit.value = true
  editId.value = cat.id
  form.value = { ...cat }
  errors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function saveCategory() {
  if (!validate()) return
  if (isEdit.value) {
    const idx = categories.value.findIndex(c => c.id === editId.value)
    if (idx !== -1) categories.value[idx] = { ...categories.value[idx], ...form.value }
  } else {
    categories.value.push({
      id: Date.now(),
      count: 0,
      ...form.value,
    })
  }
  closeModal()
}

function deleteCategory(id) {
  if (confirm('Bạn có chắc muốn xóa danh mục này?')) {
    categories.value = categories.value.filter(c => c.id !== id)
  }
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.page {
  padding: 24px 28px;
  background: #f0f4ff;
  min-height: 100vh;
  font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* ── TOPBAR ── */
.topbar { display: flex; align-items: center; justify-content: space-between; }
.topbar-left { display: flex; align-items: center; gap: 16px; }
.topbar-title { font-size: 15px; font-weight: 600; color: #1e293b; white-space: nowrap; }
.search-box { display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 7px 14px; width: 220px; }
.search-box svg { width: 15px; height: 15px; stroke: #94a3b8; stroke-width: 2; fill: none; }
.search-box input { border: none; outline: none; font-size: 13px; color: #1e293b; background: transparent; width: 100%; }
.search-box input::placeholder { color: #94a3b8; }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.icon-btn { width: 36px; height: 36px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
.icon-btn:hover { background: #f1f5f9; }
.icon-btn svg { width: 16px; height: 16px; stroke: #64748b; stroke-width: 1.8; fill: none; }
.admin-info { display: flex; align-items: center; gap: 9px; }
.admin-avatar { width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 50%; color: #fff; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
.admin-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.admin-role { font-size: 11px; color: #94a3b8; }

/* ── HERO ── */
.hero { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; }
.hero h1 { font-size: 32px; font-weight: 800; color: #0f172a; line-height: 1.25; margin-bottom: 12px; }
.hero-accent { color: #4f46e5; }
.hero-text p { font-size: 13.5px; color: #64748b; line-height: 1.7; }
.hero-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.btn-filter { display: flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer; transition: background 0.2s; }
.btn-filter:hover { background: #f8fafc; }
.btn-filter svg { width: 15px; height: 15px; stroke: #475569; stroke-width: 2; fill: none; }
.btn-primary { display: flex; align-items: center; gap: 7px; padding: 10px 20px; border-radius: 10px; border: none; background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 14px rgba(79,70,229,0.35); transition: transform 0.15s, box-shadow 0.15s; }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,0.4); }
.btn-primary svg { width: 15px; height: 15px; stroke: #fff; stroke-width: 2.5; fill: none; }

/* ── TABLE ── */
.table-card { background: #fff; border-radius: 16px; border: 1px solid #e8edf5; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
table { width: 100%; border-collapse: collapse; }
thead tr { background: #f8faff; border-bottom: 1px solid #e8edf5; }
th { padding: 13px 20px; font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 0.6px; text-align: left; }
tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbff; }
td { padding: 16px 20px; vertical-align: middle; }
.cat-thumb { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.cat-thumb svg { width: 20px; height: 20px; stroke: #4f46e5; stroke-width: 1.8; fill: none; }
.cat-name { font-size: 14px; font-weight: 600; color: #1e293b; }
.cat-count { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.slug-tag { display: inline-block; background: #f1f5f9; color: #475569; font-size: 11.5px; font-weight: 500; padding: 4px 10px; border-radius: 6px; font-family: monospace; }
.status-badge { font-size: 11px; font-weight: 700; letter-spacing: 0.3px; padding: 5px 10px; border-radius: 6px; display: inline-block; }
.status-active { color: #16a34a; background: #dcfce7; }
.status-hidden { color: #9333ea; background: #f3e8ff; }
.actions { display: flex; gap: 6px; }
.action-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; }
.action-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
.action-btn svg { width: 14px; height: 14px; stroke: #64748b; stroke-width: 1.8; fill: none; }
.action-delete:hover { background: #fef2f2; border-color: #fca5a5; }
.action-delete:hover svg { stroke: #ef4444; }
.empty-row { text-align: center; color: #94a3b8; font-size: 13px; padding: 30px; }
.pagination-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid #f1f5f9; }
.page-info { font-size: 13px; color: #64748b; }
.pagination { display: flex; gap: 4px; }
.page-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; font-size: 13px; color: #475569; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
.page-btn:hover { background: #f1f5f9; }
.page-btn.active { background: #4f46e5; border-color: #4f46e5; color: #fff; font-weight: 600; }

/* ── BOTTOM CARDS ── */
.bottom-cards { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.bcard { border-radius: 16px; padding: 22px; display: flex; flex-direction: column; gap: 8px; }
.bcard-blue { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; }
.bcard-blue .bcard-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; opacity: 0.7; }
.bcard-blue h3 { font-size: 20px; font-weight: 800; }
.bcard-stat { font-size: 13px; opacity: 0.85; }
.bcard-white { background: #fff; border: 1px solid #e8edf5; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
.bcard-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 600; color: #6366f1; background: #eef2ff; padding: 3px 8px; border-radius: 20px; align-self: flex-end; }
.bcard-icon-wrap { width: 40px; height: 40px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.bcard-icon-wrap svg { width: 20px; height: 20px; stroke: #4f46e5; stroke-width: 1.8; fill: none; }
.bcard-white h3 { font-size: 15px; font-weight: 700; color: #1e293b; }
.bcard-white p { font-size: 12.5px; color: #64748b; line-height: 1.6; }
.bcard-link { font-size: 12.5px; font-weight: 600; color: #4f46e5; text-decoration: none; }
.bcard-link:hover { text-decoration: underline; }
.bcard-dark { background: #0f172a; color: #fff; }
.bcard-dark-header { display: flex; align-items: center; gap: 10px; font-size: 12px; color: #94a3b8; line-height: 1.4; }
.bcard-dark-icon { width: 32px; height: 32px; background: #1e293b; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bcard-dark-icon svg { width: 16px; height: 16px; stroke: #4ade80; stroke-width: 2; fill: none; }
.perf-num { font-size: 44px; font-weight: 800; color: #fff; line-height: 1; }
.perf-unit { font-size: 22px; }
.perf-bar { height: 5px; background: #1e293b; border-radius: 99px; overflow: hidden; }
.perf-fill { height: 100%; background: linear-gradient(90deg, #4ade80, #22c55e); border-radius: 99px; }
.perf-label { font-size: 11px; color: #475569; }

/* ═══════════ MODAL ═══════════ */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  backdrop-filter: blur(4px);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.modal {
  background: #fff;
  border-radius: 20px;
  width: 100%;
  max-width: 560px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.18);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
}

/* Modal Header */
.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 24px 20px;
  border-bottom: 1px solid #f1f5f9;
}
.modal-header-left { display: flex; align-items: center; gap: 14px; }
.modal-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
}
.modal-icon-create { background: linear-gradient(135deg, #4f46e5, #6366f1); }
.modal-icon-create svg { width: 20px; height: 20px; stroke: #fff; stroke-width: 2.5; fill: none; }
.modal-icon-edit { background: linear-gradient(135deg, #f59e0b, #f97316); }
.modal-icon-edit svg { width: 20px; height: 20px; stroke: #fff; stroke-width: 2; fill: none; }
.modal-title { font-size: 16px; font-weight: 700; color: #0f172a; }
.modal-subtitle { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.modal-close { width: 34px; height: 34px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; flex-shrink: 0; }
.modal-close:hover { background: #fee2e2; border-color: #fca5a5; }
.modal-close svg { width: 15px; height: 15px; stroke: #64748b; stroke-width: 2; fill: none; }
.modal-close:hover svg { stroke: #ef4444; }

/* Modal Body */
.modal-body { padding: 22px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 18px; }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.form-label { font-size: 13px; font-weight: 600; color: #374151; }
.required { color: #ef4444; }

.form-input {
  padding: 10px 14px;
  border: 1.5px solid #e2e8f0;
  border-radius: 10px;
  font-size: 13.5px;
  color: #1e293b;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  font-family: inherit;
  width: 100%;
  background: #fff;
}
.form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.input-error { border-color: #ef4444 !important; }
.input-error:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.form-textarea { resize: vertical; min-height: 80px; }

.error-msg { font-size: 12px; color: #ef4444; }
.form-hint { font-size: 11.5px; color: #94a3b8; }

.input-with-prefix { display: flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s; }
.input-with-prefix:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.input-prefix { padding: 10px 12px; background: #f8fafc; font-size: 12px; color: #94a3b8; white-space: nowrap; border-right: 1.5px solid #e2e8f0; font-family: monospace; }
.prefix-input { border: none !important; border-radius: 0 !important; box-shadow: none !important; flex: 1; }
.prefix-input:focus { border: none; box-shadow: none; }

/* Color picker */
.color-picker-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.color-dot { width: 26px; height: 26px; border-radius: 50%; cursor: pointer; border: 2.5px solid transparent; transition: transform 0.15s, border-color 0.15s; }
.color-dot:hover { transform: scale(1.15); }
.color-dot-active { border-color: #4f46e5; transform: scale(1.15); }
.color-custom { width: 26px; height: 26px; border-radius: 50%; border: 2px solid #e2e8f0; cursor: pointer; padding: 0; background: none; }

.color-preview {
  width: 48px; height: 48px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  margin-top: 8px;
}
.color-preview svg { width: 22px; height: 22px; stroke: #4f46e5; stroke-width: 1.8; fill: none; }

/* Toggle */
.toggle-group { display: flex; flex-direction: column; gap: 6px; }
.toggle-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 9px 14px;
  border-radius: 10px;
  border: 1.5px solid #e2e8f0;
  background: #f8fafc;
  font-size: 13px; font-weight: 500; color: #64748b;
  cursor: pointer;
  transition: all 0.15s;
  text-align: left;
}
.toggle-dot { width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; transition: background 0.15s; }
.toggle-active-green { border-color: #16a34a; background: #f0fdf4; color: #15803d; }
.toggle-active-green .toggle-dot { background: #16a34a; }
.toggle-active-purple { border-color: #9333ea; background: #faf5ff; color: #7e22ce; }
.toggle-active-purple .toggle-dot { background: #9333ea; }

/* Modal Footer */
.modal-footer {
  display: flex; align-items: center; justify-content: flex-end; gap: 10px;
  padding: 16px 24px;
  border-top: 1px solid #f1f5f9;
  background: #fafbff;
}
.btn-cancel { padding: 10px 20px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #fff; font-size: 13px; font-weight: 500; color: #64748b; cursor: pointer; transition: all 0.15s; }
.btn-cancel:hover { background: #f1f5f9; }
.btn-save { display: flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 10px; border: none; background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 14px rgba(79,70,229,0.3); transition: transform 0.15s, box-shadow 0.15s; }
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,0.4); }
.btn-save svg { width: 15px; height: 15px; stroke: #fff; stroke-width: 2; fill: none; }

/* Transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-up-enter-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.slide-up-leave-active { transition: all 0.2s ease; }
.slide-up-enter-from { opacity: 0; transform: translateY(30px) scale(0.97); }
.slide-up-leave-to { opacity: 0; transform: translateY(10px) scale(0.98); }

a { text-decoration: none; }
</style>