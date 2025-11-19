<?php

namespace App\Modules\Product\Infrastructure\Persistence\Models\ProductDependancy;

use App\Modules\Category\Infrastructure\Persistence\Models\Collection\Collection;
use App\Modules\Category\Infrastructure\Persistence\Models\Label\Label;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class ProductOffer extends Model
{
    use HasFactory;

    protected $table = 'product_offers';

    protected $fillable = [
        'is_active',
        'product_id',
        'offer_price',
        'offer_type',
        'from_date',
        'to_date',
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

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    /**

     * Scope a query to only include active offers and offers that are currently active.

     */

    #[Scope]
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('from_date', '<=', now())->where('to_date', '>=', now());
    }
}
