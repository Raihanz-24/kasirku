<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_product_knows_when_stock_is_low(): void
    {
        $product = new Product([
            'name' => 'Gula Pasir',
            'sku' => 'GULA-001',
            'unit' => 'pcs',
            'cost_price' => 15000,
            'selling_price' => 17000,
            'current_stock' => 2,
            'minimum_stock' => 2,
            'is_active' => true,
        ]);

        $this->assertTrue($product->isLowStock());
    }

    public function test_admin_and_owner_roles_can_access_admin_panel(): void
    {
        $panel = Filament::getPanel('admin');

        $owner = new User([
            'role' => UserRole::Owner,
        ]);

        $admin = new User([
            'role' => UserRole::Admin,
        ]);

        $this->assertTrue($owner->canAccessPanel($panel));
        $this->assertTrue($admin->canAccessPanel($panel));
    }

    public function test_product_resource_uses_indonesian_labels_and_slug(): void
    {
        $this->assertSame('produk', ProductResource::getSlug());
        $this->assertSame('Produk', ProductResource::getModelLabel());
        $this->assertSame('Produk', ProductResource::getPluralModelLabel());
    }
}
