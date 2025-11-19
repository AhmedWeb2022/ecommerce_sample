<?php

namespace App\Modules\Product\Http\Controllers\Dashboard\Cart;

use App\Http\Controllers\Controller;
use App\Modules\Product\Application\UseCases\Cart\CartUseCase;
use App\Modules\Product\Http\Requests\Dashboard\Cart\ConfirmCartRequest;
use App\Modules\Product\Http\Requests\Dashboard\Cart\CreateCartRequest;
use App\Modules\Product\Http\Requests\Dashboard\Cart\FetchCartRequest;
use App\Modules\Product\Http\Requests\General\Cart\CartIdRequest;

class CartController extends Controller
{

    public function __construct(private CartUseCase $cartUseCase) {}

    public function fetchCarts(FetchCartRequest $request)
    {
        return $this->cartUseCase->fetchCarts($request->toDTO())->response();
    }

    public function createCart(CreateCartRequest $request)
    {
        return $this->cartUseCase->createCart($request->toDTO())->response();
    }

    public function fetchCartDetails(CartIdRequest $request)
    {
        return $this->cartUseCase->fetchCartDetails($request->toDTO())->response();
    }

    public function deleteCart(CartIdRequest $request)
    {
        return $this->cartUseCase->deleteCart($request->toDTO())->response();
    }


    public function confirmCart(ConfirmCartRequest $request)
    {
        return $this->cartUseCase->confirmCart($request->toDTO())->response();
    }
}
