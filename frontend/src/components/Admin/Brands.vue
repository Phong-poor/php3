<template>
  <div class="app">

    <!-- ── HEADER ── -->
    <header class="header">
      <div class="search-bar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" placeholder="Tìm kiếm thương hiệu..." v-model="searchQuery"/>
      </div>
      <div class="header-right">
        <button class="icon-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </button>
        <button class="icon-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
        </button>
        <div class="user-badge">
          <div class="user-info">
            <span class="user-name">Admin Vina</span>
            <span class="user-role">Quản trị viên</span>
          </div>
          <div class="avatar">V</div>
        </div>
      </div>
    </header>

    <div class="main-content">

      <!-- ── PAGE HEADER ── -->
      <div class="page-header">
        <div>
          <p class="breadcrumb">HỆ THỐNG <span class="sep">›</span> THƯƠNG HIỆU</p>
          <h1 class="page-title">Thương <span class="title-accent">Hiệu</span></h1>
          <p class="page-desc">Quản lý các đối tác phần cứng và hệ sinh thái sản phẩm trong chuỗi ứng VinaTech.</p>
        </div>
        <button class="btn-primary" @click="openAddModal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
          Thêm mới
        </button>
      </div>

      <!-- ── STATS ── -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          </div>
          <div>
            <p class="stat-label">Tổng Thương Hiệu</p>
            <h2 class="stat-value">{{ brands.length }}</h2>
            <p class="stat-trend">↑ +12% tháng này</p>
          </div>
        </div>

        <div class="stat-card stat-active">
          <div class="stat-icon-wrap stat-icon-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
          <div>
            <p class="stat-label">Đang Hoạt Động</p>
            <h2 class="stat-value">{{ activeCount }}</h2>
            <p class="stat-trend trend-green">● Đang chạy</p>
          </div>
        </div>

        <div class="growth-card">
          <div class="growth-text">
            <p class="growth-tag">TĂNG TRƯỞNG QUÝ 4</p>
            <h3 class="growth-title">Vượt mục tiêu 15%</h3>
            <p class="growth-desc">Hiệu suất niêm yết thương hiệu mới vượt kỳ vọng toàn hệ thống.</p>
          </div>
          <svg viewBox="0 0 80 44" width="76" height="44" class="growth-chart">
            <rect x="0"  y="32" width="12" height="12" rx="2" fill="rgba(99,102,241,0.35)"/>
            <rect x="16" y="22" width="12" height="22" rx="2" fill="rgba(99,102,241,0.5)"/>
            <rect x="32" y="14" width="12" height="30" rx="2" fill="rgba(99,102,241,0.7)"/>
            <rect x="48" y="6"  width="12" height="38" rx="2" fill="rgba(99,102,241,0.85)"/>
            <rect x="64" y="0"  width="12" height="44" rx="2" fill="#6366f1"/>
          </svg>
        </div>
      </div>

      <!-- ── TABLE ── -->
      <div class="table-card">
        <!-- table toolbar -->
        <div class="table-toolbar">
          <div class="toolbar-left">
            <span class="toolbar-count">{{ filteredBrands.length }} thương hiệu</span>
            <div class="filter-tabs">
              <button :class="['ftab', filterStatus === '' && 'ftab-active']" @click="filterStatus = ''">Tất cả</button>
              <button :class="['ftab', filterStatus === 'active' && 'ftab-active']" @click="filterStatus = 'active'">Hoạt động</button>
              <button :class="['ftab', filterStatus === 'paused' && 'ftab-active']" @click="filterStatus = 'paused'">Tạm ngừng</button>
            </div>
          </div>
        </div>

        <table class="brand-table">
          <thead>
            <tr>
              <th>LOGO</th>
              <th>THƯƠNG HIỆU</th>
              <th>SẢN PHẨM</th>
              <th>WEBSITE</th>
              <th>TRẠNG THÁI</th>
              <th>THAO TÁC</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="brand in paginatedBrands" :key="brand.id" class="brand-row">
              <td>
                <div class="brand-logo" :style="{ background: brand.color }">
                  <span>{{ brand.initials }}</span>
                </div>
              </td>
              <td>
                <p class="brand-name">{{ brand.name }}</p>
                <p class="brand-location">{{ brand.location }}</p>
              </td>
              <td>
                <span class="product-count">{{ brand.products.toLocaleString() }}</span>
                <p class="product-label">sản phẩm</p>
              </td>
              <td>
                <a v-if="brand.website" :href="brand.website" target="_blank" class="website-link">
                  {{ brand.website.replace('https://','') }}
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
                <span v-else class="no-website">—</span>
              </td>
              <td>
                <span class="status-badge" :class="brand.status === 'active' ? 'status-active' : 'status-paused'">
                  {{ brand.status === 'active' ? '● Hoạt động' : '◌ Tạm ngừng' }}
                </span>
              </td>
              <td>
                <div class="action-btns">
                  <button class="action-btn edit-btn" @click="openEditModal(brand)" title="Chỉnh sửa">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="action-btn delete-btn" @click="deleteBrand(brand.id)" title="Xóa">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="paginatedBrands.length === 0">
              <td colspan="6" class="empty-row">Không tìm thấy thương hiệu nào.</td>
            </tr>
          </tbody>
        </table>

        <div class="table-footer">
          <span class="table-info">Hiển thị <strong>{{ startItem }}–{{ endItem }}</strong> của <strong>{{ filteredBrands.length }}</strong> thương hiệu</span>
          <div class="pagination">
            <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button v-for="p in totalPages" :key="p" class="page-btn" :class="{ active: p === currentPage }" @click="currentPage = p">{{ p }}</button>
            <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>
      </div>

      <!-- ── PROMO BANNER ── -->
      <div class="promo-banner">
        <div class="promo-content">
          <span class="promo-tag">✦ ĐỐI TÁC ƯU TÚ 2026</span>
          <h3 class="promo-title">Vinh danh thương hiệu<br/>xuất sắc nhất năm</h3>
          <p class="promo-desc">Chương trình vinh danh các thương hiệu có tỷ lệ bảo hành thấp nhất và chỉ số hài lòng khách hàng cao nhất trong hệ sinh thái VinaTech.</p>
          <button class="btn-outline">Xem danh sách đề cử →</button>
        </div>
        <div class="promo-visual">
          <div class="medal">🏆</div>
          <div class="star s1">★</div>
          <div class="star s2">★</div>
          <div class="star s3">✦</div>
        </div>
      </div>

    </div>

    <!-- ══════ MODAL ══════ -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <transition name="slide-up">
          <div class="modal" v-if="showModal">

            <div class="modal-header">
              <div class="modal-header-left">
                <div class="modal-icon" :class="isEditing ? 'icon-edit' : 'icon-add'">
                  <svg v-if="!isEditing" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                  <svg v-else viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                  <h2 class="modal-title">{{ isEditing ? 'Chỉnh sửa thương hiệu' : 'Thêm thương hiệu mới' }}</h2>
                  <p class="modal-subtitle">{{ isEditing ? 'Cập nhật thông tin đối tác thương hiệu' : 'Điền thông tin để thêm thương hiệu mới' }}</p>
                </div>
              </div>
              <button class="modal-close" @click="closeModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <div class="modal-body">

              <!-- Preview + color -->
              <div class="preview-row">
                <div class="logo-preview" :style="{ background: form.color }">
                  <span>{{ form.initials || '?' }}</span>
                </div>
                <div class="preview-meta">
                  <p class="preview-name">{{ form.name || 'Tên thương hiệu' }}</p>
                  <p class="preview-loc">{{ form.location || 'Địa chỉ trụ sở' }}</p>
                  <div class="color-swatches">
                    <div v-for="c in colorOptions" :key="c"
                      class="swatch" :style="{ background: c }"
                      :class="{ selected: form.color === c }"
                      @click="form.color = c">
                    </div>
                    <input type="color" class="color-custom" v-model="form.color" title="Màu tùy chỉnh"/>
                  </div>
                </div>
              </div>

              <div class="form-grid">

                <div class="field">
                  <label class="field-label">Tên thương hiệu <span class="req">*</span></label>
                  <input v-model="form.name" @input="updateInitials" class="field-input" :class="{ err: errors.name }" placeholder="VD: Apple Inc."/>
                  <p class="err-msg" v-if="errors.name">{{ errors.name }}</p>
                </div>

                <div class="field">
                  <label class="field-label">Chữ viết tắt</label>
                  <input v-model="form.initials" class="field-input mono" placeholder="VD: AP" maxlength="3"/>
                </div>

                <div class="field full">
                  <label class="field-label">Địa chỉ / Trụ sở <span class="req">*</span></label>
                  <input v-model="form.location" class="field-input" :class="{ err: errors.location }" placeholder="VD: Cupertino, California"/>
                  <p class="err-msg" v-if="errors.location">{{ errors.location }}</p>
                </div>

                <div class="field">
                  <label class="field-label">Số lượng sản phẩm <span class="req">*</span></label>
                  <input v-model.number="form.products" type="number" class="field-input" :class="{ err: errors.products }" placeholder="0" min="0"/>
                  <p class="err-msg" v-if="errors.products">{{ errors.products }}</p>
                </div>

                <div class="field">
                  <label class="field-label">Trạng thái</label>
                  <div class="toggle-group">
                    <button class="toggle-btn" :class="{ 'toggle-active-green': form.status === 'active' }" @click="form.status = 'active'">
                      <span class="tdot"></span> Hoạt động
                    </button>
                    <button class="toggle-btn" :class="{ 'toggle-active-yellow': form.status === 'paused' }" @click="form.status = 'paused'">
                      <span class="tdot"></span> Tạm ngừng
                    </button>
                  </div>
                </div>

                <div class="field full">
                  <label class="field-label">Website</label>
                  <div class="input-prefix-wrap">
                    <span class="input-prefix">https://</span>
                    <input v-model="form.websiteDomain" class="field-input prefix-field" placeholder="apple.com"/>
                  </div>
                </div>

                <div class="field full">
                  <label class="field-label">Ghi chú</label>
                  <textarea v-model="form.note" class="field-input field-textarea" rows="3" placeholder="Thêm ghi chú về thương hiệu..."></textarea>
                </div>

              </div>
            </div>

            <div class="modal-footer">
              <button class="btn-cancel" @click="closeModal">Huỷ bỏ</button>
              <button class="btn-primary" @click="submitForm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                {{ isEditing ? 'Lưu thay đổi' : 'Thêm thương hiệu' }}
              </button>
            </div>

          </div>
        </transition>
      </div>
    </transition>

    <!-- ── TOAST ── -->
    <transition name="toast">
      <div v-if="toast.show" class="toast" :class="'toast-' + toast.type">
        <svg v-if="toast.type === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ toast.message }}
      </div>
    </transition>

  </div>
