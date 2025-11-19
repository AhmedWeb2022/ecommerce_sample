<?php

namespace App\Modules\Order\Http\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;

class OrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $orders;

    public function __construct(public ?BaseDTOInterface $dto = null)
    {
        $query = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            // ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'orders.id',
                'orders.order_number',
                'orders.user_id',
                'users.name as user_name',
                'orders.status',
                'user_addresses.address as user_address',
                'orders.phone',
                'orders.total_price',
                'orders.total_after_discount',
                'orders.total_after_tax',
                'orders.created_at',
            );

        if ($dto) {
            if (filled($dto->user_id)) {
                $query->where('orders.user_id', $dto->user_id);
            }
            if (filled($dto->status)) {
                $query->whereIn('orders.status', $dto->status);
            }
        }

        $this->orders = $query->get();
        // return Order::all();
    }
    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            '#',
            'Order Number',
            'User Name',
            'User Address',
            'Status',
            'Phone',
            'Total Price',
            'Total After Discount',
            'Total After Tax',
            'Created At',
        ];
    }

    public function map($row): array
    {
        $statusLabel = OrderStatusTypeEnum::from($row->status)->label();
        return [
            $row->id,
            $row->order_number,
            $row->user_name,
            $row->user_address,
            $statusLabel,
            $row->phone,
            $row->total_price,
            $row->total_after_discount,
            $row->total_after_tax,
            $row->created_at,
        ];
    }
}
