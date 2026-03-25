<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const searchQuery = ref('')
const activeTab = ref('Tất cả')
const showModal = ref(false)
const showEditModal = ref(false)
const formError = ref('')
const editError = ref('')

const users = ref([])
const loading = ref(false)

// ✅ LOAD DATA từ API
const fetchUsers = async () => {
    try {
        loading.value = true
        const res = await axios.get('http://127.0.0.1:8000/api/users')
        users.value = res.data.map(u => ({
            id: u.id,
            name: u.name,
            email: u.email,
            phone: u.phone || '',
            // ✅ Map role từ DB: 'admin' -> 'ADMIN', 'user' -> 'KHÁCH HÀNG'
            role: mapRoleFromDB(u.role),
            joined: new Date(u.created_at).toLocaleDateString('vi-VN'),
            status: u.status === 'locked' ? 'Bị khóa' : 'Hoạt động'
        }))
    } catch (err) {
        console.log('Lỗi load users:', err)
    } finally {
        loading.value = false
    }
}

// ✅ Map role từ backend sang display
const mapRoleFromDB = (role) => {
    if (!role) return 'KHÁCH HÀNG'
    const r = role.toLowerCase()
    if (r === 'admin') return 'ADMIN'
    if (r === 'support' || r === 'hỗ trợ') return 'HỖ TRỢ'
    return 'KHÁCH HÀNG'
}

// ✅ Map role từ display sang backend
const mapRoleToDB = (role) => {
    if (role === 'ADMIN') return 'admin'
    if (role === 'HỖ TRỢ') return 'support'
    return 'user'
}

onMounted(fetchUsers)

const tabs = ['Tất cả', 'Admin', 'Khách hàng', 'Hỗ trợ']

const roleStyle = {
    'ADMIN': { bg: '#dbeafe', color: '#1d4ed8' },
    'KHÁCH HÀNG': { bg: '#dcfce7', color: '#15803d' },
    'HỖ TRỢ': { bg: '#ede9fe', color: '#6d28d9' },
}

const statusStyle = {
    'Hoạt động': { color: '#16a34a' },
    'Bị khóa': { color: '#dc2626' },
}

const avatarColors = ['#dbeafe', '#dcfce7', '#ede9fe', '#fef9c3', '#fee2e2', '#ffedd5']
const avatarText = ['#1d4ed8', '#15803d', '#6d28d9', '#a16207', '#b91c1c', '#c2410c']

const getAvatarStyle = (name) => {
    const i = (name || 'A').charCodeAt(0) % avatarColors.length
    return { background: avatarColors[i], color: avatarText[i] }
}

const initials = (name) =>
    name?.trim().split(' ').map(w => w[0]).slice(-2).join('').toUpperCase()

// ✅ FILTER
const filtered = computed(() =>
    users.value.filter(u => {
        const matchSearch =
            u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchTab =
            activeTab.value === 'Tất cả' ||
            (activeTab.value === 'Admin' && u.role === 'ADMIN') ||
            (activeTab.value === 'Khách hàng' && u.role === 'KHÁCH HÀNG') ||
            (activeTab.value === 'Hỗ trợ' && u.role === 'HỖ TRỢ')
        return matchSearch && matchTab
    })
)

// ✅ TOGGLE STATUS - gọi API
const toggleStatus = async (u) => {
    const newStatus = u.status === 'Hoạt động' ? 'locked' : 'active'
    try {
        await axios.put(`http://127.0.0.1:8000/api/users/${u.id}`, {
            status: newStatus
        })
        u.status = u.status === 'Hoạt động' ? 'Bị khóa' : 'Hoạt động'
    } catch (err) {
        console.log('Toggle status lỗi:', err)
        // Nếu chưa có API status, chỉ toggle local
        u.status = u.status === 'Hoạt động' ? 'Bị khóa' : 'Hoạt động'
    }
}

// ✅ DELETE - dùng u.id thay vì index
const removeUser = async (userId) => {
    if (!confirm('Bạn có chắc muốn xóa người dùng này?')) return
    try {
        await axios.delete(`http://127.0.0.1:8000/api/users/${userId}`)
        users.value = users.value.filter(u => u.id !== userId)
    } catch (err) {
        console.log('Xóa lỗi:', err)
    }
}

