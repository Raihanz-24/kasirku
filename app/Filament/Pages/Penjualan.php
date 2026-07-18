<?php

namespace App\Filament\Pages;

use App\Actions\Sales\CreateSaleAction;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class Penjualan extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static string | \UnitEnum | null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 30;

    protected static ?string $title = 'Penjualan';

    protected string $view = 'filament.pages.penjualan';

    public ?string $barcode = null;

    public ?string $barcodeMessage = null;

    public ?string $manualProductSearch = null;

    public ?int $manualProductId = null;

    public ?string $manualProductSelectedLabel = null;

    /**
     * @var array<int,array{
     *     product_id:int,
     *     name:string,
     *     sku:string,
     *     unit:string,
     *     stock:int,
     *     selling_price:int,
     *     quantity:int,
     *     subtotal:int
     * }>
     */
    public array $cart = [];

    public ?string $notes = null;

    public ?string $lastInvoiceNumber = null;

    public function addByBarcode(): void
    {
        $this->barcodeMessage = null;

        $code = trim((string) $this->barcode);

        if ($code === '') {
            return;
        }

        $product = Product::query()
            ->where('barcode', $code)
            ->orWhere('sku', $code)
            ->first();

        if (! $product) {
            $this->barcodeMessage = 'Barang tidak terdaftar.';

            Notification::make()
                ->title('Produk tidak ditemukan')
                ->body('Barcode atau SKU tidak cocok dengan produk mana pun.')
                ->danger()
                ->send();

            $this->barcode = null;

            return;
        }

        $this->addProductToCart($product);
        $this->barcode = null;
        $this->barcodeMessage = null;
    }

    public function updatedBarcode(): void
    {
        $this->barcodeMessage = null;
    }

    public function addManualProduct(): void
    {
        if (! $this->manualProductId) {
            return;
        }

        $product = Product::query()->find($this->manualProductId);

        if (! $product) {
            Notification::make()
                ->title('Produk tidak ditemukan')
                ->danger()
                ->send();

            return;
        }

        $this->addProductToCart($product);
        $this->manualProductId = null;
        $this->manualProductSearch = null;
        $this->manualProductSelectedLabel = null;
    }

    public function selectManualProduct(int $productId): void
    {
        $product = Product::query()
            ->where('is_active', true)
            ->find($productId);

        if (! $product) {
            return;
        }

        $this->manualProductId = (int) $product->id;
        $this->manualProductSelectedLabel = $this->formatManualProductLabel($product);
        $this->manualProductSearch = $this->manualProductSelectedLabel;
    }

    public function updatedManualProductSearch(): void
    {
        if ($this->manualProductSearch !== $this->manualProductSelectedLabel) {
            $this->manualProductId = null;
            $this->manualProductSelectedLabel = null;
        }
    }

    public function incrementItem(int $productId): void
    {
        $this->updateQuantity($productId, ($this->cart[$productId]['quantity'] ?? 0) + 1);
    }

    public function decrementItem(int $productId): void
    {
        $this->updateQuantity($productId, ($this->cart[$productId]['quantity'] ?? 0) - 1);
    }

    public function updateQuantity(int $productId, int|float|string $quantity): void
    {
        if (! isset($this->cart[$productId])) {
            return;
        }

        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            $this->removeItem($productId);

            return;
        }

        if ($quantity > $this->cart[$productId]['stock']) {
            Notification::make()
                ->title('Stok tidak cukup')
                ->body("Stok tersedia {$this->cart[$productId]['stock']} {$this->cart[$productId]['unit']}.")
                ->danger()
                ->send();

            $quantity = (int) $this->cart[$productId]['stock'];
        }

        $this->cart[$productId]['quantity'] = $quantity;
        $this->cart[$productId]['subtotal'] = $quantity * $this->cart[$productId]['selling_price'];
    }

    public function removeItem(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->notes = null;
    }

    public function saveSale(): void
    {
        try {
            $sale = app(CreateSaleAction::class)->execute([
                'items' => collect($this->cart)
                    ->values()
                    ->map(fn (array $item): array => [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ])
                    ->all(),
                'occurred_at' => now(),
                'notes' => $this->notes,
            ], auth()->user());
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Penjualan belum bisa disimpan')
                ->body(collect($exception->errors())->flatten()->first())
                ->danger()
                ->send();

            return;
        }

        $this->lastInvoiceNumber = $sale->invoice_number;
        $this->clearCart();

        Notification::make()
            ->title('Penjualan berhasil disimpan')
            ->body("Nomor transaksi: {$sale->invoice_number}")
            ->success()
            ->send();
    }

    /**
     * @return Collection<int,Product>
     */
    public function getManualProducts(): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->when(filled($this->manualProductSearch), function ($query): void {
                $search = trim((string) $this->manualProductSearch);

                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    public function getCartTotal(): int
    {
        return (int) collect($this->cart)->sum('subtotal');
    }

    public function getCartItemCount(): int
    {
        return (int) collect($this->cart)->sum('quantity');
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    public function shouldShowManualProductResults(): bool
    {
        return filled($this->manualProductSearch) && blank($this->manualProductId);
    }

    private function addProductToCart(Product $product): void
    {
        if (! $product->is_active) {
            Notification::make()
                ->title('Produk nonaktif')
                ->danger()
                ->send();

            return;
        }

        if ((int) $product->current_stock <= 0) {
            Notification::make()
                ->title('Stok habis')
                ->body($product->name)
                ->danger()
                ->send();

            return;
        }

        $productId = (int) $product->id;

        if (isset($this->cart[$productId])) {
            $this->incrementItem($productId);

            return;
        }

        $this->cart[$productId] = [
            'product_id' => $productId,
            'name' => $product->name,
            'sku' => $product->sku,
            'unit' => $product->unit,
            'stock' => (int) $product->current_stock,
            'selling_price' => (int) $product->selling_price,
            'quantity' => 1,
            'subtotal' => (int) $product->selling_price,
        ];
    }

    private function formatManualProductLabel(Product $product): string
    {
        return "{$product->name} - stok {$product->current_stock} {$product->unit}";
    }
}
