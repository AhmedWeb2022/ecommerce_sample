<?php

namespace App\Modules\Order\Infrastructure\Persistence\Models\OrderItem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'single_price',
        'price',
    ];
    protected $table = 'order_items';


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
