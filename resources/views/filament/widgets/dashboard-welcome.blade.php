<x-filament-widgets::widget>
    <style>
        .welcome-hero { position: relative; overflow: hidden; display: grid; grid-template-columns: minmax(0, 1.8fr) 323px; gap: 30px; min-height: 270px; border: 1px solid rgb(146 106 31 / .65); border-radius: 8px; background: linear-gradient(110deg, #0b1d30 0%, #0d2835 58%, #123c40 100%); padding: 34px; color: #f8fafc; }
        .welcome-hero::before, .welcome-hero::after { position: absolute; width: 270px; height: 270px; border: 1px solid rgb(148 163 184 / .12); border-radius: 50%; content: ''; pointer-events: none; }
        .welcome-hero::before { right: -80px; top: -130px; } .welcome-hero::after { left: 38%; bottom: -230px; }
        .welcome-copy, .welcome-side { position: relative; z-index: 1; }
        .welcome-copy { align-self: center; }
        .welcome-pill { display: inline-flex; align-items: center; gap: 7px; border: 1px solid rgb(202 138 4 / .75); border-radius: 999px; color: #fbbf24; font-size: 12px; font-weight: 750; padding: 5px 10px; }
        .welcome-pill::before { width: 8px; height: 8px; border-radius: 50%; background: #fbbf24; box-shadow: 0 0 0 4px rgb(251 191 36 / .12); content: ''; }
        .welcome-copy h2 { margin-top: 20px; font-size: clamp(27px, 3vw, 38px); font-weight: 760; line-height: 1.15; }
        .welcome-copy h2 span { color: #fbbf24; }
        .welcome-copy p { max-width: 720px; margin-top: 14px; color: #c2cfdd; font-size: 14px; line-height: 1.7; }
        .welcome-status { display: flex; align-items: center; gap: 8px; margin-top: 20px; color: #b9f8dd; font-size: 12px; font-weight: 700; }
        .welcome-status::before { width: 9px; height: 9px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 5px rgb(52 211 153 / .12); content: ''; }
        .welcome-side { display: grid; align-content: center; gap: 12px; }
        .welcome-info { display: grid; grid-template-columns: 43px 1fr; align-items: center; gap: 12px; min-height: 88px; border: 1px solid rgb(201 151 53 / .35); border-radius: 8px; background: rgb(255 255 255 / .07); padding: 14px; }
        .welcome-info-icon { display: grid; width: 43px; height: 43px; place-items: center; border-radius: 8px; background: rgb(148 163 184 / .16); color: #fbbf24; }
        .welcome-info small { display: block; color: #8fa2b6; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .welcome-info strong { display: block; margin-top: 5px; font-size: 20px; font-variant-numeric: tabular-nums; }
        .welcome-info span { display: block; margin-top: 2px; color: #bdc9d5; font-size: 11px; }
        @media (max-width: 900px) { .welcome-hero { grid-template-columns: 1fr; padding: 24px; } .welcome-side { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 580px) { .welcome-hero { padding: 20px; } .welcome-side { grid-template-columns: 1fr; } }
    </style>

    <section class="welcome-hero">
        <div class="welcome-copy">
            <span class="welcome-pill">Dashboard operasional</span>
            <h2>Selamat datang, <span>{{ auth()->user()?->name }}</span></h2>
            <p>Pantau penjualan, pergerakan stok, saldo, dan prioritas restock dalam satu tampilan. Data diperbarui langsung dari aktivitas operasional toko.</p>
            <div class="welcome-status">Sistem aktif · {{ $this->getActiveUsers() }} pengguna dapat mengakses</div>
        </div>
        <div class="welcome-side">
            <div class="welcome-info" x-data="{ time: '{{ now()->format('H.i.s') }}' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID').replaceAll(':', '.'), 1000)">
                <div class="welcome-info-icon"><x-filament::icon icon="heroicon-o-clock" class="h-5 w-5" /></div>
                <div><small>Waktu saat ini</small><strong x-text="time">{{ now()->format('H.i.s') }}</strong><span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span></div>
            </div>
            @if (auth()->user()?->isOwner())
                <div class="welcome-info">
                    <div class="welcome-info-icon"><x-filament::icon icon="heroicon-o-banknotes" class="h-5 w-5" /></div>
                    <div><small>Saldo toko</small><strong>{{ $this->rupiah($this->getCurrentBalance()) }}</strong><span>Saldo tercatat saat ini</span></div>
                </div>
            @endif
        </div>
    </section>
</x-filament-widgets::widget>
