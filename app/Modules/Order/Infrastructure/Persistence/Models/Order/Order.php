<?php

namespace App\Modules\Order\Infrastructure\Persistence\Models\Order;

use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserAddress\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Order\Infrastructure\Persistence\Models\Hashtag\Hashtag;
use App\Modules\Order\Infrastructure\Persistence\Models\Category\Category;
use App\Modules\Order\Infrastructure\Persistence\Models\OrderHistory\OrderHistory;
use App\Modules\Order\Infrastructure\Persistence\Models\OrderItem\OrderItem;
use App\Modules\Order\Infrastructure\Persistence\Models\RejectReason\RejectReason;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'user_id',
        'image',
        'quantity',
        'total',

    ];
    protected $table = 'orders';
    protected $appends  = ["image_link"];





    public function taxAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->total_after_tax > 0 ? $this->total_after_tax - $this->total_after_discount : 0;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
