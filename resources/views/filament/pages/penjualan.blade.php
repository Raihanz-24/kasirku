<x-filament-panels::page>
    @once
        <style>
            .pos-page {
                display: grid;
                gap: 16px;
            }

            .pos-main {
                display: grid;
                gap: 16px;
            }

            .pos-stack {
                display: grid;
                gap: 12px;
            }

            .pos-heading {
                margin: 0;
                color: rgb(17 24 39);
                font-size: 16px;
                font-weight: 650;
                line-height: 1.4;
            }

            .pos-muted {
                margin: 4px 0 0;
                color: rgb(107 114 128);
                font-size: 13px;
                line-height: 1.4;
            }

            .pos-barcode-message {
                margin: 6px 0 0;
                color: rgb(220 38 38);
                font-size: 13px;
                font-weight: 650;
                line-height: 1.4;
            }

            .pos-input-row {
                display: grid;
                gap: 10px;
            }

            .pos-barcode-actions {
                display: grid;
                gap: 10px;
            }

            .pos-mobile-only {
                display: block;
            }

            .pos-scanner-backdrop {
                position: fixed;
                z-index: 9999;
                inset: 0;
                display: grid;
                place-items: center;
                background: rgb(0 0 0 / 0.72);
                padding: 16px;
            }

            .pos-scanner-panel {
                width: min(100%, 460px);
                overflow: hidden;
                border-radius: 12px;
                background: rgb(255 255 255);
                box-shadow: 0 24px 70px rgb(0 0 0 / 0.35);
            }

            .pos-scanner-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 14px 16px;
            }

            .pos-scanner-title {
                color: rgb(17 24 39);
                font-size: 15px;
                font-weight: 700;
            }

            .pos-scanner-video-wrap {
                position: relative;
                background: rgb(0 0 0);
                aspect-ratio: 4 / 3;
            }

            .pos-scanner-video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .pos-scanner-guide {
                position: absolute;
                inset: 22%;
                border: 2px solid rgb(16 185 129);
                border-radius: 12px;
                box-shadow: 0 0 0 999px rgb(0 0 0 / 0.28);
            }

            .pos-scanner-message {
                min-height: 44px;
                color: rgb(75 85 99);
                font-size: 13px;
                line-height: 1.45;
                padding: 12px 16px 16px;
            }

            .pos-scanner-footer {
                display: flex;
                justify-content: flex-end;
                border-top: 1px solid rgb(229 231 235);
                padding: 12px 16px;
            }

            .pos-combobox {
                position: relative;
                min-width: 0;
            }

            .pos-select {
                width: 100%;
                min-height: 42px;
                border: 0;
                background: transparent;
                color: rgb(17 24 39);
                font-size: 14px;
                outline: none;
            }

            .pos-results {
                position: absolute;
                z-index: 30;
                top: calc(100% + 6px);
                left: 0;
                right: 0;
                max-height: 260px;
                overflow-y: auto;
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
                background: rgb(255 255 255);
                box-shadow: 0 12px 30px rgb(15 23 42 / 0.16);
                padding: 6px;
            }

            .pos-result {
                display: block;
                width: 100%;
                border: 0;
                border-radius: 6px;
                background: transparent;
                color: rgb(17 24 39);
                cursor: pointer;
                padding: 10px;
                text-align: left;
            }

            .pos-result:hover,
            .pos-result:focus {
                background: rgb(243 244 246);
                outline: none;
            }

            .pos-result-name {
                display: block;
                font-size: 14px;
                font-weight: 650;
                line-height: 1.35;
            }

            .pos-result-meta {
                display: block;
                margin-top: 3px;
                color: rgb(107 114 128);
                font-size: 12px;
                line-height: 1.35;
            }

            .pos-no-result {
                color: rgb(107 114 128);
                font-size: 13px;
                padding: 10px;
            }

            .pos-cart-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .pos-count {
                display: inline-flex;
                min-height: 26px;
                align-items: center;
                border-radius: 6px;
                background: rgb(243 244 246);
                color: rgb(75 85 99);
                font-size: 12px;
                font-weight: 650;
                padding: 4px 9px;
                white-space: nowrap;
            }

            .pos-cart-list {
                display: grid;
                gap: 10px;
            }

            .pos-cart-item {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
                border: 1px solid rgb(229 231 235);
                border-radius: 8px;
                background: rgb(255 255 255);
                padding: 14px;
            }

            .pos-product-name {
                color: rgb(17 24 39);
                font-size: 14px;
                font-weight: 650;
                line-height: 1.35;
                word-break: break-word;
            }

            .pos-product-price {
                margin-top: 4px;
                color: rgb(107 114 128);
                font-size: 13px;
                font-variant-numeric: tabular-nums;
            }

            .pos-item-actions {
                display: grid;
                gap: 10px;
            }

            .pos-qty {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .pos-qty-input {
                width: 64px;
                min-height: 36px;
                border: 1px solid rgb(209 213 219);
                border-radius: 6px;
                background: rgb(255 255 255);
                color: rgb(17 24 39);
                font-size: 14px;
                font-weight: 650;
                text-align: center;
            }

            .pos-subtotal {
                color: rgb(17 24 39);
                font-size: 15px;
                font-weight: 700;
                font-variant-numeric: tabular-nums;
                text-align: left;
            }

            .pos-empty {
                border: 1px dashed rgb(209 213 219);
                border-radius: 8px;
                padding: 28px 16px;
                text-align: center;
            }

            .pos-empty-title {
                color: rgb(55 65 81);
                font-size: 14px;
                font-weight: 650;
            }

            .pos-empty-text {
                margin-top: 4px;
                color: rgb(107 114 128);
                font-size: 13px;
            }

            .pos-total-box {
                border-radius: 8px;
                background: rgb(249 250 251);
                padding: 16px;
            }

            .pos-total-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                color: rgb(107 114 128);
                font-size: 13px;
            }

            .pos-total-value {
                margin-top: 8px;
                color: rgb(17 24 39);
                font-size: 30px;
                font-weight: 750;
                line-height: 1.15;
                font-variant-numeric: tabular-nums;
            }

            .pos-notes {
                width: 100%;
                min-height: 96px;
                border: 0;
                background: transparent;
                color: rgb(17 24 39);
                font-size: 14px;
                outline: none;
                resize: vertical;
            }

            .pos-actions {
                display: grid;
                gap: 10px;
            }

            .pos-success {
                border-radius: 8px;
                background: rgb(236 253 245);
                color: rgb(4 120 87);
                font-size: 13px;
                padding: 10px 12px;
            }

            @media (min-width: 768px) {
                .pos-input-row {
                    grid-template-columns: minmax(0, 1fr) auto;
                    align-items: center;
                }

                .pos-barcode-actions {
                    grid-template-columns: auto auto;
                    align-items: center;
                }

                .pos-mobile-only {
                    display: none;
                }

                .pos-cart-item {
                    grid-template-columns: minmax(220px, 1fr) auto;
                    align-items: center;
                }

                .pos-item-actions {
                    grid-template-columns: auto minmax(150px, auto) auto;
                    align-items: center;
                }

                .pos-subtotal {
                    min-width: 150px;
                    text-align: right;
                }
            }

            @media (min-width: 1280px) {
                .pos-page {
                    grid-template-columns: minmax(0, 1fr) 380px;
                    align-items: start;
                }
            }

            @media (prefers-color-scheme: dark) {
                .pos-heading,
                .pos-product-name,
                .pos-subtotal,
                .pos-total-value,
                .pos-scanner-title,
                .pos-select,
                .pos-result,
                .pos-qty-input,
                .pos-notes {
                    color: rgb(255 255 255);
                }

                .pos-muted,
                .pos-product-price,
                .pos-total-meta,
                .pos-result-meta,
                .pos-no-result,
                .pos-empty-text {
                    color: rgb(156 163 175);
                }

                .pos-barcode-message {
                    color: rgb(252 165 165);
                }

                .pos-count,
                .pos-total-box {
                    background: rgb(255 255 255 / 0.06);
                    color: rgb(209 213 219);
                }

                .pos-cart-item {
                    border-color: rgb(255 255 255 / 0.12);
                    background: #0d2035;
                }

                .pos-results {
                    border-color: rgb(255 255 255 / 0.14);
                    background: #0d2035;
                    box-shadow: 0 18px 34px rgb(0 0 0 / 0.36);
                }

                .pos-scanner-panel {
                    background: #0d2035;
                }

                .pos-scanner-message {
                    color: rgb(209 213 219);
                }

                .pos-result:hover,
                .pos-result:focus {
                    background: rgb(255 255 255 / 0.08);
                }

                .pos-qty-input {
                    border-color: rgb(255 255 255 / 0.16);
                    background: rgb(255 255 255 / 0.05);
                }

                .pos-empty {
                    border-color: rgb(255 255 255 / 0.18);
                }

                .pos-empty-title {
                    color: rgb(229 231 235);
                }

                .pos-success {
                    background: rgb(16 185 129 / 0.12);
                    color: rgb(110 231 183);
                }
            }
        </style>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('posBarcodeScanner', (livewire) => ({
                    error: '',
                    scanning: false,
                    stream: null,
                    detector: null,
                    frameId: null,
                    lastCode: null,
                    emptyFrames: 0,
                    processing: false,
                    scannerMessage: 'Arahkan barcode ke kotak hijau.',

                    async openScanner() {
                        this.error = '';
                        this.lastCode = null;
                        this.emptyFrames = 0;
                        this.processing = false;
                        this.scannerMessage = 'Arahkan barcode ke kotak hijau.';

                        if (!window.isSecureContext) {
                            this.error = 'Kamera membutuhkan HTTPS atau localhost. Buka aplikasi lewat koneksi aman untuk memakai scanner.';

                            return;
                        }

                        if (!navigator.mediaDevices?.getUserMedia) {
                            this.error = 'Kamera tidak tersedia di browser ini.';

                            return;
                        }

                        this.scanning = true;

                        this.$nextTick(async () => {
                            try {
                                const formats = [
                                    'ean_13',
                                    'ean_8',
                                    'upc_a',
                                    'upc_e',
                                    'code_128',
                                    'code_39',
                                    'code_93',
                                    'itf',
                                    'qr_code',
                                ];

                                this.stream = await navigator.mediaDevices.getUserMedia({
                                    video: {
                                        facingMode: { ideal: 'environment' },
                                        width: { ideal: 1280 },
                                        height: { ideal: 720 },
                                    },
                                    audio: false,
                                });

                                this.$refs.scannerVideo.srcObject = this.stream;
                                await this.$refs.scannerVideo.play();

                                if (!('BarcodeDetector' in window)) {
                                    this.scannerMessage = 'Kamera sudah terbuka, tapi browser ini belum mendukung pembaca barcode otomatis. Gunakan Chrome/Edge terbaru atau ketik barcode manual.';

                                    return;
                                }

                                this.detector = new BarcodeDetector({ formats });
                                this.scanFrame();
                            } catch (error) {
                                this.error = this.cameraErrorMessage(error);
                                this.closeScanner();
                            }
                        });
                    },

                    cameraErrorMessage(error) {
                        if (error?.name === 'NotAllowedError') {
                            return 'Izin kamera ditolak. Buka pengaturan situs di browser lalu izinkan kamera.';
                        }

                        if (error?.name === 'NotFoundError') {
                            return 'Kamera tidak ditemukan pada perangkat ini.';
                        }

                        if (error?.name === 'NotReadableError') {
                            return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi kamera/WhatsApp lalu coba lagi.';
                        }

                        if (error?.name === 'OverconstrainedError') {
                            return 'Kamera belakang tidak tersedia. Coba buka ulang scanner atau gunakan input barcode manual.';
                        }

                        return 'Kamera tidak bisa dibuka. Pastikan izin kamera diberikan.';
                    },

                    async scanFrame() {
                        if (!this.scanning || !this.detector || !this.$refs.scannerVideo) {
                            return;
                        }

                        try {
                            const barcodes = await this.detector.detect(this.$refs.scannerVideo);

                            if (barcodes.length > 0) {
                                const code = barcodes[0].rawValue;
                                this.emptyFrames = 0;

                                if (!this.processing && code !== this.lastCode) {
                                    this.processing = true;
                                    this.lastCode = code;
                                    this.scannerMessage = `Memproses barcode ${code}...`;

                                    await livewire.set('barcode', code);
                                    await livewire.addByBarcode();

                                    this.scannerMessage = 'Berhasil dipindai. Arahkan ke barcode berikutnya.';
                                    this.processing = false;
                                }
                            } else {
                                this.emptyFrames++;

                                if (this.emptyFrames >= 8) {
                                    this.lastCode = null;
                                    this.emptyFrames = 0;
                                }
                            }
                        } catch (error) {
                            this.processing = false;
                            this.scannerMessage = 'Barcode belum terbaca. Arahkan kamera lebih dekat dan pastikan label terang.';
                        }

                        if (this.scanning) {
                            this.frameId = requestAnimationFrame(() => this.scanFrame());
                        }
                    },

                    closeScanner() {
                        this.scanning = false;

                        if (this.frameId) {
                            cancelAnimationFrame(this.frameId);
                            this.frameId = null;
                        }

                        if (this.stream) {
                            this.stream.getTracks().forEach((track) => track.stop());
                            this.stream = null;
                        }

                        if (this.$refs.scannerVideo) {
                            this.$refs.scannerVideo.pause();
                            this.$refs.scannerVideo.srcObject = null;
                        }

                        this.detector = null;
                        this.processing = false;
                        this.lastCode = null;
                        this.emptyFrames = 0;

                        this.$nextTick(() => this.$refs.barcodeInput?.focus());
                    },
                }));
            });
        </script>
    @endonce

    <div
        class="pos-page"
        x-data="posBarcodeScanner($wire)"
        x-on:keydown.escape.window="closeScanner()"
        x-on:livewire:navigating.window="closeScanner()"
        x-init="$nextTick(() => $refs.barcodeInput?.focus())"
    >
        <div class="pos-main">
            <x-filament::section>
                <div class="pos-stack">
                    <div>
                        <h2 class="pos-heading">Tambah Produk</h2>
                        <p class="pos-muted">Scan kode barang atau pilih produk dari daftar.</p>
                    </div>

                    <div class="pos-input-row">
                        <div>
                            <x-filament::input.wrapper>
                                <x-filament::input
                                    x-ref="barcodeInput"
                                    type="text"
                                    wire:model="barcode"
                                    wire:keydown.enter.prevent="addByBarcode"
                                    placeholder="Scan barcode atau ketik SKU"
                                    autofocus
                                />
                            </x-filament::input.wrapper>

                            @if ($barcodeMessage)
                                <p class="pos-barcode-message">{{ $barcodeMessage }}</p>
                            @endif
                        </div>

                        <div class="pos-barcode-actions">
                            <div class="pos-mobile-only">
                                <x-filament::button
                                    type="button"
                                    color="gray"
                                    icon="heroicon-o-camera"
                                    tabindex="-1"
                                    x-on:click.stop.prevent="openScanner()"
                                    x-on:keydown.enter.stop.prevent
                                    x-on:keydown.space.stop.prevent
                                >
                                    Kamera
                                </x-filament::button>
                            </div>

                            <x-filament::button wire:click="addByBarcode" icon="heroicon-o-qr-code">
                                Tambah
                            </x-filament::button>
                        </div>
                    </div>

                    <template x-if="error">
                        <p class="pos-muted" x-text="error"></p>
                    </template>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="pos-stack">
                    <div class="pos-input-row">
                        <div class="pos-combobox">
                            <x-filament::input.wrapper>
                                <x-filament::input
                                    type="text"
                                    wire:model.live.debounce.300ms="manualProductSearch"
                                    wire:keydown.enter.prevent="addManualProduct"
                                    placeholder="Cari dan pilih produk"
                                    autocomplete="off"
                                />
                            </x-filament::input.wrapper>

                            @if ($this->shouldShowManualProductResults())
                                @php
                                    $manualProducts = $this->getManualProducts();
                                @endphp

                                <div class="pos-results">
                                    @forelse ($manualProducts as $product)
                                        <button
                                            type="button"
                                            class="pos-result"
                                            wire:click="selectManualProduct({{ $product->id }})"
                                        >
                                            <span class="pos-result-name">{{ $product->name }}</span>
                                            <span class="pos-result-meta">Stok {{ $product->current_stock }} {{ $product->unit }}</span>
                                        </button>
                                    @empty
                                        <div class="pos-no-result">Produk tidak ditemukan.</div>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        <x-filament::button wire:click="addManualProduct" icon="heroicon-o-plus">
                            Tambah
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="pos-stack">
                    <div class="pos-cart-head">
                        <h2 class="pos-heading">Keranjang</h2>
                        <span class="pos-count">{{ $this->getCartItemCount() }} item</span>
                    </div>

                    @if (empty($cart))
                        <div class="pos-empty">
                            <div class="pos-empty-title">Keranjang kosong</div>
                            <div class="pos-empty-text">Produk yang ditambahkan akan muncul di sini.</div>
                        </div>
                    @else
                        <div class="pos-cart-list">
                            @foreach ($cart as $item)
                                <div class="pos-cart-item" wire:key="cart-{{ $item['product_id'] }}">
                                    <div>
                                        <div class="pos-product-name">{{ $item['name'] }}</div>
                                        <div class="pos-product-price">{{ $this->rupiah($item['selling_price']) }}</div>
                                    </div>

                                    <div class="pos-item-actions">
                                        <div class="pos-qty">
                                            <x-filament::icon-button
                                                icon="heroicon-o-minus"
                                                size="sm"
                                                wire:click="decrementItem({{ $item['product_id'] }})"
                                            />
                                            <input
                                                type="number"
                                                min="1"
                                                step="1"
                                                value="{{ $item['quantity'] }}"
                                                wire:change="updateQuantity({{ $item['product_id'] }}, $event.target.value)"
                                                class="pos-qty-input"
                                                aria-label="Jumlah {{ $item['name'] }}"
                                            />
                                            <x-filament::icon-button
                                                icon="heroicon-o-plus"
                                                size="sm"
                                                wire:click="incrementItem({{ $item['product_id'] }})"
                                            />
                                        </div>

                                        <div class="pos-subtotal">{{ $this->rupiah($item['subtotal']) }}</div>

                                        <x-filament::icon-button
                                            icon="heroicon-o-trash"
                                            color="danger"
                                            wire:click="removeItem({{ $item['product_id'] }})"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>

        <div>
            <x-filament::section>
                <div class="pos-stack">
                    <div class="pos-total-box">
                        <div class="pos-total-meta">
                            <span>Total</span>
                            <span>{{ $this->getCartItemCount() }} item</span>
                        </div>
                        <div class="pos-total-value">{{ $this->rupiah($this->getCartTotal()) }}</div>
                    </div>

                    <x-filament::input.wrapper>
                        <textarea
                            wire:model="notes"
                            rows="3"
                            placeholder="Catatan opsional"
                            class="pos-notes"
                        ></textarea>
                    </x-filament::input.wrapper>

                    <div class="pos-actions">
                        <x-filament::button
                            wire:click="saveSale"
                            icon="heroicon-o-check-circle"
                            size="lg"
                            :disabled="empty($cart)"
                        >
                            Simpan Penjualan
                        </x-filament::button>

                        <x-filament::button
                            wire:click="clearCart"
                            color="gray"
                            icon="heroicon-o-x-mark"
                            :disabled="empty($cart)"
                        >
                            Kosongkan
                        </x-filament::button>
                    </div>

                    @if ($lastInvoiceNumber)
                        <div class="pos-success">
                            Transaksi terakhir: {{ $lastInvoiceNumber }}
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>

        <div
            class="pos-scanner-backdrop"
            x-show="scanning"
            x-transition.opacity
            x-cloak
            x-on:click.self="closeScanner()"
        >
            <div class="pos-scanner-panel" x-on:click.stop>
                <div class="pos-scanner-head">
                    <div class="pos-scanner-title">Scan Barcode</div>
                    <x-filament::icon-button
                        type="button"
                        icon="heroicon-o-x-mark"
                        color="gray"
                        x-on:click.stop.prevent="closeScanner()"
                    />
                </div>

                <div class="pos-scanner-video-wrap">
                    <video
                        x-ref="scannerVideo"
                        class="pos-scanner-video"
                        playsinline
                        muted
                    ></video>
                    <div class="pos-scanner-guide"></div>
                </div>

                <div class="pos-scanner-message" x-text="scannerMessage">
                    Arahkan barcode ke kotak hijau.
                </div>

                <div class="pos-scanner-footer">
                    <x-filament::button
                        type="button"
                        color="gray"
                        icon="heroicon-o-x-mark"
                        x-on:click.stop.prevent="closeScanner()"
                    >
                        Tutup Kamera
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
