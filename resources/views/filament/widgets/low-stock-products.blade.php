<x-filament-widgets::widget>
    <style>
        .low-panel { height: 100%; min-height: 390px; overflow: hidden; border: 1px solid #c9d6e2; border-radius: 8px; background: #f9fbfc; color: #102a43; box-shadow: 0 12px 28px rgb(15 42 67 / .08); }
        .low-head { padding: 20px 22px; border-bottom: 1px solid #d6e0e8; } .low-head h2 { font-size: 16px; font-weight: 750; } .low-head p { margin-top: 5px; color: #667c91; font-size: 12px; }
        .low-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 14px; min-height: 58px; padding: 11px 22px; border-bottom: 1px solid #e1e8ee; text-decoration: none; }
        .low-row:last-child { border-bottom: 0; } .low-row strong, .low-row small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .low-row strong { color: #102a43; font-size: 12px; } .low-row small { margin-top: 3px; color: #71849c; font-size: 10px; }
        .low-count { border-radius: 999px; background: rgb(244 63 94 / .12); color: #fb7185; font-size: 11px; font-weight: 750; padding: 5px 9px; white-space: nowrap; }
        .low-empty { display: grid; min-height: 260px; place-items: center; color: #71849c; font-size: 13px; }
        .dark .low-panel { border-color: #26374b; background: #0d2035; color: #e5edf6; box-shadow: none; }
        .dark .low-head { border-color: #26374b; } .dark .low-head p { color: #8fa2b6; }
        .dark .low-row { border-color: #203247; } .dark .low-row strong { color: #e5edf6; }
    </style>
    <section class="low-panel">
        <header class="low-head"><h2>Produk Stok Menipis</h2><p>Produk yang perlu ditinjau untuk restock.</p></header>
        @forelse ($this->getProducts() as $product)
            <a class="low-row" href="{{ $this->getProductUrl($product->id) }}"><div><strong>{{ $product->name }}</strong><small>{{ $product->sku }} · Minimum {{ $product->minimum_stock }}</small></div><span class="low-count">{{ $product->current_stock }} {{ $product->unit }}</span></a>
        @empty
            <div class="low-empty">Semua stok produk masih aman.</div>
        @endforelse
    </section>
</x-filament-widgets::widget>
