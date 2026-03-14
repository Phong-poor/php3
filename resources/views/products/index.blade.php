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
 
    .page-enter { animation: fadeSlideUp 0.5s ease both; }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
 
    .product-row {
        animation: rowIn 0.4s ease both;
        border-bottom: 1px solid #ece9e4;
    }
    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .product-row:nth-child(1)  { animation-delay: 0.05s; }
    .product-row:nth-child(2)  { animation-delay: 0.10s; }
    .product-row:nth-child(3)  { animation-delay: 0.15s; }
    .product-row:nth-child(4)  { animation-delay: 0.20s; }
    .product-row:nth-child(5)  { animation-delay: 0.25s; }
    .product-row:nth-child(6)  { animation-delay: 0.30s; }
    .product-row:nth-child(7)  { animation-delay: 0.35s; }
    .product-row:nth-child(8)  { animation-delay: 0.40s; }
    .product-row:nth-child(9)  { animation-delay: 0.45s; }
    .product-row:nth-child(10) { animation-delay: 0.50s; }
 
    .product-row:hover { background: #f0ede8; }
    .product-row:hover .row-actions { opacity: 1; transform: translateX(0); }
    .row-actions { opacity: 0; transform: translateX(6px); transition: all 0.25s ease; }
 
    .btn-new {
        background: #1a1714;
        color: #f7f5f2;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-new::after {
        content: '';
        position: absolute;
        inset: 0;
        background: #c8a96e;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.35s ease;
        z-index: 0;
    }
    .btn-new:hover::after { transform: scaleX(1); }
    .btn-new span { position: relative; z-index: 1; }
 
    .badge-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.04em;
    }
    .badge-in  { background: #e8f5ee; color: #2d7a4f; }
    .badge-out { background: #fdecea; color: #c0392b; }
    .dot { width: 6px; height: 6px; border-radius: 50%; }
    .dot-in  { background: #2d7a4f; }
    .dot-out { background: #c0392b; }
 
    .price-tag { font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
 
    .alert-success {
        background: #fffdf6;
        border-left: 3px solid #c8a96e;
        animation: fadeSlideUp 0.4s ease both;
    }
 
    .table-header th {
        background: #f0ede8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        font-weight: 600;
        color: #9a8f85;
    }
 
    .id-chip {
        display: inline-block;
        background: #e8e4de;
        color: #7a6e65;
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.04em;
    }
 
    .action-edit {
        color: #7a6e65;
        transition: color 0.2s;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .action-edit:hover { color: #c8a96e; }
 
    .action-del {
        color: #7a6e65;
        transition: color 0.2s;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .action-del:hover { color: #c0392b; }
 
    .pagination-wrap { display: flex; justify-content: center; }
    .pagination-wrap nav span, .pagination-wrap nav a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px; height: 34px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #7a6e65;
        transition: all 0.2s;
    }
    .pagination-wrap nav a:hover { background: #e8e4de; color: #1a1714; }
    .pagination-wrap nav span[aria-current] { background: #1a1714; color: #f7f5f2; border-radius: 6px; }
 
    /* ── Search ── */
    .search-wrap {
        background: white;
        border: 1px solid #ece9e4;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
        animation: fadeSlideUp 0.4s ease 0.05s both;
    }
    .search-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #9a8f85;
        margin-bottom: 12px;
        display: block;
    }
    .search-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 10px;
        align-items: end;
    }
    .search-field label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b0a89e;
        margin-bottom: 6px;
    }
    .search-input {
        width: 100%;
        border: 1.5px solid #e8e4de;
        border-radius: 8px;
        padding: 9px 13px;
        font-size: 13px;
        color: #1a1714;
        background: #faf9f7;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .search-input:focus {
        border-color: #c8a96e;
        box-shadow: 0 0 0 3px rgba(200,169,110,0.12);
        background: white;
    }
    .search-input::placeholder { color: #c0b8b0; }
    .price-input-wrap { position: relative; }
    .price-prefix {
        position: absolute;
        left: 11px; top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        font-weight: 700;
        color: #b0a89e;
        pointer-events: none;
    }
    .has-prefix { padding-left: 46px !important; }
 
    .btn-search {
        background: #1a1714;
        color: #f7f5f2;
        border: none;
        border-radius: 8px;
        padding: 9px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .btn-search::after {
        content: '';
        position: absolute;
        inset: 0;
        background: #c8a96e;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
        z-index: 0;
    }
    .btn-search:hover::after { transform: scaleX(1); }
    .btn-search:hover { box-shadow: 0 3px 12px rgba(200,169,110,0.3); }
    .btn-search span { position: relative; z-index: 1; }
 
    .btn-reset {
        background: transparent;
        color: #9a8f85;
        border: 1.5px solid #e8e4de;
        border-radius: 8px;
        padding: 9px 13px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-reset:hover { border-color: #c0392b; color: #c0392b; background: #fdecea; }
 
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #ece9e4;
    }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0ede8;
        color: #7a6e65;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 500;
    }
    .filter-tag strong { color: #1a1714; font-weight: 700; }
    .result-count { font-size: 12px; color: #9a8f85; font-style: italic; margin-left: auto; }
</style>
 
<div class="grain-overlay min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-6">
 
     
        <div class="page-enter flex justify-between items-start mb-8">
            <div>
                <h1 class="display-font text-5xl text-stone-900 leading-tight">Sản Phẩm</h1>
            </div>
            <a href="{{ route('products.create') }}" class="btn-new mt-2 px-6 py-3 rounded-lg text-sm font-semibold tracking-wide">
                <span> Thêm Sản Phẩm</span>
            </a>
        </div>
 
       
        @if(session('success'))
            <div class="alert-success p-4 mb-6 rounded-r-lg text-stone-600 text-sm">
                ✓ &nbsp;{{ session('success') }}
            </div>
        @endif
 
        <div class="search-wrap">
            <span class="search-label">Tìm kiếm & lọc sản phẩm</span>
            <form method="GET" action="{{ route('products.index') }}">
                <div class="search-grid">
 
                    <div class="search-field">
                        <label>Mã sản phẩm (ID)</label>
                        <input
                            type="number"
                            name="id"
                            value="{{ request('id') }}"
                            placeholder="VD: 12"
                            class="search-input"
                            min="1"
                        >
                    </div>
 
                    <div class="search-field">
                        <label>Giá từ</label>
                        <div class="price-input-wrap">
                            <span class="price-prefix">VND</span>
                            <input
                                type="number"
                                name="price_min"
                                value="{{ request('price_min') }}"
                                placeholder="0"
                                class="search-input has-prefix"
                                min="0"
                            >
                        </div>
                    </div>
 
                    <div class="search-field">
                        <label>Giá đến</label>
                        <div class="price-input-wrap">
                            <span class="price-prefix">VND</span>
                            <input
                                type="number"
                                name="price_max"
                                value="{{ request('price_max') }}"
                                placeholder="Không giới hạn"
                                class="search-input has-prefix"
                                min="0"
                            >
                        </div>
                    </div>
 
                    <div class="flex gap-2">
                        <button type="submit" class="btn-search">
                            <span>Tìm kiếm</span>
                        </button>
                        @if(request()->hasAny(['id','price_min','price_max']))
                            <a href="{{ route('products.index') }}" class="btn-reset" title="Xóa bộ lọc">✕</a>
                        @endif
                    </div>
 
                </div>
            </form>
 
            @if(request()->hasAny(['id','price_min','price_max']))
                <div class="filter-tags">
                    <span style="font-size:11px;color:#9a8f85;">Đang lọc:</span>
                    @if(request('id'))
                        <span class="filter-tag">Mã SP: <strong>#{{ request('id') }}</strong></span>
                    @endif
                    @if(request('price_min'))
                        <span class="filter-tag">Giá từ: <strong>{{ number_format(request('price_min')) }} VND</strong></span>
                    @endif
                    @if(request('price_max'))
                        <span class="filter-tag">Giá đến: <strong>{{ number_format(request('price_max')) }} VND</strong></span>
                    @endif
                    <span class="result-count">{{ $products->total() }} kết quả</span>
                </div>
            @endif
        </div>
 
        <div class="bg-white border border-stone-100 rounded-xl overflow-hidden shadow-sm" style="animation: fadeSlideUp 0.5s ease both; animation-delay: 0.15s;">
            <table class="w-full text-left">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-4">Mã SP</th>
                        <th class="px-6 py-4">Sản Phẩm</th>
                        <th class="px-6 py-4">Giá</th>
                        <th class="px-6 py-4">Tồn Kho</th>
                        <th class="px-6 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="product-row transition-colors">
                        <td class="px-6 py-5">
                            <span class="id-chip">#{{ $product->id }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="block text-stone-900 font-semibold text-base">{{ $product->name }}</span>
                            <span class="text-stone-400 text-xs font-mono mt-0.5 block">{{ $product->slug }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="price-tag text-stone-800 font-semibold text-base">{{ number_format($product->price) }}</span>
                            <span class="text-stone-400 text-xs ml-1">VND</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($product->stock > 0)
                                <span class="badge-stock badge-in">
                                    <span class="dot dot-in"></span>
                                    {{ $product->stock }} còn lại
                                </span>
                            @else
                                <span class="badge-stock badge-out">
                                    <span class="dot dot-out"></span>
                                    Hết hàng
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="row-actions flex justify-end items-center gap-5">
                                <a href="{{ route('products.edit', $product) }}" class="action-edit">Sửa</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-del">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <p class="display-font text-3xl text-stone-200 mb-2">Không tìm thấy sản phẩm</p>
                            <p class="text-stone-400 text-sm">
                                Thử thay đổi bộ lọc hoặc
                                <a href="{{ route('products.index') }}" class="underline hover:text-stone-600">xem tất cả</a>.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        
        <div class="mt-8 pagination-wrap">
            {{ $products->appends(request()->query())->links() }}
        </div>
 
    </div>
</div>