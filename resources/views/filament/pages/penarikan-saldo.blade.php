<x-filament-panels::page>
    <style>
        .withdrawal-page { display: grid; gap: 1.25rem; }
        .withdrawal-summary { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .withdrawal-card, .withdrawal-panel { border: 1px solid rgb(229 231 235); border-radius: 8px; background: white; }
        .withdrawal-card { padding: 1rem 1.125rem; }
        .withdrawal-card span { display: block; color: rgb(107 114 128); font-size: .8rem; font-weight: 600; }
        .withdrawal-card strong { display: block; margin-top: .35rem; font-size: 1.45rem; line-height: 1.25; }
        .withdrawal-card.preview strong { color: rgb(5 150 105); }
        .withdrawal-panel { padding: 1.25rem; }
        .withdrawal-panel__header { margin-bottom: 1rem; }
        .withdrawal-panel__header h2 { font-size: 1rem; font-weight: 700; }
        .withdrawal-panel__header p { margin-top: .2rem; color: rgb(107 114 128); font-size: .85rem; }
        .withdrawal-actions { display: flex; justify-content: flex-end; margin-top: 1.25rem; }
        .withdrawal-table-wrap { overflow-x: auto; }
        .withdrawal-table { width: 100%; min-width: 760px; border-collapse: collapse; }
        .withdrawal-table th { padding: .7rem .8rem; border-bottom: 1px solid rgb(229 231 235); color: rgb(107 114 128); font-size: .72rem; text-align: left; text-transform: uppercase; }
        .withdrawal-table td { padding: .8rem; border-bottom: 1px solid rgb(243 244 246); font-size: .85rem; vertical-align: top; }
        .withdrawal-table tbody tr:last-child td { border-bottom: 0; }
        .withdrawal-amount { color: rgb(220 38 38); font-weight: 700; white-space: nowrap; }
        .withdrawal-note { max-width: 280px; color: rgb(107 114 128); }
        .withdrawal-empty { padding: 2rem; color: rgb(107 114 128); text-align: center; }
        .dark .withdrawal-card, .dark .withdrawal-panel { border-color: #263f56; background: #0d2035; }
        .dark .withdrawal-table th { border-color: rgb(55 65 81); color: rgb(156 163 175); }
        .dark .withdrawal-table td { border-color: rgb(39 39 42); }
        .dark .withdrawal-panel__header p, .dark .withdrawal-card span, .dark .withdrawal-note, .dark .withdrawal-empty { color: rgb(156 163 175); }
        @media (max-width: 640px) {
            .withdrawal-summary { grid-template-columns: 1fr; }
            .withdrawal-panel { padding: 1rem; }
            .withdrawal-actions > * { width: 100%; }
        }
    </style>

    <div class="withdrawal-page">
        <div class="withdrawal-summary">
            <div class="withdrawal-card">
                <span>Saldo tersedia</span>
                <strong>{{ $this->rupiah($this->getCurrentBalance()) }}</strong>
            </div>
            <div class="withdrawal-card preview">
                <span>Perkiraan saldo setelah penarikan</span>
                <strong>{{ $this->rupiah($this->getBalanceAfterPreview()) }}</strong>
            </div>
        </div>

        <section class="withdrawal-panel">
            <div class="withdrawal-panel__header">
                <h2>Catat Penarikan</h2>
                <p>Pastikan nominal dan keperluan sudah benar sebelum transaksi disimpan.</p>
            </div>

            <form wire:submit="createWithdrawal">
                {{ $this->form }}

                <div class="withdrawal-actions">
                    <x-filament::button type="submit" icon="heroicon-o-arrow-up-tray">
                        Simpan Penarikan
                    </x-filament::button>
                </div>
            </form>
        </section>

        <section class="withdrawal-panel">
            <div class="withdrawal-panel__header">
                <h2>Penarikan Terbaru</h2>
                <p>Delapan transaksi penarikan terakhir dari saldo toko.</p>
            </div>

            @php($withdrawals = $this->getRecentWithdrawals())
            @if ($withdrawals->isEmpty())
                <div class="withdrawal-empty">Belum ada transaksi penarikan.</div>
            @else
                <div class="withdrawal-table-wrap">
                    <table class="withdrawal-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keperluan</th>
                                <th>Nominal</th>
                                <th>Saldo Akhir</th>
                                <th>User</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $withdrawal)
                                <tr wire:key="withdrawal-{{ $withdrawal->id }}">
                                    <td>{{ $withdrawal->occurred_at?->format('d M Y, H:i') }}</td>
                                    <td><strong>{{ $withdrawal->purpose }}</strong></td>
                                    <td class="withdrawal-amount">-{{ $this->rupiah($withdrawal->amount) }}</td>
                                    <td>{{ $this->rupiah($withdrawal->balance_after) }}</td>
                                    <td>{{ $withdrawal->user?->name ?? '-' }}</td>
                                    <td class="withdrawal-note">{{ $withdrawal->notes ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-filament-panels::page>
