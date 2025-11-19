<?php

namespace App\Modules\Product\Infrastructure\Persistence\Models\Product;

use App\Modules\Category\Infrastructure\Persistence\Models\Brand\Brand;
use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use App\Modules\Product\Infrastructure\Persistence\Entities\ProductEntity;
use App\Modules\Product\Infrastructure\Persistence\Models\ProductDependancy\ProductOffer;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    // public $translatedAttributes = ['title', 'subtitle', 'description'];

    protected $table = 'products';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'slug',
        'image',
        'category_id',
        'price',
        'stock',
    ];

    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image && Storage::disk('public')->exists($this->image)) {
                    return asset('storage/' . $this->image);
                }
                return "";
            }
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class, 'product_id');
    }

    public function activeOffers()
    {
        return $this->hasMany(ProductOffer::class, 'product_id')
            ->available();
    }
}
