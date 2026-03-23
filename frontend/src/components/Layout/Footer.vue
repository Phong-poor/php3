<script setup>
</script>

<template>
  <footer class="footer">

    <div class="container">

      <!-- GRID -->
      <div class="grid">

        <!-- LEFT -->
        <div class="col brand">
          <h3>VinaTech Premium</h3>

          <p>
            Dẫn đầu kỷ nguyên công nghệ số 2026 với những giải pháp tối ưu
            và trải nghiệm người dùng đẳng cấp.
          </p>

          <div class="social">
            <span>↗</span>
          </div>
        </div>

        <!-- COL -->
        <div class="col">
          <h4>Khám phá</h4>
          <ul>
            <li>Thông tin công ty</li>
            <li>Sản phẩm nổi bật</li>
            <li>Tin tức công nghệ</li>
          </ul>
        </div>

        <!-- COL -->
        <div class="col">
          <h4>Hỗ trợ</h4>
          <ul>
            <li>Chính sách bảo hành</li>
            <li>Điều khoản dịch vụ</li>
            <li>Câu hỏi thường gặp</li>
          </ul>
        </div>

        <!-- COL -->
        <div class="col newsletter">
          <h4>Bản tin</h4>

          <p>
            Đăng ký nhận tin mới nhất từ VinaTech.
          </p>

          <div class="subscribe">
            <input placeholder="Email của bạn" />
            <button>Đăng ký</button>
          </div>
        </div>

      </div>

      <!-- LINE -->
      <div class="divider"></div>

      <!-- BOTTOM -->
      <div class="bottom">

        <div class="copy">
          © 2026 VinaTech Premium. All rights reserved.
        </div>

        <div class="links">
          <span>Privacy Policy</span>
          <span>Cookie Settings</span>
        </div>

      </div>

    </div>

  </footer>
</template>

<style scoped>
.footer {
  background: #c8c8c9;
  border-top: 1px solid #e2e8f0;
  padding: 50px 0;
}

/* CONTAINER */
.container {
  max-width: 1300px;
  margin: auto;
  padding: 0 30px;
}

/* GRID */
.grid {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
  gap: 40px;
}

/* BRAND */
.brand h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 14px;
}

.brand p {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
  max-width: 300px;
}

/* SOCIAL */
.social {
  margin-top: 20px;
}

.social span {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: #e2e8f0;

  display: flex;
  align-items: center;
  justify-content: center;

  cursor: pointer;
  transition: 0.2s;
}

.social span:hover {
  background: #cbd5f5;
}

/* COLUMN */
.col h4 {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 14px;
}

.col ul {
  list-style: none;
  padding: 0;
}

.col li {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 10px;
  cursor: pointer;
  transition: 0.2s;
}

.col li:hover {
  color: #2563eb;
}

/* NEWSLETTER */
.newsletter p {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 12px;
}

/* SUBSCRIBE */
.subscribe {
  display: flex;
  gap: 10px;
}

.subscribe input {
  flex: 1;
  padding: 10px 14px;
  border-radius: 999px;
  border: none;
  background: #e2e8f0;
  font-size: 13px;
}

.subscribe button {
  background: #2563eb;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 999px;
  font-size: 13px;
  cursor: pointer;
  transition: 0.2s;
}

.subscribe button:hover {
  background: #1d4ed8;
}

/* DIVIDER */
.divider {
  height: 1px;
  background: #e2e8f0;
  margin: 40px 0 20px;
}

/* BOTTOM */
.bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.copy {
  font-size: 13px;
  color: #94a3b8;
}

.links {
  display: flex;
  gap: 20px;
}

.links span {
  font-size: 13px;
  color: #94a3b8;
  cursor: pointer;
  transition: 0.2s;
}

.links span:hover {
  color: #2563eb;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }

  .bottom {
    flex-direction: column;
    gap: 10px;
  }
}
</style>