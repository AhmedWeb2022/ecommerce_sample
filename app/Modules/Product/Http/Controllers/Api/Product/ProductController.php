<?php

namespace App\Modules\Product\Http\Controllers\Api\Product;


use App\Http\Controllers\Controller;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Product\Application\UseCases\Product\ProductUseCase;
use App\Modules\Product\Http\Requests\Api\Product\FetchProductRequest;
use App\Modules\Product\Http\Requests\Api\Product\ProductIdRequest;

class ProductController extends Controller
{
    protected $productUseCase;
    public function __construct(ProductUseCase $productUseCase)
    {
        $this->productUseCase = $productUseCase;
    }

    public function fetchProducts(fetchProductRequest $request)
    {

        return $this->productUseCase->fetchProducts($request->toDTO())->response();
    }

    public function fetchProductDetails(ProductIdRequest $request)
    {
        return $this->productUseCase->fetchProductDetails($request->toDTO(), ViewTypeEnum::MOBILE->value)->response();
    }
}
