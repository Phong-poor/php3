<script setup>
import { ref } from 'vue'

const showForm = ref(false)
const editingIndex = ref(null)
const saving = ref(false)
const saved = ref(false)

const defaultForm = () => ({
    name: '', phone: '', province: '', district: '', ward: '', detail: '', isDefault: false
})

const form = ref(defaultForm())

const addresses = ref([
    {
        id: 1, name: 'Nguyễn Văn A', phone: '0901 234 567',
        province: 'TP. Hồ Chí Minh', district: 'Quận 1', ward: 'Phường Bến Nghé',
        detail: '123 Lê Lợi', isDefault: true
    },
    {
        id: 2, name: 'Nguyễn Văn A', phone: '0912 345 678',
        province: 'Hà Nội', district: 'Quận Cầu Giấy', ward: 'Phường Dịch Vọng',
        detail: '45 Nguyễn Phong Sắc', isDefault: false
    },
])

const provinces = ['TP. Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng', 'Biên Hòa', 'Buôn Ma Thuột']

const openAdd = () => {
    form.value = defaultForm()
    editingIndex.value = null
    showForm.value = true
}

const openEdit = (i) => {
    form.value = { ...addresses.value[i] }
    editingIndex.value = i
    showForm.value = true
}

const cancel = () => { showForm.value = false }

const save = async () => {
    saving.value = true
    await new Promise(r => setTimeout(r, 800))
    if (editingIndex.value !== null) {
        addresses.value[editingIndex.value] = { ...form.value }
    } else {
        addresses.value.push({ ...form.value, id: Date.now(), isDefault: addresses.value.length === 0 })
    }
    saving.value = false
    showForm.value = false
    saved.value = true
    setTimeout(() => saved.value = false, 2500)
}

const setDefault = (i) => {
    addresses.value = addresses.value.map((a, idx) => ({ ...a, isDefault: idx === i }))
}

const remove = (i) => {
    addresses.value.splice(i, 1)
}
</script>

<template>
    <div class="page">
        <transition name="toast">
            <div class="toast" v-if="saved">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                Địa chỉ đã được cập nhật!
            </div>
        </transition>

        <div class="container">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Địa chỉ của tôi</h1>
                    <p class="page-sub">Quản lý địa chỉ giao hàng</p>
                </div>
                <button class="btn-add" @click="openAdd" v-if="!showForm">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Thêm địa chỉ mới
                </button>
            </div>

            <!-- Form -->
            <transition name="slide">
                <div class="card form-card" v-if="showForm">
                    <h2 class="form-title">{{ editingIndex !== null ? 'Chỉnh sửa địa chỉ' : 'Thêm địa chỉ mới' }}</h2>
                    <form @submit.prevent="save" class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên người nhận</label>
                            <input v-model="form.name" type="text" placeholder="Nhập họ tên" required />
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input v-model="form.phone" type="tel" placeholder="Nhập số điện thoại" required />
                        </div>
                        <div class="form-group">
                            <label>Tỉnh / Thành phố</label>
                            <select v-model="form.province" required>
                                <option value="" disabled>Chọn tỉnh/thành</option>
                                <option v-for="p in provinces" :key="p" :value="p">{{ p }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quận / Huyện</label>
                            <input v-model="form.district" type="text" placeholder="Nhập quận/huyện" required />
                        </div>
                        <div class="form-group">
                            <label>Phường / Xã</label>
                            <input v-model="form.ward" type="text" placeholder="Nhập phường/xã" required />
                        </div>
                        <div class="form-group form-full">
                            <label>Địa chỉ chi tiết</label>
                            <input v-model="form.detail" type="text" placeholder="Số nhà, tên đường..." required />
                        </div>
                        <div class="form-group form-full checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="form.isDefault" />
                                <span>Đặt làm địa chỉ mặc định</span>
                            </label>
                        </div>
                        <div class="form-actions form-full">
                            <button type="button" class="btn-cancel" @click="cancel">Hủy</button>
                            <button type="submit" class="btn-save" :disabled="saving">
                                <svg v-if="saving" class="spin" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg>
                                {{ saving ? 'Đang lưu...' : 'Lưu địa chỉ' }}
                            </button>
                        </div>
                    </form>
                </div>
            </transition>

            <!-- Address list -->
            <div class="addr-list">
                <div v-if="addresses.length === 0 && !showForm" class="empty">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <p>Chưa có địa chỉ nào. Hãy thêm địa chỉ giao hàng!</p>
                </div>

                <div class="addr-card" v-for="(addr, i) in addresses" :key="addr.id"
                    :class="{ 'is-default': addr.isDefault }">
                    <div class="addr-head">
                        <div class="addr-name-wrap">
                            <span class="addr-name">{{ addr.name }}</span>
                            <span class="addr-sep">|</span>
                            <span class="addr-phone">{{ addr.phone }}</span>
                        </div>
                        <span class="default-badge" v-if="addr.isDefault">Mặc định</span>
                    </div>
                    <p class="addr-full">{{ addr.detail }}, {{ addr.ward }}, {{ addr.district }}, {{ addr.province }}
                    </p>
                    <div class="addr-actions">
                        <button class="addr-btn" @click="openEdit(i)">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                            Chỉnh sửa
                        </button>
                        <button class="addr-btn addr-btn-default" v-if="!addr.isDefault" @click="setDefault(i)">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                            Đặt mặc định
                        </button>
                        <button class="addr-btn addr-btn-delete" @click="remove(i)">
                            <svg viewBox="0 0 24 24" fill="none">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14H6L5 6" />
                                <path d="M10 11v6M14 11v6" />
                                <path d="M9 6V4h6v2" />
                            </svg>
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page {
    min-height: 100vh;
    background: #f8fafc;
    padding: 32px 30px;
    font-family: system-ui, sans-serif;
}

.container {
    max-width: 860px;
    margin: auto;
}

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
}

