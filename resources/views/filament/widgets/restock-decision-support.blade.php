<x-filament-widgets::widget>
    <style>
        .saw-shell { overflow: hidden; border: 1px solid #263f56; border-radius: 8px; background: #0d2035; color: rgb(241 245 249); }
        .saw-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 20px 22px; border-bottom: 1px solid rgb(51 65 85); }
        .saw-eyebrow { color: rgb(250 204 21); font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .saw-head h2 { margin-top: 5px; font-size: 18px; font-weight: 750; }
        .saw-head p { margin-top: 5px; color: rgb(148 163 184); font-size: 13px; }
        .saw-method { display: inline-flex; align-items: center; gap: 7px; border: 1px solid rgb(161 98 7); border-radius: 999px; color: rgb(253 224 71); font-size: 12px; font-weight: 700; padding: 7px 12px; white-space: nowrap; }
        .saw-content { display: grid; grid-template-columns: minmax(260px, .9fr) minmax(0, 1.8fr); gap: 18px; padding: 20px; }
        .saw-winner { display: flex; flex-direction: column; min-height: 360px; border: 1px solid rgb(133 77 14); border-radius: 8px; background: linear-gradient(120deg, #102a43, #15353d); padding: 20px; }
        .saw-priority { align-self: flex-start; border-radius: 999px; background: rgb(250 204 21); color: rgb(24 24 27); font-size: 11px; font-weight: 800; padding: 7px 11px; }
        .saw-sku { margin-top: 20px; color: rgb(250 204 21); font-size: 11px; font-weight: 800; }
        .saw-winner h3 { margin-top: 6px; font-size: clamp(21px, 2vw, 30px); font-weight: 500; line-height: 1.2; }
        .saw-copy { margin-top: 12px; color: rgb(148 163 184); font-size: 13px; line-height: 1.55; }
        .saw-score-main { display: flex; align-items: end; justify-content: space-between; gap: 12px; margin-top: 18px; border-top: 1px solid rgb(71 85 105); border-bottom: 1px solid rgb(71 85 105); padding: 14px 0; }
        .saw-score-main span { color: rgb(148 163 184); font-size: 11px; }
        .saw-score-main strong { color: rgb(253 224 71); font-size: 26px; font-variant-numeric: tabular-nums; }
        .saw-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin-top: 14px; }
        .saw-metric { border: 1px solid #30475f; border-radius: 8px; background: #102a43; padding: 10px; }
        .saw-metric span { display: block; color: rgb(148 163 184); font-size: 10px; }
        .saw-metric strong { display: block; margin-top: 5px; font-size: 15px; }
        .saw-detail { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 20px; color: rgb(253 224 71); font-size: 12px; font-weight: 700; text-decoration: none; }
        .saw-list { overflow: hidden; border: 1px solid rgb(51 65 85); border-radius: 8px; }
        .saw-list-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 16px; border-bottom: 1px solid rgb(51 65 85); }
        .saw-list-head strong { display: block; font-size: 13px; }
        .saw-list-head small { display: block; margin-top: 3px; color: rgb(125 211 252); font-size: 10px; }
        .saw-list-head > span { color: rgb(125 211 252); font-size: 10px; }
        .saw-row { display: grid; grid-template-columns: 34px minmax(150px, .95fr) minmax(130px, 1.5fr) 68px; align-items: center; gap: 14px; min-height: 68px; padding: 10px 16px; border-bottom: 1px solid rgb(30 41 59); }
        .saw-row:last-child { border-bottom: 0; }
        .saw-rank { display: grid; width: 30px; height: 30px; place-items: center; border-radius: 7px; background: #102a43; color: rgb(203 213 225); font-size: 12px; font-weight: 800; }
        .saw-row:first-child .saw-rank { background: rgb(250 204 21); color: rgb(24 24 27); }
        .saw-product { min-width: 0; }
        .saw-product strong, .saw-product small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .saw-product strong { font-size: 12px; }
        .saw-product small { margin-top: 3px; color: rgb(100 116 139); font-size: 10px; }
        .saw-bar { height: 7px; overflow: hidden; border-radius: 999px; background: #071424; }
        .saw-bar span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, rgb(234 179 8), rgb(253 224 71)); }
        .saw-bar-label { display: block; margin-top: 5px; color: rgb(100 116 139); font-size: 9px; }
        .saw-row-score { color: rgb(253 224 71); font-size: 13px; font-weight: 800; text-align: right; font-variant-numeric: tabular-nums; }
        .saw-foot { display: flex; flex-wrap: wrap; gap: 22px; padding: 13px 20px; border-top: 1px solid rgb(51 65 85); color: rgb(125 211 252); font-size: 10px; }
        .saw-foot span::before { display: inline-block; width: 7px; height: 7px; margin-right: 7px; border-radius: 50%; background: rgb(250 204 21); content: ''; }
        .saw-foot span:nth-child(2)::before { background: rgb(52 211 153); }
        .saw-foot span:nth-child(3)::before { background: rgb(56 189 248); }
        .saw-empty { padding: 48px 20px; color: rgb(148 163 184); text-align: center; }
        html:not(.dark) .saw-shell { border-color: #c9d6e2; background: #f9fbfc; color: #102a43; box-shadow: 0 12px 28px rgb(15 42 67 / .08); }
        html:not(.dark) .saw-head, html:not(.dark) .saw-foot { border-color: #d6e0e8; }
        html:not(.dark) .saw-head p { color: #667c91; }
        html:not(.dark) .saw-winner { border-color: #e4bd62; background: linear-gradient(120deg, #f8fafc, #fff8e7); }
        html:not(.dark) .saw-copy, html:not(.dark) .saw-score-main span, html:not(.dark) .saw-metric span { color: #667c91; }
        html:not(.dark) .saw-score-main { border-color: #d6e0e8; }
        html:not(.dark) .saw-score-main strong, html:not(.dark) .saw-detail, html:not(.dark) .saw-row-score { color: #b77905; }
        html:not(.dark) .saw-metric { border-color: #d6e0e8; background: rgb(255 255 255 / .75); }
        html:not(.dark) .saw-list { border-color: #c9d6e2; background: white; }
        html:not(.dark) .saw-list-head, html:not(.dark) .saw-row { border-color: #e1e8ee; }
        html:not(.dark) .saw-list-head small, html:not(.dark) .saw-list-head > span, html:not(.dark) .saw-foot { color: #52799a; }
        html:not(.dark) .saw-rank, html:not(.dark) .saw-bar { background: #e7edf3; color: #536f87; }
        html:not(.dark) .saw-product small, html:not(.dark) .saw-bar-label { color: #71849c; }
        @media (max-width: 900px) { .saw-content { grid-template-columns: 1fr; } .saw-winner { min-height: 340px; } }
        @media (max-width: 640px) { .saw-head { align-items: flex-start; flex-direction: column; } .saw-content { padding: 12px; } .saw-row { grid-template-columns: 30px minmax(0, 1fr) 62px; gap: 9px; } .saw-progress { grid-column: 2 / 4; } .saw-metrics { grid-template-columns: 1fr; } }
    </style>

    @php
        $rankings = $this->getRankings();
        $winner = $rankings->first();
    @endphp

    <section class="saw-shell">
        <header class="saw-head">
            <div>
                <div class="saw-eyebrow">Sistem Pendukung Keputusan</div>
                <h2>Prioritas Restock Metode SAW</h2>
                <p>Top 5 rekomendasi periode {{ $this->getPeriodLabel() }}.</p>
            </div>
            <div class="saw-method"><x-filament::icon icon="heroicon-o-calculator" class="h-4 w-4" /> Simple Additive Weighting</div>
        </header>

        @if ($winner)
            <div class="saw-content">
                <article class="saw-winner">
                    <span class="saw-priority">#1 Prioritas utama</span>
                    <div class="saw-sku">{{ $winner['product']->sku }}</div>
                    <h3>{{ $winner['product']->name }}</h3>
                    <p class="saw-copy">Barang dengan nilai preferensi tertinggi untuk segera dilakukan restock.</p>
                    <div class="saw-score-main"><span>Nilai preferensi</span><strong>{{ number_format($winner['score'], 4, ',', '.') }}</strong></div>
                    <div class="saw-metrics">
                        <div class="saw-metric"><span>Frekuensi</span><strong>{{ $winner['frequency'] }}x</strong></div>
                        <div class="saw-metric"><span>Pemakaian</span><strong>{{ $winner['usage'] }}</strong></div>
                        <div class="saw-metric"><span>Sisa stok</span><strong>{{ $winner['remaining_stock'] }}</strong></div>
                    </div>
                    <a class="saw-detail" href="{{ $this->getProductUrl($winner['product']->id) }}"><span>Lihat detail barang</span><x-filament::icon icon="heroicon-o-arrow-right" class="h-4 w-4" /></a>
                </article>

                <div class="saw-list">
                    <div class="saw-list-head"><div><strong>Peringkat rekomendasi</strong><small>Semakin tinggi skor, semakin mendesak prioritas restock.</small></div><span>Skor SAW</span></div>
                    @foreach ($rankings as $index => $row)
                        <div class="saw-row">
                            <span class="saw-rank">{{ $index + 1 }}</span>
                            <div class="saw-product"><strong>{{ $row['product']->name }}</strong><small>{{ $row['product']->sku }} · Sisa {{ $row['remaining_stock'] }} {{ $row['product']->unit }}</small></div>
                            <div class="saw-progress"><div class="saw-bar"><span style="width: {{ min(100, $row['score'] * 100) }}%"></span></div><small class="saw-bar-label">{{ $row['frequency'] }}x frekuensi · {{ $row['usage'] }} terjual</small></div>
                            <strong class="saw-row-score">{{ number_format($row['score'], 4, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <footer class="saw-foot"><span>Frekuensi benefit 33,3%</span><span>Jumlah terjual benefit 33,3%</span><span>Sisa stok cost 33,3%</span></footer>
        @else
            <div class="saw-empty">Belum ada produk aktif untuk dianalisis.</div>
        @endif
    </section>
</x-filament-widgets::widget>
