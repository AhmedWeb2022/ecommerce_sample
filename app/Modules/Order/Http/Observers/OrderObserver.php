<?php

namespace App\Modules\Order\Http\Observers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;
use App\Modules\Notification\Application\DTOS\NotificationUser\NotificationUserDTO;
use App\Modules\Notification\Infrastructure\Persistence\Repositories\Notification\NotificationRepository;
use App\Modules\Notification\Infrastructure\Persistence\Repositories\NotificationUser\NotificationUserRepository;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;
use App\Modules\Order\Infrastructure\Persistence\Models\OrderHistory\OrderHistory;
use App\Modules\Order\Infrastructure\Persistence\Repositories\Order\OrderRepository;

class OrderObserver
{

    /**
     * Order Observer constructor
     *
     * @return void
     */
    protected $user;
    public function __construct()
    {
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
    }

    /**
     * Handle the Vendor "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order)
    {
        // Generate dynamic order number: e.g. ORD-20250626-000001
        $order->order_number = 'ORD-' . now()->format('Ymd') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $order->saveQuietly(); // Avoid infinite observer loop
        // $order->searchable();
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            OrderHistory::create([
                'order_id'           => $order->id,
                'status'             => $order->status,
                'user_id'            => $order->user_id, // fallback if run from system
                'reject_reason_id'   => $order->reject_reason_id,
                'reject_reason_note' => $order->reject_reason_note,
            ]);
            // update product stock when status changed to anything except approved
            $NotificationRepository = new NotificationRepository();

            if ($order->status == OrderStatusTypeEnum::REJECTED->value) {
                $orderRepository = new OrderRepository();
                $orderRepository->revertProductStock($order);
                // $order->searchable();
                $title = 'Order ' . $order->order_number . ' is rejected';
                $subtitle = 'Your order ' . $order->order_number . ' is rejected';
            } elseif ($order->status == OrderStatusTypeEnum::CANCELLED->value) {
                // $order->searchable();
                $orderRepository = new OrderRepository();
                $orderRepository->revertProductStock($order);
                $title = 'Order ' . $order->order_number . ' is cancelled';
                $subtitle = 'You have cancelled your order ' . $order->order_number;
            } elseif ($order->status == OrderStatusTypeEnum::AWAITING_APPROVAL->value) {
                // $order->searchable();
                $title = 'Order ' . $order->order_number . ' is waiting for approval';
                $subtitle =  'Your order ' . $order->order_number . ' is waiting for approval';
            } elseif ($order->status == OrderStatusTypeEnum::AWAITING_RECEIPT->value) {
                // $order->searchable();
                $title = 'Order ' . $order->order_number . ' is waiting for receipt';
                $subtitle = 'Your order ' . $order->order_number . ' is waiting for receipt';
            } elseif ($order->status == OrderStatusTypeEnum::PEREPERATION->value) {
                $title = 'Order ' . $order->order_number . ' is being processed';
                $subtitle = 'Your order ' . $order->order_number . ' is being processed';
            } elseif ($order->status == OrderStatusTypeEnum::DELIVERED->value) {
                $title = 'Order ' . $order->order_number . ' is delivered';
                $subtitle = 'Your order ' . $order->order_number . ' is delivered';
            } elseif ($order->status == OrderStatusTypeEnum::WAITING_INVOICE_CHECK->value) {
                $title = 'Order ' . $order->order_number . ' is waiting for invoice check';
                $subtitle = 'Your order ' . $order->order_number . ' is waiting for invoice check';
            }elseif ($order->status == OrderStatusTypeEnum::INSHIPPING->value) {
                $title = ' Order ' . $order->order_number . ' is being shipped'; // Your order '. $order->order_number .
                $subtitle = ' Your order ' . $order->order_number . ' is being shipped';
            }

            // send notification to user
            $NotificationDTO = NotificationDTO::fromArray([
                'title' => $title ,
                'subtitle' => $subtitle,
                'userIds' => [$order->user_id],
            ]);
            $NotificationRepository->SendNotification($NotificationDTO);

            // create notification to user in the database
            $notification = $NotificationRepository->create($NotificationDTO);
            $NotificationUserDTO = NotificationUserDTO::fromArray([
                'notification_id' => $notification->id,
                'user_id' => $order->user_id,
            ]);
            $NotificationUserRepository = new NotificationUserRepository();
            $NotificationUserRepository->create($NotificationUserDTO);
        }
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
