<?php

namespace App\Modules\Product\Http\Controllers\Api\Cart;


use App\Http\Controllers\Controller;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Product\Application\UseCases\Cart\CartUseCase;
use App\Modules\Product\Http\Requests\Api\Cart\FetchCartRequest;
use App\Modules\Product\Http\Requests\Api\Cart\CreateCartRequest;
use App\Modules\Product\Http\Requests\Api\Cart\UpdateCartRequest;
use App\Modules\Product\Http\Requests\General\Cart\CartIdRequest;

class CartController extends Controller
{
    protected $cartUseCase;

    public function __construct(CartUseCase $cartUseCase)
    {
        $this->cartUseCase = $cartUseCase;
    }

    public function fetchCarts(FetchCartRequest $request)
    {
        return $this->cartUseCase->fetchCarts($request->toDTO())->response();
    }

    public function fetchCartDetails(CartIdRequest $request)
    {
        return $this->cartUseCase->fetchCartDetails($request->toDTO())->response();
    }


    public function createCart(CreateCartRequest $request)
    {
        return $this->cartUseCase->createCart($request->toDTO())->response();
    }

    public function updateCart(UpdateCartRequest $request)
    {
        return $this->cartUseCase->updateCart($request->toDTO())->response();
    }

    public function deleteCart(CartIdRequest $request)
    {
        return $this->cartUseCase->deleteCart($request->toDTO())->response();
    }

    public function confirmCart()
    {
        return $this->cartUseCase->confirmCart()->response();
    }
}
