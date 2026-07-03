<x-filament-widgets::widget>
    <style>
        .ops-head h2 { color: #102a43; font-size: 17px; font-weight: 750; } .ops-head p { margin-top: 4px; color: #667c91; font-size: 13px; }
        .ops-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-top: 16px; }
        .ops-stat { position: relative; overflow: hidden; min-height: 150px; border: 1px solid #d7e1e9; border-top: 2px solid rgb(225 172 62 / .7); border-radius: 8px; background: #fff; box-shadow: 0 12px 24px rgb(15 42 67 / .08); padding: 20px; }
        .ops-stat-label { display: flex; align-items: center; gap: 9px; color: #536f87; font-size: 14px; }
        .ops-stat-label svg { width: 20px; color: #8998a8; }
        .ops-stat strong { display: block; margin-top: 10px; color: #102a43; font-size: 27px; font-variant-numeric: tabular-nums; }
        .ops-stat small { display: block; max-width: 190px; margin-top: 8px; color: #7dd3fc; font-size: 12px; line-height: 1.45; }
        .ops-stat:nth-child(1) small { color: #34d399; } .ops-stat:nth-child(2) small { color: #fbbf24; } .ops-stat:nth-child(4) small { color: #fb7185; }
        .dark .ops-head h2 { color: #f8fafc; } .dark .ops-head p { color: #8fa2b6; }
        .dark .ops-stat { border-color: #26374b; border-top-color: rgb(210 164 65 / .55); background: #0d2035; box-shadow: none; }
        .dark .ops-stat-label { color: #aebdcd; } .dark .ops-stat-label svg { color: #7f91a5; } .dark .ops-stat strong { color: #fff; }
        @media (max-width: 1000px) { .ops-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 540px) { .ops-stats { grid-template-columns: 1fr; } }
    </style>
    <section>
        <header class="ops-head"><h2>Ringkasan Operasional</h2><p>Kondisi terkini penjualan, transaksi, stok masuk, dan keuangan toko.</p></header>
        <div class="ops-stats">
            <article class="ops-stat"><div class="ops-stat-label"><x-filament::icon icon="heroicon-o-banknotes" />Penjualan Hari Ini</div><strong>{{ $this->rupiah($this->getTodaySales()) }}</strong><small>Total pemasukan dari transaksi hari ini</small></article>
            <article class="ops-stat"><div class="ops-stat-label"><x-filament::icon icon="heroicon-o-shopping-cart" />Transaksi Hari Ini</div><strong>{{ number_format($this->getTodayTransactions(), 0, ',', '.') }}</strong><small>Transaksi yang berhasil dicatat</small></article>
            <article class="ops-stat"><div class="ops-stat-label"><x-filament::icon icon="heroicon-o-arrow-down-tray" />Barang Masuk Hari Ini</div><strong>{{ number_format($this->getTodayStockIn(), 0, ',', '.') }}</strong><small>Jumlah item stok yang ditambahkan</small></article>
            @if (auth()->user()?->isOwner())
                <article class="ops-stat"><div class="ops-stat-label"><x-filament::icon icon="heroicon-o-arrow-up-tray" />Penarikan Bulan Ini</div><strong>{{ $this->rupiah($this->getMonthlyWithdrawals()) }}</strong><small>Total saldo yang telah ditarik</small></article>
            @else
                <article class="ops-stat"><div class="ops-stat-label"><x-filament::icon icon="heroicon-o-cube" />Stok Menipis</div><strong>{{ $this->getLowStockProducts()->count() }}</strong><small>Produk yang perlu segera diperiksa</small></article>
            @endif
        </div>
    </section>
</x-filament-widgets::widget>
