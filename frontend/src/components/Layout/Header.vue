<script setup>
</script>

<template>
  <!-- TOP BAR -->
  <div class="topbar">
    <div class="topbar-container">
      <span class="topbar-message">
        Chào mừng đến với <strong>NextGen Laptop 2026</strong> — Giao hàng nhanh chỉ trong 2 giờ nội thành
      </span>
      <div class="topbar-right">
        <span class="topbar-item">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.95-.95a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
          1800 9999
        </span>
        <span class="topbar-divider">|</span>
        <span class="topbar-item">
          <svg viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 2a14.5 14.5 0 0 1 0 20A14.5 14.5 0 0 1 12 2"/>
            <path d="M2 12h20"/>
          </svg>
          Việt Nam | VNĐ
        </span>
      </div>
    </div>
  </div>

  <!-- MAIN HEADER -->
  <header class="header">
    <div class="container">

      <!-- LOGO -->
      <div class="logo">
        <span class="logo-black">NextGen</span>
        <span class="logo-blue">Laptop</span>
      </div>

      <!-- MENU -->
      <nav class="nav">
        <router-link to="/" :exact="true">Trang chủ</router-link>
        <router-link to="/products">Sản phẩm</router-link>
        <router-link to="/news">Tin tức</router-link>
        <router-link to="/contact">Liên hệ</router-link>
      </nav>

      <!-- RIGHT -->
      <div class="right">

        <!-- SEARCH -->
        <div class="search">
          <input type="text" placeholder="Tìm kiếm sản phẩm..." />
          <svg viewBox="0 0 24 24" fill="none">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
          </svg>
        </div>

        <!-- WISHLIST -->
        <router-link to="/wishlist" class="icon-btn">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
          <span class="badge badge-red">0</span>
        </router-link>

        <!-- CART -->
        <router-link to="/cart" class="icon-btn">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 6h15l-1.5 9h-13z" />
            <circle cx="9" cy="20" r="1" />
            <circle cx="18" cy="20" r="1" />
          </svg>
          <span class="badge badge-blue">3</span>
        </router-link>

        <!-- USER -->
        <router-link to="/profile" class="icon-btn icon-btn-user">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
        </router-link>

      </div>

    </div>
  </header>
</template>

<style scoped>
/* ───────────────────────────── TOP BAR ───────────────────────────── */
.topbar {
  background: #0f172a;
  color: #cbd5e1;
  font-size: 13px;
  height: 36px;
  display: flex;
  align-items: center;
}

.topbar-container {
  max-width: 1300px;
  width: 100%;
  margin: auto;
  padding: 0 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.topbar-message {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #94a3b8;
  letter-spacing: 0.2px;
}

.topbar-message strong {
  color: #e2e8f0;
  font-weight: 600;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.topbar-item {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #94a3b8;
  cursor: pointer;
  transition: color 0.2s;
}

.topbar-item:hover {
  color: #e2e8f0;
}

.topbar-item svg {
  width: 13px;
  height: 13px;
  stroke: currentColor;
  stroke-width: 2;
  fill: none;
}

.topbar-divider {
  color: #334155;
}

/* ───────────────────────────── MAIN HEADER ───────────────────────────── */
.header {
  background: #ffffff;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 1px 8px rgba(0,0,0,0.05);
}

.container {
  max-width: 1300px;
  margin: auto;
  padding: 0 30px;
  height: 68px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* LOGO */
.logo {
  font-size: 21px;
  font-weight: 800;
  letter-spacing: 0.5px;
  user-select: none;
}

.logo-black {
  color: #0f172a;
}

.logo-blue {
  color: #2563eb;
}

/* NAV */
.nav {
  display: flex;
  gap: 28px;
}

.nav a {
  text-decoration: none;
  color: #6b7280;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.4px;
  text-transform: uppercase;
  transition: color 0.2s;
  position: relative;
  padding-bottom: 4px;
}

.nav a:hover {
  color: #2563eb;
}

.nav a.router-link-exact-active {
  color: #2563eb;
}

.nav a.router-link-exact-active::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  right: 0;
  height: 2px;
  background: #2563eb;
  border-radius: 2px;
}

/* RIGHT */
.right {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* SEARCH */
.search {
  display: flex;
  align-items: center;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 999px;
  padding: 6px 14px;
  width: 230px;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.search:focus-within {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  background: #fff;
}

.search input {
  border: none;
  background: transparent;
  outline: none;
  font-size: 13px;
  flex: 1;
  color: #1e293b;
}

.search input::placeholder {
  color: #94a3b8;
}

.search svg {
  width: 15px;
  height: 15px;
  stroke: #94a3b8;
  stroke-width: 2;
  flex-shrink: 0;
}

/* ICON BTN */
.icon-btn {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f1f5f9;
  cursor: pointer;
  position: relative;
  transition: background 0.2s, transform 0.15s;
  text-decoration: none;
}

.icon-btn:hover {
  background: #e2e8f0;
  transform: translateY(-1px);
}

.icon-btn svg {
  width: 18px;
  height: 18px;
  stroke: #475569;
  stroke-width: 1.8;
  fill: none;
}

.icon-btn-user {
  background: #2563eb;
}

.icon-btn-user svg {
  stroke: #ffffff;
}

.icon-btn-user:hover {
  background: #1d4ed8;
}

/* BADGE */
.badge {
  position: absolute;
  top: -4px;
  right: -4px;
  width: 18px;
  height: 18px;
  color: white;
  font-size: 10px;
  font-weight: 700;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #fff;
}

.badge-blue {
  background: #2563eb;
}

.badge-red {
  background: #ef4444;
}
</style>