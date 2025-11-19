<?php

namespace App\Modules\Product\Infrastructure\Persistence\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Translatable;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Category\Infrastructure\Persistence\Models\Brand\Brand;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
    ];

    public function getImageLinkAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getAllQuantityForProduct($productId): int
    {
        return $this->where('product_id', $productId)->sum('quantity');
    }
}
