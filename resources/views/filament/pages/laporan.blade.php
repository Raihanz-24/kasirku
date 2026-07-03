<x-filament-panels::page>
    <style>
        .report-grid { display: grid; gap: 16px; }
        .report-summary { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .report-card { border: 1px solid rgb(229 231 235); border-radius: 8px; background: white; padding: 15px; }
        .report-card span { color: rgb(107 114 128); font-size: 12px; font-weight: 600; }
        .report-card strong { display: block; margin-top: 7px; font-size: 21px; font-variant-numeric: tabular-nums; }
        .report-table-wrap { overflow-x: auto; border: 1px solid rgb(229 231 235); border-radius: 8px; }
        .report-table { width: 100%; min-width: 820px; border-collapse: collapse; font-size: 13px; }
        .report-table th { background: rgb(249 250 251); color: rgb(107 114 128); font-size: 11px; padding: 11px; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .report-table td { border-top: 1px solid rgb(229 231 235); padding: 11px; vertical-align: top; }
        .report-num { font-variant-numeric: tabular-nums; text-align: right !important; white-space: nowrap; }
        .report-items { max-width: 340px; }
        .report-muted { color: rgb(107 114 128); }
        .report-badge { display: inline-flex; border-radius: 999px; padding: 3px 8px; font-size: 11px; font-weight: 700; }
        .report-badge.safe { background: rgb(220 252 231); color: rgb(22 101 52); }
        .report-badge.low, .report-badge.out { background: rgb(254 226 226); color: rgb(153 27 27); }
        .report-badge.in { background: rgb(219 234 254); color: rgb(30 64 175); }
        .report-empty { color: rgb(107 114 128); padding: 28px; text-align: center; }
        .dark .report-card { border-color: #263f56; background: #0d2035; }
        .dark .report-card span, .dark .report-muted, .dark .report-empty { color: rgb(156 163 175); }
        .dark .report-table-wrap, .dark .report-table td { border-color: rgb(255 255 255 / .12); }
        .dark .report-table th { background: #102a43; color: rgb(209 213 219); }
        @media (min-width: 768px) { .report-summary { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 520px) { .report-summary { grid-template-columns: 1fr; } }
    </style>

    <div class="report-grid">
        <x-filament::section><x-slot name="heading">Filter laporan</x-slot>{{ $this->form }}</x-filament::section>

        @if ($reportType === 'sales')
            @php($rows = $this->getSales())
            <div class="report-summary">
                <div class="report-card"><span>Total penjualan</span><strong>{{ $this->rupiah($this->getSalesTotal()) }}</strong></div>
                <div class="report-card"><span>Jumlah transaksi</span><strong>{{ number_format($rows->count(), 0, ',', '.') }}</strong></div>
            </div>
            <x-filament::section><x-slot name="heading">Laporan Penjualan</x-slot>
                <div class="report-table-wrap"><table class="report-table"><thead><tr><th>Tanggal</th><th>Nomor transaksi</th><th>Produk</th><th class="report-num">Total</th><th>Kasir</th></tr></thead><tbody>
                    @forelse ($rows as $sale)<tr><td>{{ $sale->occurred_at?->format('d/m/Y H:i') }}</td><td>{{ $sale->invoice_number }}</td><td class="report-items">{{ $sale->items->map(fn ($item) => $item->product_name.' x'.$item->quantity)->join(', ') }}</td><td class="report-num">{{ $this->rupiah($sale->total_amount) }}</td><td>{{ $sale->user?->name ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="report-empty">Tidak ada penjualan pada periode ini.</td></tr>@endforelse
                </tbody></table></div>
            </x-filament::section>
        @elseif ($reportType === 'stock_in')
            @php($rows = $this->getStockIns())
            <div class="report-summary"><div class="report-card"><span>Total barang masuk</span><strong>{{ number_format($this->getStockInTotal(), 0, ',', '.') }} item</strong></div><div class="report-card"><span>Jumlah pencatatan</span><strong>{{ number_format($rows->count(), 0, ',', '.') }}</strong></div></div>
            <x-filament::section><x-slot name="heading">Laporan Barang Masuk</x-slot><div class="report-table-wrap"><table class="report-table"><thead><tr><th>Tanggal</th><th>Produk</th><th class="report-num">Jumlah</th><th class="report-num">Harga modal</th><th>User</th></tr></thead><tbody>
                @forelse ($rows as $row)<tr><td>{{ $row->occurred_at?->format('d/m/Y H:i') }}</td><td>{{ $row->product?->name ?? '-' }}</td><td class="report-num">{{ $row->quantity }} {{ $row->product?->unit }}</td><td class="report-num">{{ $this->rupiah($row->cost_price) }}</td><td>{{ $row->user?->name ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="report-empty">Tidak ada barang masuk pada periode ini.</td></tr>@endforelse
            </tbody></table></div></x-filament::section>
        @elseif ($reportType === 'stock')
            @php($rows = $this->getProducts())
            <div class="report-summary"><div class="report-card"><span>Total produk</span><strong>{{ number_format($rows->count(), 0, ',', '.') }}</strong></div><div class="report-card"><span>Stok menipis</span><strong>{{ number_format($rows->filter->isLowStock()->count(), 0, ',', '.') }}</strong></div></div>
            <x-filament::section><x-slot name="heading">Laporan Stok Produk</x-slot><div class="report-table-wrap"><table class="report-table"><thead><tr><th>Produk</th><th>SKU</th><th>Kategori</th><th class="report-num">Stok</th><th class="report-num">Minimum</th><th>Status</th></tr></thead><tbody>
                @forelse ($rows as $row)<tr><td>{{ $row->name }}</td><td>{{ $row->sku }}</td><td>{{ $row->category?->name ?? '-' }}</td><td class="report-num">{{ $row->current_stock }} {{ $row->unit }}</td><td class="report-num">{{ $row->minimum_stock }}</td><td><span class="report-badge {{ $row->isLowStock() ? 'low' : 'safe' }}">{{ $row->isLowStock() ? 'Menipis' : 'Aman' }}</span></td></tr>@empty<tr><td colspan="6" class="report-empty">Belum ada produk.</td></tr>@endforelse
            </tbody></table></div></x-filament::section>
        @elseif ($reportType === 'balance')
            @php($rows = $this->getBalanceMovements())
            <div class="report-summary"><div class="report-card"><span>Saldo masuk</span><strong>{{ $this->rupiah($this->getBalanceTotalIn()) }}</strong></div><div class="report-card"><span>Saldo keluar</span><strong>{{ $this->rupiah($this->getBalanceTotalOut()) }}</strong></div></div>
            <x-filament::section><x-slot name="heading">Laporan Saldo</x-slot><div class="report-table-wrap"><table class="report-table"><thead><tr><th>Tanggal</th><th>Tipe</th><th>Keterangan</th><th class="report-num">Nominal</th><th class="report-num">Saldo akhir</th><th>User</th></tr></thead><tbody>
                @forelse ($rows as $row)<tr><td>{{ $row->occurred_at?->format('d/m/Y H:i') }}</td><td><span class="report-badge {{ in_array($row->type, ['sale', 'correction_in']) ? 'in' : 'out' }}">{{ $this->movementLabel($row->type) }}</span></td><td>{{ $row->description ?? '-' }}</td><td class="report-num">{{ $this->rupiah($row->amount) }}</td><td class="report-num">{{ $this->rupiah($row->balance_after) }}</td><td>{{ $row->user?->name ?? '-' }}</td></tr>@empty<tr><td colspan="6" class="report-empty">Tidak ada pergerakan saldo pada periode ini.</td></tr>@endforelse
            </tbody></table></div></x-filament::section>
        @else
            @php($rows = $this->getWithdrawals())
            <div class="report-summary"><div class="report-card"><span>Total penarikan</span><strong>{{ $this->rupiah($this->getWithdrawalTotal()) }}</strong></div><div class="report-card"><span>Jumlah transaksi</span><strong>{{ number_format($rows->count(), 0, ',', '.') }}</strong></div></div>
            <x-filament::section><x-slot name="heading">Laporan Penarikan Saldo</x-slot><div class="report-table-wrap"><table class="report-table"><thead><tr><th>Tanggal</th><th>Keperluan</th><th>Catatan</th><th class="report-num">Nominal</th><th>User</th></tr></thead><tbody>
                @forelse ($rows as $row)<tr><td>{{ $row->occurred_at?->format('d/m/Y H:i') }}</td><td>{{ $row->purpose }}</td><td class="report-muted">{{ $row->notes ?: '-' }}</td><td class="report-num">{{ $this->rupiah($row->amount) }}</td><td>{{ $row->user?->name ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="report-empty">Tidak ada penarikan pada periode ini.</td></tr>@endforelse
            </tbody></table></div></x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
