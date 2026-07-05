<div
    class="product-camera"
    x-data="productBarcodeCamera($wire)"
    x-init="start()"
    x-on:keydown.escape.window="stop()"
>
    <style>
        .product-camera { display: grid; gap: 14px; }
        .product-camera__viewport { position: relative; overflow: hidden; aspect-ratio: 4 / 3; border: 1px solid #30475f; border-radius: 8px; background: #020617; }
        .product-camera__video { width: 100%; height: 100%; object-fit: cover; }
        .product-camera__guide { position: absolute; inset: 24% 12%; border: 2px solid #34d399; border-radius: 8px; box-shadow: 0 0 0 999px rgb(0 0 0 / .32); }
        .product-camera__status { min-height: 42px; border: 1px solid #30475f; border-radius: 7px; background: #0d2035; color: #cbd7e2; font-size: 13px; line-height: 1.5; padding: 10px 12px; }
        .product-camera__status.success { border-color: rgb(52 211 153 / .55); color: #6ee7b7; }
        .product-camera__status.error { border-color: rgb(251 113 133 / .55); color: #fda4af; }
    </style>

    <div class="product-camera__viewport">
        <video x-ref="video" class="product-camera__video" playsinline muted></video>
        <div class="product-camera__guide"></div>
    </div>

    <div
        class="product-camera__status"
        x-bind:class="{ success: state === 'success', error: state === 'error' }"
        x-text="message"
    ></div>

    <script>
        window.productBarcodeCamera = (livewire) => ({
            detector: null,
            stream: null,
            frameId: null,
            scanning: false,
            state: 'loading',
            message: 'Menyiapkan kamera...',

            async start() {
                if (!window.isSecureContext) {
                    this.fail('Kamera membutuhkan HTTPS atau localhost.');
                    return;
                }

                if (!('BarcodeDetector' in window) || !navigator.mediaDevices?.getUserMedia) {
                    this.fail('Browser belum mendukung scanner kamera. Gunakan Chrome atau Edge terbaru.');
                    return;
                }

                try {
                    this.detector = new BarcodeDetector({
                        formats: ['ean_13', 'ean_8', 'upc_a', 'upc_e', 'code_128', 'code_39', 'code_93', 'itf', 'qr_code'],
                    });
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } },
                        audio: false,
                    });
                    this.$refs.video.srcObject = this.stream;
                    await this.$refs.video.play();
                    this.scanning = true;
                    this.state = 'scanning';
                    this.message = 'Arahkan barcode ke dalam kotak hijau.';
                    this.scan();
                } catch (error) {
                    this.fail('Kamera tidak dapat dibuka. Pastikan izin kamera diberikan.');
                }
            },

            async scan() {
                if (!this.scanning || !this.detector || !this.$refs.video) return;

                try {
                    const results = await this.detector.detect(this.$refs.video);

                    if (results.length > 0) {
                        const code = results[0].rawValue;
                        this.scanning = false;
                        await livewire.set('data.barcode', code);
                        this.state = 'success';
                        this.message = `Barcode ${code} berhasil dimasukkan.`;
                        this.stopCamera();
                        return;
                    }
                } catch (error) {
                    this.message = 'Barcode belum terbaca. Pastikan label terang dan tidak buram.';
                }

                this.frameId = requestAnimationFrame(() => this.scan());
            },

            fail(message) {
                this.state = 'error';
                this.message = message;
                this.stop();
            },

            stopCamera() {
                if (this.frameId) cancelAnimationFrame(this.frameId);
                this.frameId = null;
                this.stream?.getTracks().forEach((track) => track.stop());
                this.stream = null;
                if (this.$refs.video) this.$refs.video.srcObject = null;
            },

            stop() {
                this.scanning = false;
                this.stopCamera();
                this.detector = null;
            },

            destroy() {
                this.stop();
            },
        });
    </script>
</div>
