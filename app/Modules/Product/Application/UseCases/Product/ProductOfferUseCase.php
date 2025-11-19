<?php

namespace App\Modules\Product\Application\UseCases\Product;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Product\Application\DTOS\Product\ProductDTO;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferDTO;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferFilterDTO;

use App\Modules\Product\Infrastructure\Persistence\Repositories\Product\ProductOfferRepository;
use App\Modules\Product\Http\Resources\Api\ProductOffer\ProductOfferResource as MobileProductOfferResource;
use App\Modules\Product\Http\Resources\Api\Product\ProductDetailResource as MobileProductDetailResource;
use App\Modules\Product\Http\Resources\Dashboard\ProductOffer\ProductOfferResource;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use App\Modules\Product\Infrastructure\Persistence\Repositories\Product\ProductRepository;
use Illuminate\Http\Response;

class ProductOfferUseCase
{

    protected $ProductOfferRepository;
    protected $productRepository;
    protected $employee;

    public function __construct(ProductOfferRepository $ProductOfferRepository, ProductRepository $productRepository)
    {
        $this->ProductOfferRepository = $ProductOfferRepository;
        $this->productRepository = $productRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchProducts(ProductOfferFilterDTO $productFilterDto, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($productFilterDto);
            $productsOffers = $this->ProductOfferRepository->filter(
                $productFilterDto,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $productFilterDto->paginate,
                limit: $productFilterDto->limit,
            );


            $resource = $this->HandelProductResource(
                $productsOffers,
                $view,
                $productFilterDto->paginate
            );
            return DataSuccess(
                status: true,
                message: 'Products offers fetched successfully',
                data: $productFilterDto->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchProductDetails(ProductOfferFilterDTO $productFilterDto, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($productFilterDto);
            /* if (isset($productFilterDto->international_code) && !empty($productFilterDto->international_code)) {

                $product = $this->ProductRepository->getWhere('international_code', $productFilterDto->international_code, 'first');
            } else if (isset($productFilterDto->product_id) && !empty($productFilterDto->product_id)) {

                $product = $this->ProductRepository->getById($productFilterDto->product_id);
            } */
           $productOffer = $this->ProductOfferRepository->getById($productFilterDto->product_offer_id);
            $resource = $this->HandelProductDetailResource(
                $productOffer,
                $view,
                false
            );
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

    public function createProduct(ProductOfferDTO $productDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            //check if this product has offer to create or update it
            /** @var Product $product */
            $product = $this->productRepository->getWhere('id', $productDTO->product_id, 'first');
            if($product->getEntity()->hasOffer()){
                /* $productDTO->updated_by = $this->employee->id;
                $productOffer = $this->ProductOfferRepository->update($productDTO);
                $productOffer->refresh(); */
                DB::rollBack();
                return DataFailed(
                    status: false,
                    statusCode: Response::HTTP_NOT_ACCEPTABLE,
                    message: 'Product already has offer please just update it'
                );
            }else{
                $productDTO->created_by = $this->employee->id;
                $productOffer = $this->ProductOfferRepository->create($productDTO);
                $productOffer->refresh();
                DB::commit();
                return DataSuccess(
                    status: true,
                    message: 'product offer created successfully',
                    data: new ProductOfferResource($productOffer)
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

    public function updateProduct(ProductOfferDTO $productDTO): DataStatus
    {
        try {
            $productDTO->updated_by = $this->employee->id;
            $product = $this->ProductOfferRepository->update($productDTO->id, $productDTO);
            $product->refresh();
            return DataSuccess(
                status: true,
                message: 'Product updated successfully',
                data: new ProductOfferResource($product)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteProduct(ProductOfferFilterDTO $productFilterDto): DataStatus
    {
        try {
            $this->ProductOfferRepository->delete($productFilterDto->id);

            return DataSuccess(
                status: true,
                message: 'Product offer deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    private function HandelProductResource($categories, $viewType, $paginate)
    {
        if ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return ProductOfferResource::collection($categories);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return MobileProductOfferResource::collection($categories);
        } else {
            return ProductOfferResource::collection($categories);
        }
    }

    public function HandelProductDetailResource($product, $viewType, $paginate)
    {
        if ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return ProductOfferResource::make($product);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return MobileProductOfferResource::make($product);
        } else {
            return ProductOfferResource::make($product);
        }
    }
}
