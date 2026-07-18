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

        if (!navigator.mediaDevices?.getUserMedia) {
            this.fail('Kamera tidak tersedia di browser ini.');
            return;
        }

        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                },
                audio: false,
            });

            this.$refs.video.srcObject = this.stream;

            if (this.$refs.video.readyState < 1) {
                await new Promise((resolve) => {
                    this.$refs.video.addEventListener('loadedmetadata', resolve, { once: true });
                });
            }

            await this.$refs.video.play();

            if (!('BarcodeDetector' in window)) {
                this.state = 'error';
                this.message = 'Kamera sudah terbuka, tapi browser ini belum mendukung pembaca barcode otomatis. Gunakan Chrome/Edge terbaru atau isi barcode manual.';
                return;
            }

            this.detector = new BarcodeDetector({
                formats: ['ean_13', 'ean_8', 'upc_a', 'upc_e', 'code_128', 'code_39', 'code_93', 'itf', 'qr_code'],
            });
            this.scanning = true;
            this.state = 'scanning';
            this.message = 'Arahkan barcode ke dalam kotak hijau.';
            this.scan();
        } catch (error) {
            this.fail(this.cameraErrorMessage(error));
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

    cameraErrorMessage(error) {
        if (error?.name === 'NotAllowedError') return 'Izin kamera ditolak. Izinkan kamera melalui pengaturan situs browser.';
        if (error?.name === 'NotFoundError') return 'Kamera tidak ditemukan pada perangkat ini.';
        if (error?.name === 'NotReadableError') return 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.';
        if (error?.name === 'OverconstrainedError') return 'Kamera belakang tidak tersedia. Coba buka ulang scanner atau isi barcode manual.';

        return 'Kamera tidak dapat dibuka. Muat ulang halaman lalu coba kembali.';
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

        if (this.$refs.video) {
            this.$refs.video.pause();
            this.$refs.video.srcObject = null;
        }
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