// ─── CREATE MODAL ───────────────────────────────────────────
const defaultForm = () => ({
    name: '', email: '', phone: '', role: 'KHÁCH HÀNG',
    status: 'Hoạt động', password: ''
})
const form = ref(defaultForm())

const openModal = () => {
    form.value = defaultForm()
    formError.value = ''
    showModal.value = true
}
const closeModal = () => { showModal.value = false }

// ✅ CREATE USER - gửi role lên backend
const submitForm = async () => {
    if (!form.value.name.trim()) { formError.value = 'Vui lòng nhập họ tên.'; return }
    if (!form.value.email.trim()) { formError.value = 'Vui lòng nhập email.'; return }
    if (!form.value.password.trim()) { formError.value = 'Vui lòng nhập mật khẩu.'; return }
    if (form.value.password.length < 8) { formError.value = 'Mật khẩu tối thiểu 8 ký tự.'; return }

    try {
        const res = await axios.post('http://127.0.0.1:8000/api/register', {
            name: form.value.name,
            email: form.value.email,
            password: form.value.password,
            password_confirmation: form.value.password,
            role: mapRoleToDB(form.value.role), // ✅ gửi role
            phone: form.value.phone
        })

        const newUser = res.data.user
        users.value.unshift({
            id: newUser.id,
            name: newUser.name,
            email: newUser.email,
            phone: newUser.phone || '',
            role: form.value.role,
            joined: new Date().toLocaleDateString('vi-VN'),
            status: 'Hoạt động'
        })
        closeModal()
    } catch (err) {
        const msg = err.response?.data?.message || 'Tạo user thất bại'
        formError.value = msg
    }
}

// ─── EDIT MODAL ──────────────────────────────────────────────
const editingUser = ref(null)
const editForm = ref({})

const openEditModal = (u) => {
    editingUser.value = u
    editForm.value = {
        name: u.name,
        email: u.email,
        phone: u.phone || '',
        role: u.role,
        status: u.status,
        password: ''
    }
    editError.value = ''
    showEditModal.value = true
}
const closeEditModal = () => { showEditModal.value = false; editingUser.value = null }

// ✅ UPDATE USER
const submitEdit = async () => {
    if (!editForm.value.name.trim()) { editError.value = 'Vui lòng nhập họ tên.'; return }
    if (!editForm.value.email.trim()) { editError.value = 'Vui lòng nhập email.'; return }
    if (editForm.value.password && editForm.value.password.length < 8) {
        editError.value = 'Mật khẩu tối thiểu 8 ký tự.'; return
    }

    const payload = {
        name: editForm.value.name,
        email: editForm.value.email,
        phone: editForm.value.phone,
        role: mapRoleToDB(editForm.value.role),
        status: editForm.value.status === 'Hoạt động' ? 'active' : 'locked',
    }
    if (editForm.value.password) payload.password = editForm.value.password

    try {
        await axios.put(`http://127.0.0.1:8000/api/users/${editingUser.value.id}`, payload)

        // ✅ Cập nhật local
        const idx = users.value.findIndex(u => u.id === editingUser.value.id)
        if (idx !== -1) {
            users.value[idx] = {
                ...users.value[idx],
                name: editForm.value.name,
                email: editForm.value.email,
                phone: editForm.value.phone,
                role: editForm.value.role,
                status: editForm.value.status,
            }
        }
        closeEditModal()
    } catch (err) {
        const msg = err.response?.data?.message || 'Cập nhật thất bại'
        editError.value = msg
    }
}
</script>

