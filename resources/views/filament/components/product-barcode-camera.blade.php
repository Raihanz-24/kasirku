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
        <video x-ref="video" class="product-camera__video" autoplay playsinline muted></video>
        <div class="product-camera__guide"></div>
    </div>

    <div
        class="product-camera__status"
        x-bind:class="{ success: state === 'success', error: state === 'error' }"
        x-text="message"
    ></div>

</div>
