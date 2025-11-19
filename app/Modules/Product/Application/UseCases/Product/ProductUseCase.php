<?php

namespace App\Modules\Product\Application\UseCases\Product;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Product\Application\DTOS\Product\ProductDTO;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Product\Application\DTOS\Product\ProductFilterDTO;
use App\Modules\Product\Http\Resources\Dashboard\Product\ProductResource;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Product\ProductRepository;

class ProductUseCase
{

    protected $productRepository;
    protected $employee;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchProducts(ProductFilterDTO $productFilterDto): DataStatus
    {
        try {
            $products = $this->productRepository->filter(
                $productFilterDto,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $productFilterDto->paginate,
                limit: $productFilterDto->limit,
                whereHasMultipleRelations: [
                    'category' => function ($query) use ($productFilterDto) {
                        $query->where('id', $productFilterDto->category_id);
                    }
                ]
            );
            $resource = ProductResource::collection($products);
            return DataSuccess(
                status: true,
                message: 'Products fetched successfully',
                data: $productFilterDto->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchProductDetails(ProductFilterDTO $productFilterDto): DataStatus
    {
        try {
            // dd($productFilterDto);
            $product = $this->productRepository->getById($productFilterDto->product_id);
            $resource = ProductResource::make($product);
            return DataSuccess(
                status: true,
                message: 'Product fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createProduct(ProductDTO $productDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $productDTO->created_by = $this->employee->id;
            $product = $this->productRepository->create($productDTO);
            // dd($product);
            DB::commit();

            return DataSuccess(
                status: true,
                message: 'Product created successfully',
                data: ProductResource::make($product)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function updateProduct(ProductDTO $productDTO): DataStatus
    {
        try {

            $productDTO->updated_by = $this->employee->id;
            $product = $this->productRepository->update($productDTO->product_id, $productDTO);
            return DataSuccess(
                status: true,
                message: 'Product updated successfully',
                data: new ProductResource($product)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function deleteProduct(ProductFilterDTO $productFilterDto): DataStatus
    {
        try {
            $this->productRepository->delete($productFilterDto->product_id);
            return DataSuccess(
                status: true,
                message: 'Product deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
