<?php

namespace App\Modules\Order\Application\UseCases\Order;

use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Base\Application\Response\DataSuccess;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use App\Modules\Order\Http\Resources\Dashboard\Order\OrderResource;
use App\Modules\Order\Http\Resources\Dashboard\Order\OrderDetailsResource;
use App\Modules\Order\Http\Resources\Api\Order\OrderDetailsResource as MobileOrderDetailsResource;
use App\Modules\Order\Http\Resources\Api\Order\OrderResource as MobileOrderResource;
use App\Modules\Order\Application\DTOS\Order\OrderFilterDTO;
use App\Modules\Order\Application\DTOS\OrderItem\OrderItemDTO;
use App\Modules\Order\Http\Resources\OrderItem\OrderItemResource;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use App\Modules\Order\Domain\Exceptions\OrderStockException;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;
use App\Modules\Order\Infrastructure\Persistence\Repositories\Order\OrderRepository;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Cart\CartRepository;
use App\Modules\Order\Infrastructure\Persistence\Repositories\OrderItem\OrderItemRepository;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Product\ProductRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderUseCase
{

    protected $orderRepository;
    protected $orderItemRepository;
    protected $cartRepository;
    protected $productRepository;
    protected $user;
    protected $employee;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = new OrderItemRepository();
        $this->cartRepository = new CartRepository(); // Assuming you have a CartRepository similar to OrderItemRepository
        $this->productRepository = $productRepository;
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }


    public function fetchOrders(OrderFilterDTO $orderFilterDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // add user_id to dto if not exist and auth user exist
            if (!$orderFilterDTO->user_id && $this->user) {
                $orderFilterDTO->user_id = $this->user->id;
            }
            $orders = $this->orderRepository->filter(
                $orderFilterDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $orderFilterDTO->paginate,
                limit: $orderFilterDTO->limit
            );

            $resource = OrderResource::collection($orders);
            $resource = $orderFilterDTO->paginate ? $resource->response()->getData(true) : $resource;
            // Log::info($resource->toArray());
            return DataSuccess(
                status: true,
                message: 'Order fetched successfully',
                data: $resource,
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchOrderDetails(OrderFilterDTO $orderFilterDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $order = $this->orderRepository->getById($orderFilterDTO->order_id);
            $resource = OrderResource::make($order);
            return DataSuccess(
                status: true,
                message: 'Order Details fetched successfully',
                resourceData: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }





    public function createOrder(OrderDTO $orderDTO): DataStatus
    {
        try {
            // dd($orderDTO);
            $user_id = isset($orderDTO->user_id) ? $orderDTO->user_id : (isset($this->user->id) ? $this->user->id : null);

            $orderDTO->status = OrderStatusTypeEnum::AWAITING_APPROVAL->value;

            $orderDTO->user_id = $user_id;
            $cartItems = $this->cartRepository->getWhere('user_id', $user_id, 'get');
            if (!$cartItems || $cartItems->isEmpty()) {
                return DataFailed(
                    status: false,
                    statusCode: Response::HTTP_NOT_ACCEPTABLE,
                    message: 'Cart is empty, please add items to your cart before placing an order.'
                );
            }
            DB::beginTransaction();
            // dd($orderDTO->toArray());
            $order = $this->orderRepository->create($orderDTO);
            // dd($cartItems);
            $cartItems->each(function ($cartItem) use ($order) {

                /** @var Product $cartItemProduct */
                $cartItemProduct = $cartItem->product;
                $orderItemDTO = OrderItemDTO::fromArray([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'single_price' => $cartItemProduct->price,
                    'price' => $cartItemProduct->price * $cartItem->quantity,
                ]);
                // dd($orderItemDTO);
                $orderItem = $this->orderItemRepository->create($orderItemDTO);
            });

            /* $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->purchase_price * $item->quantity;
            }); */
            // $totalPrice = $order->items->sum('price');
            // $order->update([
            //     'total' => $totalPrice,
            // ]);

            // Clear the user's cart
            $cartItems->each->delete();

            //step 4: update the product stock
            if (!isset($orderDTO->products)) {
                $orderDTO->products = $order->items()->select('product_id', 'quantity')->get();
            }
            $order->save();
            DB::commit();

            return DataSuccess(
                status: true,
                message: 'Order created successfully.',
                data: new OrderResource($order)
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return DataFailed(
                status: false,
                message: 'Order creation failed: ' . $e->getMessage()
            );
        }
    }



    public function fetchOrderProducts(OrderFilterDTO $orderDTO): DataStatus
    {
        try {
            $order = $this->orderRepository->getById($orderDTO->order_id);

            if (!$order) {
                return DataFailed(
                    status: false,
                    message: 'Order not found.'
                );
            }

            $orderItems = $this->orderItemRepository->getWhere('order_id', $order->id, 'get');

            return DataSuccess(
                status: true,
                message: 'Order products fetched successfully',
                data: OrderItemResource::collection($orderItems)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function deleteOrder(OrderFilterDTO $orderFilterDTO): DataStatus
    {
        try {
            $order = $this->orderRepository->delete($orderFilterDTO->order_id);
            return DataSuccess(
                status: true,
                message: ' Order deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
