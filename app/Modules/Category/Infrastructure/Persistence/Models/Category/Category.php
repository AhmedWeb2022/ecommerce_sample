<?php

namespace App\Modules\Category\Infrastructure\Persistence\Models\Category;

use App\Modules\Category\Infrastructure\Persistence\Models\Brand\Brand;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;

class Category extends Model
{
    use HasFactory;


    protected $table = 'categories';

    protected $fillable = [
        'title',
        'subtitle',
        'is_active',
        'image',
        'slug',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    public function ImageLink(): Attribute
    {
        if (isset($this->image) && Storage::disk('public')->exists($this->image)) {
            return Attribute::make(fn() => asset('storage/' . $this->image));
        } else {
            return Attribute::make(fn() => "");
        }
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
