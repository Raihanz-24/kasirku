<x-filament-panels::page>
    @once
        <style>
            .movement-grid {
                display: grid;
                gap: 16px;
            }

            .movement-summary {
                display: grid;
                gap: 12px;
            }

            .movement-card {
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
                background: rgb(255 255 255);
                padding: 16px;
            }

            .movement-label {
                color: rgb(107 114 128);
                font-size: 13px;
            }

            .movement-value {
                margin-top: 8px;
                color: rgb(17 24 39);
                font-size: 24px;
                font-weight: 750;
                font-variant-numeric: tabular-nums;
            }

            .movement-card.profit {
                border-color: rgb(167 243 208);
                background: rgb(236 253 245);
            }

            .movement-card.profit .movement-value {
                color: rgb(4 120 87);
            }

            .movement-table-wrap {
                overflow-x: auto;
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
            }

            .movement-table {
                width: 100%;
                min-width: 900px;
                border-collapse: collapse;
                font-size: 14px;
            }

            .movement-table th {
                background: rgb(249 250 251);
                color: rgb(107 114 128);
                font-size: 12px;
                font-weight: 700;
                padding: 12px;
                text-align: left;
                text-transform: uppercase;
            }

            .movement-table td {
                border-top: 1px solid rgb(229 231 235);
                color: rgb(31 41 55);
                padding: 12px;
                vertical-align: top;
            }

            .movement-num {
                font-variant-numeric: tabular-nums;
                text-align: right;
                white-space: nowrap;
            }

            .movement-badge {
                display: inline-flex;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                padding: 3px 9px;
                white-space: nowrap;
            }

            .movement-badge.in {
                background: rgb(220 252 231);
                color: rgb(22 101 52);
            }

            .movement-badge.out {
                background: rgb(254 226 226);
                color: rgb(153 27 27);
            }

            .movement-badge.neutral {
                background: rgb(243 244 246);
                color: rgb(75 85 99);
            }

            .movement-empty {
                border: 1px dashed rgb(209 213 219);
                border-radius: 8px;
                color: rgb(107 114 128);
                font-size: 14px;
                padding: 24px;
                text-align: center;
            }

            @media (min-width: 768px) {
                .movement-summary {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (prefers-color-scheme: dark) {
                .movement-card {
                    border-color: rgb(255 255 255 / 0.12);
                    background: #0d2035;
                }

                .movement-card.profit {
                    border-color: rgb(16 185 129 / 0.35);
                    background: rgb(16 185 129 / 0.1);
                }

                .movement-card.profit .movement-value {
                    color: rgb(110 231 183);
                }

                .movement-label,
                .movement-empty {
                    color: rgb(156 163 175);
                }

                .movement-value,
                .movement-table td {
                    color: rgb(255 255 255);
                }

                .movement-table-wrap,
                .movement-table td {
                    border-color: rgb(255 255 255 / 0.12);
                }

                .movement-table th {
                    background: #102a43;
                    color: rgb(209 213 219);
                }

                .movement-empty {
                    border-color: rgb(255 255 255 / 0.18);
                }
            }
        </style>
    @endonce

    <div class="movement-grid">
        <x-filament::section>
            <x-slot name="heading">Filter riwayat</x-slot>
            {{ $this->form }}
        </x-filament::section>

        <div class="movement-summary">
            <div class="movement-card">
                <div class="movement-label">Total saldo masuk</div>
                <div class="movement-value">{{ $this->rupiah($this->getTotalIn()) }}</div>
            </div>

            <div class="movement-card">
                <div class="movement-label">Total saldo keluar</div>
                <div class="movement-value">{{ $this->rupiah($this->getTotalOut()) }}</div>
            </div>

            <div class="movement-card profit">
                <div class="movement-label">Laba kotor</div>
                <div class="movement-value">{{ $this->rupiah($this->getGrossProfit()) }}</div>
            </div>
        </div>

        <x-filament::section>
            <x-slot name="heading">Daftar riwayat saldo</x-slot>

            @php
                $movements = $this->getMovements();
            @endphp

            @if ($movements->isEmpty())
                <div class="movement-empty">Tidak ada riwayat saldo pada filter ini.</div>
            @else
                <div class="movement-table-wrap">
                    <table class="movement-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th class="movement-num">Nominal</th>
                                <th class="movement-num">Saldo sebelum</th>
                                <th class="movement-num">Saldo setelah</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                <tr>
                                    <td>{{ $movement->occurred_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="movement-badge {{ $this->movementTone($movement->type) }}">
                                            {{ $this->movementTypeLabel($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->description ?? '-' }}</td>
                                    <td class="movement-num">{{ $this->rupiah($movement->amount) }}</td>
                                    <td class="movement-num">{{ $this->rupiah($movement->balance_before) }}</td>
                                    <td class="movement-num">{{ $this->rupiah($movement->balance_after) }}</td>
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
