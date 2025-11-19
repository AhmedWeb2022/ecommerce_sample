<?php

namespace App\Modules\Product\Application\UseCases\Cart;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Product\Application\DTOS\Cart\CartDTO;
use App\Modules\Product\Application\DTOS\Cart\CartFilterDTO;
use App\Modules\Product\Http\Resources\Api\Cart\CartResource;
use App\Modules\General\Http\Resources\General\Home\HomeResource;
use App\Modules\Product\Http\Resources\Api\Cart\CartConfirmResource;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Cart\CartRepository;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Product\ProductRepository;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB as FacadesDB;

class CartUseCase
{
    protected $cartRepository;
    protected $productRepository;

    protected $user;
    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;

        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
    }

    public function fetchCarts(CartFilterDTO $cartFilterDTO): DataStatus
    {
        try {
            $cartFilterDTO->user_id = isset($cartFilterDTO->user_id) ? $cartFilterDTO->user_id : $this->user->id; // Set user_id from authenticated user
            $carts = $this->cartRepository->filter(
                $cartFilterDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $cartFilterDTO->paginate,
                limit: $cartFilterDTO->limit
            );
            $resource = $cartFilterDTO->paginate ? CartResource::collection($carts)->response()->getData(true) : CartResource::collection($carts);
            return DataSuccess(
                status: true,
                message: 'Cart fetched successfully',
                data: $resource,
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function fetchCartDetails(CartFilterDTO $cartFilterDTO): DataStatus
    {
        try {
            $cart = $this->cartRepository->getById($cartFilterDTO->cart_id);
            return DataSuccess(
                status: true,
                message: 'Course Cart fetched successfully',
                data: new CartResource($cart)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createCart(CartDTO $cartDTO): DataStatus
    {
        try {
            FacadesDB::beginTransaction();
            $cartDTO->user_id = $this->user->id;
            $cart = $this->cartRepository->getMultibleWhere([
                'user_id' => $this->user->id,
                'product_id' => $cartDTO->product_id
            ], 'first');
            $quantities = $this->cartRepository->getCartsQuantitySummation($cartDTO->product_id, $this->user->id);
            if ($cart) {
                // dd($quantities);
                $countQuantity = $cart->product->stock - $quantities;
                // dd($cart->quantity , $countQuantity);
                if ($cartDTO->quantity > $countQuantity) {
                    return DataFailed(
                        status: false,
                        message: 'Cart quantity must be less than or equal to stock still available: ' . $countQuantity,
                        // statusCode: Response::HTTP_NOT_ACCEPTABLE,
                    );
                }
                $cartDTO->cart_id = $cart->id;
                $cartDTO->quantity += $cart->quantity;
                $cart = $this->cartRepository->update($cartDTO->cart_id, $cartDTO);
            } else {
                $cart = $this->cartRepository->create($cartDTO);
            }
            FacadesDB::commit();
            return DataSuccess(
                status: true,
                message: ' Cart created successfully',
                data: new CartResource($cart)
            );
        } catch (\Exception $e) {
            FacadesDB::rollBack();
            $statusCode = $this->handleStatusCode($e);
            return DataFailed(
                status: false,
                message: $e->getMessage(),
                // statusCode: $statusCode,
            );
        }
    }

    public function updateCart(CartDTO $cartDTO): DataStatus
    {
        try {
            $cartDTO->user_id = $this->user->id; // Set user_id from authenticated user
            $cart = $this->cartRepository->update($cartDTO->cart_id, $cartDTO);

            return DataSuccess(
                status: true,
                message: ' Cart updated successfully',
                data: new CartResource($cart)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCart(CartFilterDTO $cartFilterDTO): DataStatus
    {
        try {
            $cart = $this->cartRepository->delete($cartFilterDTO->cart_id);
            return DataSuccess(
                status: true,
                message: ' Cart deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function confirmCart(?CartFilterDTO $cartFilterDTO = null): DataStatus
    {
        try {
            $user_id = isset($cartFilterDTO?->user_id) ? $cartFilterDTO->user_id : $this->user->id;
            $carts = $this->cartRepository->getWhere('user_id', $user_id, 'get');
            if (!$carts) {
                return DataFailed(
                    status: false,
                    message: 'Cart not found'
                );
            }
            $exceededItems = [];

            foreach ($carts as $cart) {
                if ($cart->quantity > $cart->product->stock) {
                    $exceededItems[] = [
                        'id' => $cart->id,
                        'stock' => $cart->product->stock,
                    ];
                }
            }
            if (!empty($exceededItems)) {
                return DataSuccess(
                    status: false,
                    statusCode: 422,
                    message: 'Cart quantity must be less than or equal to stock',
                    data: [
                        'success' => false,
                        'tax_amount' => 0,
                        'stocks' => $exceededItems
                    ]
                );
            }
            return DataSuccess(
                status: true,
                message: 'Cart confirmed successfully',
                data: [
                    'success' => true,
                    'tax_amount' => 0,
                    'stocks' => []
                ]
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    private function handleStatusCode(\Exception $e)
    {

        $statusCode = $e->getCode() && $e->getCode() != 0 && $e->getCode() < 500
            ? $e->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR; // or another default
        return $statusCode;
    }
}
