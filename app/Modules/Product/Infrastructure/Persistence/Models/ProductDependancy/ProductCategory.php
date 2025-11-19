<?php

namespace App\Modules\Product\Infrastructure\Persistence\Models\ProductDependancy;

use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'is_active',
        'product_id',
        'category_id',
    ];

    public function ImageLink(): Attribute
    {
        if (isset($this->image) &&  Storage::disk('public')->exists($this->image)) {
            return Attribute::make(fn() => asset('storage/' . $this->image));
        } else {
            return Attribute::make(fn() => "");
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
