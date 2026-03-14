@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
 
<style>
    * { font-family: 'DM Sans', sans-serif; }
    .display-font { font-family: 'DM Serif Display', serif; }
 
    body { background: #f7f5f2; }
 
    .grain-overlay::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 100;
        opacity: 0.4;
    }
 
    .card {
        background: white;
        border: 1px solid #ece9e4;
        border-radius: 16px;
        animation: fadeSlideUp 0.45s ease both;
    }
 
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
 
    .form-group { animation: fadeSlideUp 0.4s ease both; }
    .form-group:nth-child(1) { animation-delay: 0.10s; }
    .form-group:nth-child(2) { animation-delay: 0.17s; }
    .form-group:nth-child(3) { animation-delay: 0.24s; }
 
    .form-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #9a8f85;
        margin-bottom: 8px;
    }
 
    .form-input {
        width: 100%;
        border: 1.5px solid #e8e4de;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        color: #1a1714;
        background: #faf9f7;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .form-input:focus {
        border-color: #c8a96e;
        box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.12);
        background: white;
    }
    .form-input.is-error { border-color: #e05252; box-shadow: 0 0 0 3px rgba(224,82,82,0.1); }
 
    .input-prefix-wrap { position: relative; }
    .input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        font-weight: 600;
        color: #9a8f85;
        letter-spacing: 0.05em;
        pointer-events: none;
    }
    .has-prefix { padding-left: 56px !important; }
 
    .error-msg {
        color: #e05252;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
 
    .btn-submit {
        background: #1a1714;
        color: #f7f5f2;
        border: none;
        border-radius: 10px;
        padding: 13px 28px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.04em;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-submit::after {
        content: '';
        position: absolute;
        inset: 0;
        background: #c8a96e;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.35s ease;
        z-index: 0;
    }
    .btn-submit:hover::after { transform: scaleX(1); }
    .btn-submit:hover { box-shadow: 0 4px 16px rgba(200,169,110,0.3); }
    .btn-submit:active { transform: scale(0.98); }
    .btn-submit span { position: relative; z-index: 1; }
 
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #9a8f85;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
        margin-bottom: 28px;
    }
    .btn-back:hover { color: #1a1714; }
    .btn-back svg { transition: transform 0.2s; }
    .btn-back:hover svg { transform: translateX(-3px); }
 
    .product-badge {
        display: inline-block;
        background: #f0ede8;
        color: #7a6e65;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        font-family: monospace;
    }
 
    .divider { border: none; border-top: 1px solid #ece9e4; margin: 24px 0; }
</style>
 
<div class="grain-overlay min-h-screen py-12">
    <div class="max-w-lg mx-auto px-4">
 
        <a href="{{ route('products.index') }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Quay lại danh sách
        </a>
 
        <div class="card p-8 shadow-sm">
 
            <div class="mb-7">
                <span class="product-badge">#{{ $product->id }}</span>
                <h2 class="display-font text-3xl text-stone-900 mt-3 leading-snug">{{ $product->name }}</h2>
                <p class="text-stone-400 text-sm mt-1">Chỉnh sửa thông tin sản phẩm</p>
            </div>
 
            <hr class="divider">
 
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-6 text-rose-600 text-sm">
                    <p class="font-semibold mb-1">Vui lòng kiểm tra lại:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
 
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
 
                <div class="space-y-5">
 
        
                    <div class="form-group">
                        <label class="form-label">Tên sản phẩm</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            placeholder="Nhập tên sản phẩm..."
                            class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                        >
                        @error('name')
                            <p class="error-msg">⚠ {{ $message }}</p>
                        @enderror
                    </div>
 
        
                    <div class="form-group">
                        <label class="form-label">Giá bán</label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">VND</span>
                            <input
                                type="number"
                                name="price"
                                value="{{ old('price', $product->price) }}"
                                placeholder="0"
                                class="form-input has-prefix {{ $errors->has('price') ? 'is-error' : '' }}"
                                min="0"
                            >
                        </div>
                        @error('price')
                            <p class="error-msg">lỗi  {{ $message }}</p>
                        @enderror
                    </div>
 
          
                    <div class="form-group">
                        <label class="form-label">Số lượng tồn kho</label>
                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', $product->stock) }}"
                            placeholder="0"
                            class="form-input {{ $errors->has('stock') ? 'is-error' : '' }}"
                            min="0"
                        >
                        @error('stock')
                            <p class="error-msg">lỗi  {{ $message }}</p>
                        @enderror
                    </div>
 
                </div>
 
                <hr class="divider">
 
                <div class="flex items-center justify-between">
                    <a href="{{ route('products.index') }}" class="text-stone-400 hover:text-stone-700 text-sm font-medium transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn-submit">
                        <span>Lưu thay đổi</span>
                    </button>
                </div>
 
            </form>
        </div>
 
    </div>
</div>