</template>

<script>
export default {
  name: 'ThuongHieu',
  data() {
    return {
      searchQuery: '',
      filterStatus: '',
      currentPage: 1,
      perPage: 5,
      showModal: false,
      isEditing: false,
      editingId: null,
      toast: { show: false, message: '', type: 'success' },
      colorOptions: ['#1a1a2e','#e63946','#2563eb','#059669','#7c3aed','#dc2626','#0891b2','#d97706','#0f172a','#be185d'],
      form: this.emptyForm(),
      errors: {},
      brands: [
        { id:1, name:'Apple Inc.',    location:'Cupertino, California',      products:1240, status:'active', color:'#1a1a2e', initials:'AP', website:'https://apple.com',     websiteDomain:'apple.com',     note:'' },
        { id:2, name:'ASUS',          location:'Beitou District, Taipei',    products:856,  status:'active', color:'#2563eb', initials:'AS', website:'https://asus.com',      websiteDomain:'asus.com',      note:'' },
        { id:3, name:'Dell',          location:'Round Rock, Texas',          products:912,  status:'active', color:'#0891b2', initials:'DL', website:'https://dell.com',      websiteDomain:'dell.com',      note:'' },
        { id:4, name:'HP Inc.',       location:'Palo Alto, California',      products:645,  status:'paused', color:'#059669', initials:'HP', website:'https://hp.com',        websiteDomain:'hp.com',        note:'' },
        { id:5, name:'Lenovo',        location:'Haidian District, Beijing',  products:1012, status:'active', color:'#dc2626', initials:'LV', website:'https://lenovo.com',    websiteDomain:'lenovo.com',    note:'' },
        { id:6, name:'Samsung',       location:'Suwon, South Korea',         products:2100, status:'active', color:'#1a1a2e', initials:'SS', website:'https://samsung.com',   websiteDomain:'samsung.com',   note:'' },
        { id:7, name:'LG Electronics',location:'Seoul, South Korea',         products:780,  status:'active', color:'#7c3aed', initials:'LG', website:'https://lg.com',        websiteDomain:'lg.com',        note:'' },
        { id:8, name:'Microsoft',     location:'Redmond, Washington',        products:430,  status:'paused', color:'#0891b2', initials:'MS', website:'https://microsoft.com', websiteDomain:'microsoft.com', note:'' },
      ]
    }
  },
  computed: {
    activeCount() { return this.brands.filter(b => b.status === 'active').length },
    filteredBrands() {
      const q = this.searchQuery.toLowerCase()
      return this.brands.filter(b => {
        const matchQ = !q || b.name.toLowerCase().includes(q) || b.location.toLowerCase().includes(q)
        const matchS  = !this.filterStatus || b.status === this.filterStatus
        return matchQ && matchS
      })
    },
    totalPages() { return Math.max(1, Math.ceil(this.filteredBrands.length / this.perPage)) },
    paginatedBrands() {
      const start = (this.currentPage - 1) * this.perPage
      return this.filteredBrands.slice(start, start + this.perPage)
    },
    startItem() { return this.filteredBrands.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1 },
    endItem()   { return Math.min(this.currentPage * this.perPage, this.filteredBrands.length) },
  },
  watch: {
    searchQuery()    { this.currentPage = 1 },
    filterStatus()   { this.currentPage = 1 },
  },
  methods: {
    emptyForm() {
      return { name:'', initials:'', location:'', products:0, status:'active', color:'#1a1a2e', website:'', websiteDomain:'', note:'' }
    },
    updateInitials() {
      if (!this.isEditing) {
        const words = this.form.name.trim().split(' ')
        this.form.initials = words.map(w => w[0] || '').join('').toUpperCase().slice(0,2)
      }
    },
    openAddModal() {
      this.isEditing = false; this.editingId = null
      this.form = this.emptyForm(); this.errors = {}
      this.showModal = true
    },
    openEditModal(brand) {
      this.isEditing = true; this.editingId = brand.id
      this.form = { ...brand }; this.errors = {}
      this.showModal = true
    },
    closeModal() { this.showModal = false },
    validate() {
      this.errors = {}
      if (!this.form.name.trim())      this.errors.name     = 'Vui lòng nhập tên thương hiệu'
      if (!this.form.location.trim())  this.errors.location = 'Vui lòng nhập địa chỉ'
      if (this.form.products < 0)      this.errors.products = 'Số sản phẩm không hợp lệ'
      return Object.keys(this.errors).length === 0
    },
    submitForm() {
      if (!this.validate()) return
      const website = this.form.websiteDomain ? 'https://' + this.form.websiteDomain : ''
      if (this.isEditing) {
        const idx = this.brands.findIndex(b => b.id === this.editingId)
        if (idx !== -1) this.brands.splice(idx, 1, { ...this.form, id: this.editingId, website })
        this.showToast('Cập nhật thương hiệu thành công!', 'success')
      } else {
        const newId = Math.max(0, ...this.brands.map(b => b.id)) + 1
        this.brands.push({ ...this.form, id: newId, website })
        this.showToast('Thêm thương hiệu thành công!', 'success')
      }
      this.closeModal()
    },
    deleteBrand(id) {
      if (!confirm('Bạn có chắc muốn xoá thương hiệu này không?')) return
      this.brands = this.brands.filter(b => b.id !== id)
      if (this.currentPage > this.totalPages) this.currentPage = this.totalPages
      this.showToast('Đã xoá thương hiệu.', 'success')
    },
    showToast(message, type = 'success') {
      this.toast = { show: true, message, type }
      setTimeout(() => { this.toast.show = false }, 3000)
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');
* { box-sizing: border-box; margin: 0; padding: 0; }

.app { font-family: 'Be Vietnam Pro', sans-serif; background: #f0f2f8; min-height: 100vh; color: #1e293b; }

/* ── HEADER ── */
.header { display:flex; align-items:center; justify-content:space-between; background:#fff; padding:12px 28px; border-bottom:1px solid #e8ecf4; position:sticky; top:0; z-index:50; box-shadow:0 1px 6px rgba(0,0,0,.05); }
.search-bar { display:flex; align-items:center; gap:8px; background:#f4f6fb; border:1px solid #e4e8f0; border-radius:10px; padding:8px 14px; width:260px; color:#94a3b8; transition:border-color .2s, box-shadow .2s; }
.search-bar:focus-within { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); background:#fff; }
.search-bar svg { width:15px; height:15px; flex-shrink:0; }
.search-bar input { border:none; background:transparent; outline:none; font-size:13px; width:100%; color:#334155; font-family:inherit; }
.search-bar input::placeholder { color:#94a3b8; }
.header-right { display:flex; align-items:center; gap:8px; }
.icon-btn { background:#f4f6fb; border:1px solid #e4e8f0; border-radius:9px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b; transition:background .15s; }
.icon-btn svg { width:16px; height:16px; }
.icon-btn:hover { background:#e8ecf4; }
.user-badge { display:flex; align-items:center; gap:9px; margin-left:6px; }
.user-name { font-size:13px; font-weight:600; color:#1e293b; text-align:right; }
.user-role { font-size:11px; color:#94a3b8; text-align:right; }
.user-info { display:flex; flex-direction:column; }
.avatar { width:36px; height:36px; background:linear-gradient(135deg,#6366f1,#8b5cf6); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:14px; }

/* ── MAIN ── */
.main-content { padding:26px 32px; max-width:1060px; margin:0 auto; display:flex; flex-direction:column; gap:20px; }

/* PAGE HEADER */
.breadcrumb { font-size:10.5px; font-weight:700; letter-spacing:.8px; color:#94a3b8; margin-bottom:6px; }
.sep { margin:0 6px; }
.page-header { display:flex; align-items:flex-end; justify-content:space-between; }
.page-title { font-size:28px; font-weight:800; color:#0f172a; letter-spacing:-.5px; }
.title-accent { color:#6366f1; }
.page-desc { font-size:13px; color:#64748b; margin-top:4px; max-width:420px; line-height:1.55; }

/* BUTTONS */
.btn-primary { display:flex; align-items:center; gap:7px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border:none; border-radius:11px; padding:11px 20px; font-size:13.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:transform .15s, box-shadow .15s; box-shadow:0 4px 14px rgba(99,102,241,.35); }
.btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(99,102,241,.4); }
.btn-primary svg { width:15px; height:15px; }

/* STATS */
.stats-row { display:grid; grid-template-columns:1fr 1fr 2fr; gap:14px; }
.stat-card { background:#fff; border-radius:16px; padding:20px; border:1px solid #e8ecf4; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; gap:14px; }
.stat-icon-wrap { width:44px; height:44px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.stat-icon-wrap svg { width:20px; height:20px; stroke:#6366f1; }
.stat-icon-green { background:#d1fae5; }
.stat-icon-green svg { stroke:#059669; }
.stat-label { font-size:11px; font-weight:700; letter-spacing:.5px; color:#94a3b8; text-transform:uppercase; margin-bottom:4px; }
.stat-value { font-size:34px; font-weight:800; color:#0f172a; line-height:1; }
.stat-trend { font-size:12px; color:#10b981; font-weight:600; margin-top:5px; }
.trend-green { color:#059669; }
.growth-card { background:#fff; border-radius:16px; padding:20px 24px; border:1px solid #e8ecf4; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; justify-content:space-between; gap:16px; }
.growth-tag { font-size:10px; font-weight:700; letter-spacing:1px; color:#6366f1; margin-bottom:6px; }
.growth-title { font-size:17px; font-weight:800; color:#0f172a; margin-bottom:5px; }
.growth-desc { font-size:12.5px; color:#64748b; line-height:1.55; max-width:260px; }
.growth-chart { flex-shrink:0; }

/* TABLE */
.table-card { background:#fff; border-radius:16px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.table-toolbar { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border-bottom:1px solid #f1f5f9; }
.toolbar-left { display:flex; align-items:center; gap:14px; }
.toolbar-count { font-size:12px; font-weight:700; color:#94a3b8; }
.filter-tabs { display:flex; gap:4px; }
.ftab { padding:5px 12px; border-radius:8px; border:1px solid #e4e8f0; background:#f8fafc; font-size:12px; font-weight:600; color:#64748b; cursor:pointer; font-family:inherit; transition:all .15s; }
.ftab:hover { background:#eef2ff; color:#6366f1; border-color:#c7d2fe; }
.ftab-active { background:#eef2ff; border-color:#6366f1; color:#6366f1; }

.brand-table { width:100%; border-collapse:collapse; }
.brand-table thead tr { background:#f8faff; border-bottom:1px solid #e8ecf4; }
.brand-table th { padding:12px 16px; text-align:left; font-size:10.5px; font-weight:700; color:#94a3b8; letter-spacing:.6px; text-transform:uppercase; }
.brand-row { border-bottom:1px solid #f8fafc; transition:background .12s; }
.brand-row:last-child { border-bottom:none; }
.brand-row:hover { background:#fafbfd; }
.brand-table td { padding:13px 16px; vertical-align:middle; }
.brand-logo { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:13px; letter-spacing:.5px; }
.brand-name { font-size:13.5px; font-weight:700; color:#1e293b; }
.brand-location { font-size:11.5px; color:#94a3b8; margin-top:2px; }
.product-count { font-size:15px; font-weight:700; color:#334155; }
.product-label { font-size:10.5px; color:#94a3b8; margin-top:1px; }
.website-link { display:inline-flex; align-items:center; gap:4px; font-size:12px; color:#6366f1; font-weight:500; text-decoration:none; }
.website-link:hover { text-decoration:underline; }
.website-link svg { width:11px; height:11px; }
.no-website { font-size:13px; color:#cbd5e1; }
.status-badge { display:inline-flex; align-items:center; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:600; gap:4px; }
.status-active { background:#dcfce7; color:#16a34a; }
.status-paused { background:#fef9c3; color:#ca8a04; }
.action-btns { display:flex; gap:6px; }
.action-btn { width:30px; height:30px; border-radius:8px; border:1px solid #e4e8f0; background:#f8fafc; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .12s; }
.action-btn svg { width:14px; height:14px; }
.edit-btn { color:#6366f1; }
.edit-btn:hover { background:#eef2ff; border-color:#c7d2fe; }
.delete-btn { color:#ef4444; }
.delete-btn:hover { background:#fef2f2; border-color:#fecaca; }
.empty-row { text-align:center; padding:32px; color:#94a3b8; font-size:13px; }
.table-footer { display:flex; align-items:center; justify-content:space-between; padding:13px 18px; border-top:1px solid #f1f5f9; }
.table-info { font-size:12px; color:#94a3b8; }
.pagination { display:flex; gap:5px; align-items:center; }
.page-btn { width:30px; height:30px; border-radius:8px; border:1px solid #e4e8f0; background:#fff; font-size:12.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .12s; font-family:inherit; }
.page-btn svg { width:13px; height:13px; }
.page-btn:hover:not(:disabled) { border-color:#6366f1; color:#6366f1; }
.page-btn.active { background:#6366f1; border-color:#6366f1; color:#fff; }
.page-btn:disabled { opacity:.3; cursor:default; }

/* PROMO BANNER */
.promo-banner { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); border-radius:20px; padding:30px 36px; display:flex; align-items:center; justify-content:space-between; position:relative; overflow:hidden; }
.promo-tag { display:inline-block; font-size:10.5px; font-weight:700; letter-spacing:1px; color:rgba(255,255,255,.7); margin-bottom:8px; }
.promo-title { font-size:22px; font-weight:800; color:#fff; line-height:1.3; margin-bottom:10px; }
.promo-desc { font-size:13px; color:rgba(255,255,255,.75); max-width:400px; line-height:1.65; margin-bottom:18px; }
.btn-outline { background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.4); color:#fff; padding:9px 20px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; backdrop-filter:blur(4px); }
.btn-outline:hover { background:rgba(255,255,255,.25); }
.promo-visual { position:relative; width:120px; height:90px; flex-shrink:0; }
.medal { position:absolute; top:0; right:10px; font-size:56px; filter:drop-shadow(0 4px 12px rgba(0,0,0,.2)); }
.star { position:absolute; color:rgba(255,255,255,.3); }
.s1 { bottom:0; left:0; font-size:30px; }
.s2 { top:10px; left:20px; font-size:18px; }
.s3 { bottom:10px; right:30px; font-size:14px; }

/* ══ MODAL ══ */
.modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:100; padding:16px; }
.modal { background:#fff; border-radius:20px; width:100%; max-width:580px; max-height:90vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,.18); display:flex; flex-direction:column; }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:20px 24px 18px; border-bottom:1px solid #f1f5f9; }
.modal-header-left { display:flex; align-items:center; gap:12px; }
.modal-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.modal-icon svg { width:18px; height:18px; }
.icon-add { background:linear-gradient(135deg,#6366f1,#4f46e5); }
.icon-edit { background:linear-gradient(135deg,#f59e0b,#f97316); }
.modal-title { font-size:16px; font-weight:800; color:#0f172a; }
.modal-subtitle { font-size:12px; color:#94a3b8; margin-top:2px; }
.modal-close { width:32px; height:32px; border:1px solid #e4e8f0; border-radius:9px; background:#f8fafc; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .12s; }
.modal-close svg { width:14px; height:14px; }
.modal-close:hover { background:#fef2f2; border-color:#fecaca; color:#ef4444; }
.modal-body { padding:20px 24px; flex:1; overflow-y:auto; display:flex; flex-direction:column; gap:18px; }
.modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:15px 24px; border-top:1px solid #f1f5f9; background:#fafbff; }
.btn-cancel { padding:10px 18px; border-radius:10px; border:1.5px solid #e4e8f0; background:#fff; color:#64748b; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .12s; }
.btn-cancel:hover { background:#f8fafc; }

/* Preview row */
.preview-row { display:flex; align-items:center; gap:16px; background:#f8fafc; border-radius:14px; padding:16px; border:1px solid #e8ecf4; }
.logo-preview { width:58px; height:58px; border-radius:14px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:18px; flex-shrink:0; transition:background .2s; letter-spacing:.5px; }
.preview-name { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:2px; }
.preview-loc { font-size:12px; color:#94a3b8; margin-bottom:10px; }
.color-swatches { display:flex; gap:7px; flex-wrap:wrap; align-items:center; }
.swatch { width:22px; height:22px; border-radius:6px; cursor:pointer; border:2px solid transparent; transition:transform .1s, border-color .1s; }
.swatch:hover { transform:scale(1.18); }
.swatch.selected { border-color:#6366f1; transform:scale(1.1); box-shadow:0 0 0 2px #fff,0 0 0 4px #6366f1; }
.color-custom { width:22px; height:22px; border-radius:6px; border:2px solid #e4e8f0; cursor:pointer; padding:0; background:none; }

/* Form */
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.field { display:flex; flex-direction:column; gap:5px; }
.field.full { grid-column:span 2; }
.field-label { font-size:12px; font-weight:600; color:#374151; }
.req { color:#ef4444; }
.field-input { border:1.5px solid #e4e8f0; border-radius:10px; padding:10px 12px; font-size:13px; color:#1e293b; font-family:inherit; outline:none; background:#fff; transition:border-color .15s,box-shadow .15s; width:100%; }
.field-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.field-input.err { border-color:#ef4444; }
.field-textarea { resize:vertical; min-height:72px; }
.mono { font-family:monospace; font-size:14px; letter-spacing:.5px; }
.err-msg { font-size:11px; color:#ef4444; font-weight:500; }

/* Input prefix */
.input-prefix-wrap { display:flex; border:1.5px solid #e4e8f0; border-radius:10px; overflow:hidden; transition:border-color .15s,box-shadow .15s; }
.input-prefix-wrap:focus-within { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.input-prefix { padding:10px 12px; background:#f4f6fb; font-size:12.5px; color:#94a3b8; border-right:1.5px solid #e4e8f0; white-space:nowrap; font-family:monospace; }
.prefix-field { border:none !important; border-radius:0 !important; box-shadow:none !important; flex:1; }
.prefix-field:focus { border:none; box-shadow:none; }

/* Toggle status */
.toggle-group { display:flex; gap:7px; }
.toggle-btn { display:flex; align-items:center; gap:7px; padding:8px 14px; border-radius:9px; border:1.5px solid #e4e8f0; background:#f8fafc; font-size:12.5px; font-weight:500; color:#64748b; cursor:pointer; font-family:inherit; transition:all .15s; flex:1; justify-content:center; }
.tdot { width:7px; height:7px; border-radius:50%; background:#cbd5e1; flex-shrink:0; transition:background .15s; }
.toggle-active-green { border-color:#16a34a; background:#f0fdf4; color:#15803d; }
.toggle-active-green .tdot { background:#16a34a; }
.toggle-active-yellow { border-color:#ca8a04; background:#fefce8; color:#a16207; }
.toggle-active-yellow .tdot { background:#ca8a04; }

/* TOAST */
.toast { position:fixed; bottom:28px; left:50%; transform:translateX(-50%); background:#0f172a; color:#fff; padding:12px 22px; border-radius:12px; font-size:13.5px; font-weight:600; display:flex; align-items:center; gap:8px; z-index:200; box-shadow:0 8px 28px rgba(0,0,0,.2); }
.toast svg { width:15px; height:15px; }
.toast-success svg { stroke:#4ade80; }
.toast-error { background:#ef4444; }

/* Transitions */
.fade-enter-active,.fade-leave-active { transition:opacity .22s ease; }
.fade-enter-from,.fade-leave-to { opacity:0; }
.slide-up-enter-active { transition:all .3s cubic-bezier(.34,1.56,.64,1); }
.slide-up-leave-active { transition:all .2s ease; }
.slide-up-enter-from { opacity:0; transform:translateY(28px) scale(.97); }
.slide-up-leave-to { opacity:0; transform:translateY(8px) scale(.98); }
.toast-enter-active,.toast-leave-active { transition:all .25s ease; }
.toast-enter-from,.toast-leave-to { opacity:0; transform:translateX(-50%) translateY(12px); }

a { text-decoration:none; }
</style>