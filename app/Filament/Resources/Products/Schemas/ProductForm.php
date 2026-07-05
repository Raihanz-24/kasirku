<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->description('Data yang dipakai kasir saat mencari dan menjual produk.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama produk')
                            ->placeholder('Contoh: Indomie Goreng')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('sku')
                            ->label('SKU / Kode internal')
                            ->default(fn (): string => Product::generateSku())
                            ->helperText('Boleh diganti. Sistem menyiapkan kode otomatis untuk produk baru.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->placeholder('Scan atau isi manual')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->suffixAction(
                                Action::make('scanBarcodeCamera')
                                    ->label('Scan dengan kamera')
                                    ->icon('heroicon-o-camera')
                                    ->color('gray')
                                    ->modalHeading('Scan Barcode Produk')
                                    ->modalDescription('Arahkan kamera ke barcode. Nilai akan otomatis masuk ke field barcode.')
                                    ->modalContent(view('filament.components.product-barcode-camera'))
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Tutup Kamera'),
                            ),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('name'),
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih kategori'),
                        Select::make('unit')
                            ->label('Satuan')
                            ->options([
                                'pcs' => 'pcs',
                                'pack' => 'pack',
                                'dus' => 'dus',
                                'kg' => 'kg',
                                'gram' => 'gram',
                                'liter' => 'liter',
                                'botol' => 'botol',
                                'sachet' => 'sachet',
                            ])
                            ->searchable()
                            ->required()
                            ->default('pcs'),
                    ])
                    ->columns(2),

                Section::make('Harga')
                    ->description('Harga disimpan dalam rupiah tanpa desimal.')
                    ->schema([
                        TextInput::make('cost_price')
                            ->label('Harga modal')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp'),
                        TextInput::make('selling_price')
                            ->label('Harga jual')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp'),
                    ])
                    ->columns(2),

                Section::make('Stok')
                    ->description('Stok awal bisa diisi saat membuat produk. Penambahan berikutnya lewat Barang Masuk.')
                    ->schema([
                        TextInput::make('current_stock')
                            ->label('Stok saat ini')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('minimum_stock')
                            ->label('Stok minimum')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Produk akan ditandai menipis saat stok menyentuh angka ini.'),
                    ])
                    ->columns(2),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Produk aktif')
                            ->helperText('Produk nonaktif tidak akan dipakai pada transaksi penjualan.')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
