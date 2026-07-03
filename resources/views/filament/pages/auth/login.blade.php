<x-filament-panels::page.simple>
    <style>
        .fi-simple-layout { position: relative; overflow: hidden; background: #071424; color-scheme: dark; }
        .fi-simple-layout::before { position: fixed; inset: 0; background: radial-gradient(circle at 15% 20%, rgb(20 184 166 / .09), transparent 32%), radial-gradient(circle at 86% 82%, rgb(251 191 36 / .08), transparent 28%); content: ''; pointer-events: none; }
        .fi-simple-main-ctn { position: relative; z-index: 1; padding: 28px; }
        .fi-simple-main { width: min(100%, 980px) !important; max-width: 980px !important; overflow: hidden; border: 1px solid #30475f; border-radius: 8px !important; background: #0d2035 !important; box-shadow: 0 32px 80px rgb(0 0 0 / .38) !important; padding: 0 !important; }
        .fi-simple-page-content { gap: 0 !important; }
        .kasirku-login { display: grid; min-height: 590px; grid-template-columns: minmax(0, 1.08fr) minmax(390px, .92fr); }
        .kasirku-brand { position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #385069; background: linear-gradient(145deg, #102a43 0%, #0b3440 58%, #12473f 100%); padding: 48px; color: #f8fafc; }
        .kasirku-brand::before, .kasirku-brand::after { position: absolute; border: 1px solid rgb(255 255 255 / .08); border-radius: 50%; content: ''; }
        .kasirku-brand::before { width: 360px; height: 360px; right: -210px; top: -170px; } .kasirku-brand::after { width: 300px; height: 300px; left: -190px; bottom: -190px; }
        .kasirku-wordmark, .kasirku-message, .kasirku-trust { position: relative; z-index: 1; }
        .kasirku-wordmark { display: flex; align-items: center; gap: 13px; }
        .kasirku-mark { display: grid; width: 45px; height: 45px; place-items: center; border: 1px solid rgb(251 191 36 / .65); border-radius: 8px; background: rgb(251 191 36 / .1); color: #fbbf24; font-size: 21px; font-weight: 850; }
        .kasirku-wordmark strong { display: block; font-size: 20px; letter-spacing: 0; } .kasirku-wordmark small { display: block; margin-top: 2px; color: #9fb2c5; font-size: 11px; }
        .kasirku-eyebrow { color: #fbbf24; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .kasirku-message h1 { max-width: 390px; margin-top: 13px; font-size: clamp(29px, 3vw, 42px); font-weight: 760; line-height: 1.16; }
        .kasirku-message p { max-width: 410px; margin-top: 18px; color: #bdcad7; font-size: 14px; line-height: 1.75; }
        .kasirku-trust { display: flex; align-items: center; gap: 9px; color: #b9f8dd; font-size: 12px; font-weight: 650; }
        .kasirku-trust::before { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 5px rgb(52 211 153 / .11); content: ''; }
        .kasirku-form { display: flex; flex-direction: column; justify-content: center; background: #0d2035; padding: 48px; color: #f8fafc; }
        .kasirku-form-head h2 { color: #f8fafc; font-size: 27px; font-weight: 780; letter-spacing: 0; } .kasirku-form-head p { margin-top: 8px; color: #9fb2c5; font-size: 13px; line-height: 1.55; }
        .kasirku-form-body { margin-top: 30px; }
        .kasirku-form .fi-input-wrp { border-color: #30475f !important; background: #071424 !important; box-shadow: none !important; }
        .kasirku-form .fi-input { color: #e5edf6 !important; }
        .kasirku-form .fi-input::placeholder { color: #71849c !important; }
        .kasirku-form .fi-fo-field-wrp-label, .kasirku-form .fi-fo-field-wrp-label span, .kasirku-form .fi-checkbox-label { color: #cbd7e2 !important; opacity: 1 !important; }
        .kasirku-form .fi-checkbox-input { border-color: #4b6278 !important; background: #071424 !important; }
        .kasirku-form .fi-ac-btn-action { min-height: 44px; border-radius: 7px; background: #f4bf4f !important; color: #102a43 !important; box-shadow: none !important; }
        .kasirku-form .fi-ac-btn-action svg { color: #102a43 !important; }
        .kasirku-form .fi-ac-btn-action:hover { background: #ffd66e !important; }
        .kasirku-install { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; min-height: 42px; margin-top: 12px; border: 1px solid #405a72; border-radius: 7px; background: transparent; color: #cbd7e2; cursor: pointer; font-size: 13px; font-weight: 700; }
        .kasirku-install:hover { border-color: #f4bf4f; color: #f4bf4f; }
        .kasirku-install svg { width: 18px; height: 18px; }
        .kasirku-footnote { margin-top: 28px; color: #71849c; font-size: 11px; text-align: center; }
        @media (max-width: 800px) { .fi-simple-main-ctn { padding: 18px; } .kasirku-login { min-height: auto; grid-template-columns: 1fr; } .kasirku-brand { min-height: auto; border-right: 0; border-bottom: 1px solid #385069; padding: 24px 30px; } .kasirku-wordmark { justify-content: center; } .kasirku-wordmark small, .kasirku-message, .kasirku-trust { display: none; } .kasirku-form { padding: 34px 30px; } }
        @media (max-width: 480px) { .fi-simple-main-ctn { padding: 0; } .fi-simple-main { min-height: 100dvh; border: 0; border-radius: 0 !important; } .kasirku-brand { padding: 24px 22px; } .kasirku-form { justify-content: flex-start; padding: 34px 22px; } .kasirku-form-head { text-align: center; } .kasirku-form-head h2 { font-size: 23px; } .kasirku-form-head p { font-size: 12px; } .kasirku-footnote { display: none; } }
    </style>

    <div class="kasirku-login">
        <aside class="kasirku-brand">
            <div class="kasirku-wordmark"><span class="kasirku-mark">K</span><div><strong>Kasirku</strong><small>Sistem operasional toko</small></div></div>
            <div class="kasirku-message"><div class="kasirku-eyebrow">Kelola toko dengan percaya diri</div><h1>Satu tempat untuk seluruh aktivitas tokomu.</h1><p>Pantau penjualan, stok, saldo, dan keputusan restock melalui sistem yang sederhana dan terpercaya.</p></div>
            <div class="kasirku-trust">Akses aman untuk Owner dan Admin/Kasir</div>
        </aside>
        <main class="kasirku-form">
            <header class="kasirku-form-head"><h2>Selamat datang kembali</h2><p>Masukkan akun Anda untuk melanjutkan ke dashboard Kasirku.</p></header>
            <div class="kasirku-form-body">{{ $this->content }}</div>
            <div x-data="{
                installed: window.matchMedia('(display-mode: standalone)').matches,
                async install() {
                    if (window.kasirkuInstallPrompt) {
                        await window.kasirkuInstallPrompt.prompt();
                        await window.kasirkuInstallPrompt.userChoice;
                        window.kasirkuInstallPrompt = null;
                        return;
                    }

                    alert('Untuk memasang Kasirku, buka menu browser lalu pilih Tambahkan ke layar utama atau Instal aplikasi.');
                }
            }" x-on:kasirku-installed.window="installed = true">
                <button x-show="! installed" type="button" class="kasirku-install" x-on:click="install()">
                    <x-filament::icon icon="heroicon-o-arrow-down-tray" />
                    Unduh Kasirku
                </button>
            </div>
            <p class="kasirku-footnote">Kasirku · Sistem pencatatan toko kelontong</p>
        </main>
    </div>
</x-filament-panels::page.simple>
