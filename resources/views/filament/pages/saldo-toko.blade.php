<x-filament-panels::page>
    @once
        <style>
            .balance-grid {
                display: grid;
                gap: 16px;
            }

            .balance-cards {
                display: grid;
                gap: 12px;
            }

            .balance-card {
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
                background: rgb(255 255 255);
                padding: 16px;
            }

            .balance-label {
                color: rgb(107 114 128);
                font-size: 13px;
                line-height: 1.4;
            }

            .balance-value {
                margin-top: 8px;
                color: rgb(17 24 39);
                font-size: 26px;
                font-weight: 750;
                line-height: 1.15;
                font-variant-numeric: tabular-nums;
            }

            .balance-value.main {
                font-size: 34px;
            }

            .balance-table-wrap {
                overflow-x: auto;
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
            }

            .balance-table {
                width: 100%;
                min-width: 760px;
                border-collapse: collapse;
                font-size: 14px;
            }

            .balance-table th {
                background: rgb(249 250 251);
                color: rgb(107 114 128);
                font-size: 12px;
                font-weight: 700;
                padding: 12px;
                text-align: left;
                text-transform: uppercase;
            }

            .balance-table td {
                border-top: 1px solid rgb(229 231 235);
                color: rgb(31 41 55);
                padding: 12px;
                vertical-align: top;
            }

            .balance-num {
                font-variant-numeric: tabular-nums;
                text-align: right;
                white-space: nowrap;
            }

            .balance-badge {
                display: inline-flex;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                padding: 3px 9px;
                white-space: nowrap;
            }

            .balance-badge.in {
                background: rgb(220 252 231);
                color: rgb(22 101 52);
            }

            .balance-badge.out {
                background: rgb(254 226 226);
                color: rgb(153 27 27);
            }

            .balance-badge.neutral {
                background: rgb(243 244 246);
                color: rgb(75 85 99);
            }

            .balance-empty {
                border: 1px dashed rgb(209 213 219);
                border-radius: 8px;
                color: rgb(107 114 128);
                font-size: 14px;
                padding: 24px;
                text-align: center;
            }

            @media (min-width: 768px) {
                .balance-cards {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }

                .balance-card.main {
                    grid-column: span 3;
                }
            }

            @media (prefers-color-scheme: dark) {
                .balance-card {
                    border-color: rgb(255 255 255 / 0.12);
                    background: #0d2035;
                }

                .balance-label,
                .balance-empty {
                    color: rgb(156 163 175);
                }

                .balance-value,
                .balance-table td {
                    color: rgb(255 255 255);
                }

                .balance-table-wrap,
                .balance-table td {
                    border-color: rgb(255 255 255 / 0.12);
                }

                .balance-table th {
                    background: #102a43;
                    color: rgb(209 213 219);
                }

                .balance-empty {
                    border-color: rgb(255 255 255 / 0.18);
                }
            }
        </style>
    @endonce

    <div class="balance-grid">
        <div class="balance-cards">
            <div class="balance-card main">
                <div class="balance-label">Saldo toko saat ini</div>
                <div class="balance-value main">{{ $this->rupiah($this->getCurrentBalance()) }}</div>
            </div>

            <div class="balance-card">
                <div class="balance-label">Penjualan hari ini</div>
                <div class="balance-value">{{ $this->rupiah($this->getTodayIncome()) }}</div>
            </div>

            <div class="balance-card">
                <div class="balance-label">Penjualan bulan ini</div>
                <div class="balance-value">{{ $this->rupiah($this->getThisMonthIncome()) }}</div>
            </div>

            <div class="balance-card">
                <div class="balance-label">Saldo keluar bulan ini</div>
                <div class="balance-value">{{ $this->rupiah($this->getThisMonthOutcome()) }}</div>
            </div>
        </div>

        <x-filament::section>
            <x-slot name="heading">Riwayat saldo terbaru</x-slot>

            @php
                $movements = $this->getRecentMovements();
            @endphp

            @if ($movements->isEmpty())
                <div class="balance-empty">Belum ada riwayat saldo.</div>
            @else
                <div class="balance-table-wrap">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th class="balance-num">Nominal</th>
                                <th class="balance-num">Saldo akhir</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                <tr>
                                    <td>{{ $movement->occurred_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="balance-badge {{ $this->movementTone($movement->type) }}">
                                            {{ $this->movementTypeLabel($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->description ?? '-' }}</td>
                                    <td class="balance-num">{{ $this->rupiah($movement->amount) }}</td>
                                    <td class="balance-num">{{ $this->rupiah($movement->balance_after) }}</td>
                                    <td>{{ $movement->user?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
