<?php

namespace App\Modules\Product\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Modules\Product\Application\UseCases\Product\ProductUseCase;
use App\Modules\Product\Http\Requests\Dashboard\Product\ProductIdRequest;

use App\Modules\Product\Http\Requests\Dashboard\Product\CreateProductRequest;
use App\Modules\Product\Http\Requests\Dashboard\Product\FetchProductRequest;

use App\Modules\Product\Http\Requests\Dashboard\Product\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productUseCase;

    public function __construct(ProductUseCase $productUseCase)
    {
        $this->productUseCase = $productUseCase;
    }
    public function fetchProducts(FetchProductRequest $request)
    {
        return $this->productUseCase->fetchProducts($request->toDTO())->response();
    }
    public function fetchProductDetails(ProductIdRequest $request)
    {
        return $this->productUseCase->fetchProductDetails($request->toDTO())->response();
    }
    public function createProduct(CreateProductRequest $request)
    {
        return $this->productUseCase->createProduct($request->toDTO())->response();
    }
    public function updateProduct(UpdateProductRequest $request)
    {
        return $this->productUseCase->updateProduct($request->toDTO())->response();
    }
    public function deleteProduct(ProductIdRequest $request)
    {
        return $this->productUseCase->deleteProduct($request->toDTO())->response();
    }
}