<template>
    <div class="page">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input v-model="searchQuery" placeholder="Tìm kiếm người dùng, email hoặc vai trò..." />
            </div>
            <div class="topbar-right">
                <button class="icon-btn">🔔</button>
                <button class="icon-btn">🌙</button>
                <button class="icon-btn">❓</button>
                <div class="admin-wrap">
                    <div class="admin-text">
                        <b>Admin VinaTech</b>
                        <span>Quản trị viên cao cấp</span>
                    </div>
                    <div class="av-circle">AV</div>
                </div>
            </div>
        </div>

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h1>Quản lý người dùng</h1>
                <p>Theo dõi, điều chỉnh quyền hạn và trạng thái hoạt động của toàn bộ thành viên trong hệ thống
                    VinaTech.</p>
            </div>
            <button class="btn-new" @click="openModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="19" y1="8" x2="19" y2="14" />
                    <line x1="22" y1="11" x2="16" y2="11" />
                </svg>
                Thêm người dùng mới
            </button>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-info">
                    <p>TỔNG NGƯỜI DÙNG</p>
                    <div class="stat-val-row">
                        <b>{{ users.length }}</b>
                        <span class="badge-up">+12%</span>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>ĐANG HOẠT ĐỘNG</p>
                    <div class="stat-val-row">
                        <b>{{users.filter(u => u.status === 'Hoạt động').length}}</b>
                        <span class="badge-neutral">Ổn định</span>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <p>BỊ KHÓA</p>
                    <div class="stat-val-row">
                        <b>{{users.filter(u => u.status === 'Bị khóa').length}}</b>
                        <span class="badge-down">-5%</span>
                    </div>
                </div>
            </div>
            <div class="stat-card dark-stat">
                <div class="stat-info">
                    <p>TĂNG TRƯỞNG THÁNG</p>
                    <b class="big-growth">2.4k</b>
                </div>
                <svg class="trend-svg" viewBox="0 0 80 40" fill="none">
                    <polyline points="0,35 15,28 30,30 45,18 60,22 75,8" stroke="#60a5fa" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
            </div>
        </div>

        <!-- FILTER ROW -->
        <div class="filter-row">
            <div class="tabs-group">
                <button v-for="t in tabs" :key="t" class="tab" :class="{ active: activeTab === t }"
                    @click="activeTab = t">{{ t }}</button>
                <div class="tab-divider"></div>
                <button class="tab status-tab">
                    Trạng thái: Tất cả
                    <svg viewBox="0 0 20 20" fill="none" style="width:12px;height:12px">
                        <path d="M5 7L10 12L15 7" stroke="currentColor" stroke-width="2" />
                    </svg>
                </button>
            </div>
            <div class="filter-actions">
                <button class="btn-filter">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        style="width:14px;height:14px">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                        <line x1="11" y1="18" x2="13" y2="18" />
                    </svg>
                    Bộ lọc nâng cao
                </button>
                <button class="btn-export">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        style="width:14px;height:14px">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Xuất báo cáo
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-wrap">
            <div v-if="loading" class="loading-row">Đang tải dữ liệu...</div>
            <table v-else>
                <thead>
                    <tr>
                        <th>NGƯỜI DÙNG</th>
                        <th>VAI TRÒ</th>
                        <th>NGÀY THAM GIA</th>
                        <th>TRẠNG THÁI</th>
                        <th>THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filtered.length === 0">
                        <td colspan="5" class="empty">Không tìm thấy người dùng nào.</td>
                    </tr>
                    <tr v-for="u in filtered" :key="u.id">
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar" :style="getAvatarStyle(u.name)">{{ initials(u.name) }}</div>
                                <div>
                                    <b>{{ u.name }}</b>
                                    <span>{{ u.email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge"
                                :style="{ background: roleStyle[u.role]?.bg, color: roleStyle[u.role]?.color }">
                                {{ u.role }}
                            </span>
                        </td>
                        <td class="date-cell">{{ u.joined }}</td>
                        <td>
                            <span class="status-dot" :style="{ color: statusStyle[u.status]?.color }">
                                ● {{ u.status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <!-- Xem (chỉ hiển thị, mở edit) -->
                                <button class="act-btn" title="Xem chi tiết" @click="openEditModal(u)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                                <!-- Sửa -->
                                <button class="act-btn" title="Chỉnh sửa" @click="openEditModal(u)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <!-- Khóa / Mở khóa -->
                                <button class="act-btn" :title="u.status === 'Hoạt động' ? 'Khóa tài khoản' : 'Mở khóa'"
                                    @click="toggleStatus(u)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </button>
                                <!-- ✅ Xóa - dùng u.id -->
                                <button class="act-btn danger" title="Xóa" @click="removeUser(u.id)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round">
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
        <div class="table-footer">
            <span class="showing">Hiển thị 1 – {{ filtered.length }} của {{ users.length }} người dùng</span>
            <div class="pagination">
                <button>‹</button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button class="dots">...</button>
                <button>›</button>
            </div>
        </div>

        <!-- BOTTOM CARDS -->
        <div class="bottom-grid">
            <div class="bottom-card">
                <div class="bottom-icon orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="bottom-text">
                    <h4>Yêu cầu đăng ký mới</h4>
                    <p>Hiện có 12 yêu cầu đăng ký đang chờ phê duyệt từ hệ thống VinaTech Partner.</p>
                    <div class="bottom-actions">
                        <button class="btn-primary-sm">Xem danh sách</button>
                        <button class="btn-ghost-sm">Để sau</button>
                    </div>
                </div>
            </div>
            <div class="bottom-card security-card">
                <div class="security-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <div class="bottom-text">
                    <h4>Bảo mật hệ thống</h4>
                    <p>Mọi tài khoản người dùng đều được ghi lại trong nhật ký hệ thống để đảm bảo an toàn.</p>
                </div>
            </div>
        </div>

        <!-- ─── MODAL TẠO MỚI ─── -->
        <Teleport to="body">
            <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
                <div class="modal">
                    <div class="modal-header">
                        <h3>Thêm người dùng mới</h3>
                        <button class="modal-close" @click="closeModal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>HỌ TÊN <span class="req">*</span></label>
                                <input v-model="form.name" placeholder="VD: Nguyễn Văn A" />
                            </div>
                            <div class="form-group">
                                <label>EMAIL <span class="req">*</span></label>
                                <input v-model="form.email" type="email" placeholder="VD: user@vinatech.com" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>SỐ ĐIỆN THOẠI</label>
                                <input v-model="form.phone" placeholder="VD: 0901234567" />
                            </div>
                            <div class="form-group">
                                <label>MẬT KHẨU <span class="req">*</span></label>
                                <input v-model="form.password" type="password" placeholder="Tối thiểu 8 ký tự" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>VAI TRÒ</label>
                                <select v-model="form.role">
                                    <option>KHÁCH HÀNG</option>
                                    <option>ADMIN</option>
                                    <option>HỖ TRỢ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>TRẠNG THÁI</label>
                                <select v-model="form.status">
                                    <option>Hoạt động</option>
                                    <option>Bị khóa</option>
                                </select>
                            </div>
                        </div>
                        <p v-if="formError" class="form-error">⚠ {{ formError }}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-cancel" @click="closeModal">Hủy</button>
                        <button class="btn-submit" @click="submitForm">Tạo người dùng</button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- ─── MODAL CHỈNH SỬA ─── -->
        <Teleport to="body">
            <div v-if="showEditModal" class="modal-overlay" @click.self="closeEditModal">
                <div class="modal">
                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <h3>Chỉnh sửa người dùng</h3>
                            <span class="modal-sub">ID: #{{ editingUser?.id }}</span>
                        </div>
                        <button class="modal-close" @click="closeEditModal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>HỌ TÊN <span class="req">*</span></label>
                                <input v-model="editForm.name" placeholder="Họ tên" />
                            </div>
                            <div class="form-group">
                                <label>EMAIL <span class="req">*</span></label>
                                <input v-model="editForm.email" type="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>SỐ ĐIỆN THOẠI</label>
                                <input v-model="editForm.phone" placeholder="Số điện thoại" />
                            </div>
                            <div class="form-group">
                                <label>MẬT KHẨU MỚI</label>
                                <input v-model="editForm.password" type="password"
                                    placeholder="Để trống nếu không đổi" />
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- ✅ Phân quyền -->
                            <div class="form-group">
                                <label>VAI TRÒ / PHÂN QUYỀN</label>
                                <select v-model="editForm.role">
                                    <option>KHÁCH HÀNG</option>
                                    <option>ADMIN</option>
                                    <option>HỖ TRỢ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>TRẠNG THÁI</label>
                                <select v-model="editForm.status">
                                    <option>Hoạt động</option>
                                    <option>Bị khóa</option>
                                </select>
                            </div>
                        </div>

                        <!-- Preview role badge -->
                        <div class="role-preview">
                            <span class="preview-label">Xem trước quyền hạn:</span>
                            <span class="role-badge"
                                :style="{ background: roleStyle[editForm.role]?.bg, color: roleStyle[editForm.role]?.color }">
                                {{ editForm.role }}
                            </span>
                            <span class="status-dot" :style="{ color: statusStyle[editForm.status]?.color }">
                                ● {{ editForm.status }}
                            </span>
                        </div>

                        <p v-if="editError" class="form-error">⚠ {{ editError }}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-cancel" @click="closeEditModal">Hủy</button>
                        <button class="btn-submit" @click="submitEdit">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </Teleport>

    </div>
</template>

<style scoped>
* {
    box-sizing: border-box;
}

.page {
    background: #f5f7fb;
    min-height: 100vh;
    font-family: sans-serif;
    padding-bottom: 40px;
}

/* TOPBAR */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 32px;
    background: white;
    border-bottom: 1px solid #f1f5f9;
}

.search-box {
    position: relative;
    width: 280px;
}

.search-box svg {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    color: #94a3b8;
    pointer-events: none;
}

.search-box input {
    width: 100%;
    padding: 8px 12px 8px 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    color: #0f172a;
    outline: none;
    background: #f8fafc;
}

.search-box input:focus {
    border-color: #2563eb;
    background: white;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 6px;
}

.icon-btn {
    background: none;
    border: none;
    font-size: 15px;
    cursor: pointer;
    padding: 6px 8px;
    border-radius: 8px;
}

.icon-btn:hover {
    background: #f1f5f9;
}

.admin-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 6px;
}

.admin-text b {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #0f172a;
}

.admin-text span {
    font-size: 10px;
    color: #94a3b8;
}

.av-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* PAGE HEADER */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px 32px 20px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
    letter-spacing: -0.02em;
}

.page-header p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    max-width: 480px;
    line-height: 1.6;
}