.page-sub {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.btn-add {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    background: #2563eb;
    border: none;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-add:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-add svg {
    width: 16px;
    height: 16px;
    stroke: #fff;
    stroke-width: 2.5;
    fill: none;
}

/* FORM CARD */
.form-card {
    margin-bottom: 20px;
}

.card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    padding: 28px 32px;
}

.form-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 22px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-full {
    grid-column: 1/-1;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select {
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.checkbox-group {}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #2563eb;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 4px;
}

.btn-cancel {
    padding: 10px 22px;
    border-radius: 10px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #374151;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

.btn-save {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border-radius: 10px;
    background: #2563eb;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-save:hover {
    background: #1d4ed8;
}

.btn-save:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.spin {
    width: 15px;
    height: 15px;
    stroke: #fff;
    stroke-width: 2.5;
    fill: none;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ADDRESS LIST */
.addr-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.empty {
    text-align: center;
    padding: 60px 0;
    color: #94a3b8;
}

.empty svg {
    width: 48px;
    height: 48px;
    stroke: #cbd5e1;
    stroke-width: 1.5;
    fill: none;
    margin-bottom: 12px;
}

.empty p {
    font-size: 14px;
}

.addr-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #e5e7eb;
    padding: 18px 22px;
    transition: border-color 0.2s;
}

.addr-card.is-default {
    border-color: #bfdbfe;
}

.addr-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.addr-name-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.addr-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}

.addr-sep {
    color: #cbd5e1;
}

.addr-phone {
    font-size: 13px;
    color: #64748b;
}

.default-badge {
    font-size: 11px;
    font-weight: 700;
    color: #2563eb;
    background: #dbeafe;
    padding: 3px 10px;
    border-radius: 99px;
}

.addr-full {
    font-size: 13px;
    color: #374151;
    margin: 0 0 14px;
    line-height: 1.5;
}

.addr-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.addr-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #374151;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}

.addr-btn svg {
    width: 13px;
    height: 13px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.addr-btn:hover {
    background: #f1f5f9;
}

.addr-btn-default:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}

.addr-btn-delete:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

/* TOAST */
.toast {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    background: #0f172a;
    color: #fff;
    padding: 12px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.toast svg {
    width: 18px;
    height: 18px;
    stroke: #4ade80;
    stroke-width: 2.5;
    fill: none;
}

.toast-enter-active {
    transition: all 0.3s cubic-bezier(0.34, 1.4, 0.64, 1);
}

.toast-leave-active {
    transition: all 0.2s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateY(-12px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

.slide-enter-active {
    transition: all 0.25s ease;
}

.slide-leave-active {
    transition: all 0.2s ease;
}

.slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.slide-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>