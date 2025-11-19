<?php

namespace App\Modules\Order\Application\UseCases\Order;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use App\Modules\Order\Application\DTOS\OrderItem\OrderItemDTO;
use App\Modules\Order\Infrastructure\Persistence\Repositories\Order\OrderRepository;
use App\Modules\Order\Infrastructure\Persistence\Repositories\OrderItem\OrderItemRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderItemUseCase
{

    protected $orderItemRepository;
    protected  $orderRepository;
    protected $user;
    protected $employee;

    public function __construct()
    {
        $this->orderItemRepository = new OrderItemRepository();
        $this->orderRepository = new OrderRepository();
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }
    public function deleteOrderItem(OrderItemDTO $orderFilterDTO): DataStatus
    {
        DB::beginTransaction();
        try {
            $orderItem = $this->orderItemRepository->getById($orderFilterDTO->order_item_id);
            if ($orderItem && $orderItem->order_id & $orderItem->product_id) {
                //step 1: update the stock of the product
                $orderDTO = new OrderDTO([
                    'order_id' => $orderItem->order_id,
                    'products' => [
                        [
                            'product_id' => $orderItem->product_id,
                            'quantity' => 0,
                        ]
                    ]
                ]);
                //step 3: delete the order item
                $orderItemDeleteResponse = $this->orderItemRepository->delete($orderFilterDTO->order_item_id);
                DB::commit();
                return DataSuccess(
                    status: true,
                    message: 'Item delete from Order deleted successfully',
                );
            }else{
                DB::rollBack();
                return DataFailed(
                    statusCode: Response::HTTP_NOT_ACCEPTABLE,
                    message: 'Deleting Item not Accepted',
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