.btn-new {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    padding: 11px 20px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.2s;
}

.btn-new svg {
    width: 15px;
    height: 15px;
}

.btn-new:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* STATS */
.stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1.2fr;
    gap: 16px;
    padding: 0 32px 20px;
}

.stat-card {
    background: white;
    border-radius: 14px;
    border: 1px solid #f1f5f9;
    padding: 20px 22px;
}

.stat-info p {
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.08em;
    margin: 0 0 8px;
}

.stat-info b {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
}

.stat-val-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge-up {
    font-size: 11px;
    font-weight: 700;
    color: #16a34a;
    background: #dcfce7;
    padding: 3px 8px;
    border-radius: 20px;
}

.badge-down {
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    background: #fee2e2;
    padding: 3px 8px;
    border-radius: 20px;
}

.badge-neutral {
    font-size: 11px;
    font-weight: 700;
    color: #2563eb;
    background: #dbeafe;
    padding: 3px 8px;
    border-radius: 20px;
}

.dark-stat {
    background: linear-gradient(135deg, #1e3a5f, #0f172a);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
}

.dark-stat .stat-info p {
    color: rgba(255, 255, 255, 0.5);
}

.big-growth {
    font-size: 30px;
    font-weight: 800;
    color: white !important;
}

.trend-svg {
    width: 80px;
    height: 40px;
    opacity: 0.8;
}

/* FILTER ROW */
.filter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px 14px;
    flex-wrap: wrap;
    gap: 10px;
}

