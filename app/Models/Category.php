<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            $category->name = Str::title((string) $category->name);
            $category->slug = filled($category->slug)
                ? Str::slug($category->slug)
                : Str::slug($category->name);
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