.tabs-group {
    display: flex;
    align-items: center;
    gap: 4px;
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 10px;
    padding: 4px 6px;
}

.tab {
    padding: 7px 14px;
    border-radius: 7px;
    border: none;
    background: transparent;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.tab:hover {
    background: #f8fafc;
    color: #334155;
}

.tab.active {
    background: #2563eb;
    color: white;
}

.tab-divider {
    width: 1px;
    height: 20px;
    background: #e2e8f0;
    margin: 0 4px;
}

.status-tab {
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-filter,
.btn-export {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: white;
    font-size: 13px;
    color: #334155;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-filter:hover,
.btn-export:hover {
    border-color: #2563eb;
    color: #2563eb;
}

/* TABLE */
.table-wrap {
    margin: 0 32px;
    background: white;
    border-radius: 14px;
    border: 1px solid #f1f5f9;
    overflow: hidden;
}

.loading-row {
    text-align: center;
    padding: 48px;
    color: #94a3b8;
    font-size: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead tr {
    background: #f8fafc;
}

thead th {
    padding: 12px 18px;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-align: left;
    letter-spacing: 0.07em;
    border-bottom: 1px solid #f1f5f9;
}

tbody tr {
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
}

tbody tr:last-child {
    border-bottom: none;
}

tbody tr:hover {
    background: #fafbff;
}

tbody td {
    padding: 16px 18px;
    font-size: 13px;
    vertical-align: middle;
}

.empty {
    text-align: center;
    color: #94a3b8;
    padding: 50px !important;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-cell b {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 2px;
}

.user-cell span {
    font-size: 12px;
    color: #94a3b8;
}

.role-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    letter-spacing: 0.04em;
}

.date-cell {
    color: #64748b;
    font-size: 13px;
}

.status-dot {
    font-size: 13px;
    font-weight: 600;
}

.actions {
    display: flex;
    gap: 5px;
}

.act-btn {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    transition: all 0.2s;
}

.act-btn svg {
    width: 13px;
    height: 13px;
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

/* FOOTER */
.table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 32px;
}

.showing {
    font-size: 13px;
    color: #64748b;
}

.pagination {
    display: flex;
    gap: 5px;
}

.pagination button {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: white;
    font-size: 13px;
    cursor: pointer;
    color: #334155;
    transition: all 0.2s;
}

.pagination button:hover {
    border-color: #2563eb;
    color: #2563eb;
}

.pagination .active {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
}

.pagination .dots {
    border: none;
    background: transparent;
    cursor: default;
}

/* BOTTOM GRID */
.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 20px 32px 0;
}

.bottom-card {
    background: white;
    border-radius: 14px;
    border: 1px solid #f1f5f9;
    padding: 22px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.bottom-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.bottom-icon svg {
    width: 22px;
    height: 22px;
}

.bottom-icon.orange {
    background: #fff7ed;
    color: #ea580c;
}

.bottom-text h4 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 6px;
}

.bottom-text p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.6;
    margin: 0 0 14px;
}

.bottom-actions {
    display: flex;
    gap: 8px;
}

.btn-primary-sm {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    background: #0f172a;
    color: white;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.btn-primary-sm:hover {
    opacity: 0.85;
}

.btn-ghost-sm {
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.btn-ghost-sm:hover {
    border-color: #94a3b8;
}

.security-card {
    background: #f8fafc;
}

.security-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
}

.security-icon svg {
    width: 24px;
    height: 24px;
}

.security-card .bottom-text p {
    margin: 0;
}

/* MODAL CHUNG */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 560px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
    animation: modalIn 0.22s cubic-bezier(.22, 1, .36, 1);
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: translateY(16px) scale(0.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.modal-title-wrap {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.modal-header h3 {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.modal-sub {
    font-size: 12px;
    color: #94a3b8;
}

.modal-close {
    background: none;
    border: none;
    font-size: 22px;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #0f172a;
}

.modal-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
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
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.08em;
}

.req {
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
    transition: border-color 0.2s;
    background: #f8fafc;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
}

.form-error {
    font-size: 12px;
    color: #ef4444;
    background: #fef2f2;
    border: 1px solid #fecaca;
    padding: 9px 12px;
    border-radius: 8px;
    margin: 0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px 20px;
    border-top: 1px solid #f1f5f9;
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
    transition: all 0.2s;
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
    transition: opacity 0.2s, transform 0.2s;
}

.btn-submit:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* ROLE PREVIEW (edit modal) */
.role-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
}

.preview-label {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .stats {
        grid-template-columns: 1fr 1fr;
    }

    .bottom-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .page-header {
        flex-direction: column;
        gap: 14px;
        padding: 16px;
    }

    .stats {
        padding: 0 16px 16px;
        grid-template-columns: 1fr 1fr;
    }

    .filter-row {
        padding: 0 16px 12px;
    }

    .table-wrap {
        margin: 0 16px;
        overflow-x: auto;
    }

    table {
        min-width: 640px;
    }

    .table-footer {
        padding: 12px 16px;
        flex-direction: column;
        gap: 10px;
    }

    .bottom-grid {
        padding: 16px 16px 0;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .topbar {
        padding: 12px 16px;
    }
}
</style>