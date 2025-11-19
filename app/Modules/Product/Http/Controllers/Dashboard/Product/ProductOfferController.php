<?php

namespace App\Modules\Product\Http\Controllers\Dashboard\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Product\Application\UseCases\Product\ProductOfferUseCase;

use App\Modules\Product\Http\Requests\Dashboard\ProductOffer\CreateProductOfferRequest;
use App\Modules\Product\Http\Requests\Dashboard\ProductOffer\FetchProductOfferRequest;
use App\Modules\Product\Http\Requests\Dashboard\ProductOffer\ProductOfferIdRequest;
use App\Modules\Product\Http\Requests\Dashboard\ProductOffer\UpdateProductOfferRequest;
use Illuminate\Support\Facades\Log;

class ProductOfferController extends Controller
{
    protected $productOfferUseCase;

    public function __construct(ProductOfferUseCase $productOfferUseCase)
    {
        $this->productOfferUseCase = $productOfferUseCase;
    }

    /**
     *  @OA\Info(
     *     title="Product Offer API",
     *     version="1.1",
     *     description="API documentation for managing Products , video and lessons."
     * )
     * @OA\Post(
     *     path="/dashboard/fetch_products_offers",
     *     summary="Fetch a list of products that has offers",
     *     tags={"Dashboard Product Offers"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", nullable=true, description="Filter by product title", example="Math 101"),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by product type", example="online"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent product ID", example=1),
     *             @OA\Property(property="code", type="string", nullable=true, description="Filter by product code", example="MATH101")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of products Offers",
     *         @OA\JsonContent(type="List")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchProducts(FetchProductOfferRequest $request)
    {
        return $this->productOfferUseCase->fetchProducts($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_product_offer_details",
     *     summary="Fetch details of a specific product",
     *     tags={"Dashboard Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_offer_id", type="integer", description="The ID of the product_offer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product offer details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid product ID"
     *     )
     * )
     */
    public function fetchProductDetails(ProductOfferIdRequest $request)
    {
        return $this->productOfferUseCase->fetchProductDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_product",
     *     summary="Create a new product",
     *     tags={"Dashboard Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Product translations", @OA\Items(type="object")),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent product ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="stage_id", type="integer", nullable=true, description="Stage ID", example=1),
     *             @OA\Property(property="subject_id", type="integer", nullable=true, description="Subject ID", example=1),
     *             @OA\Property(property="type", type="string", description="Product type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Product status", example="active"),
     *             @OA\Property(property="is_private", type="boolean", nullable=true, description="Is the product private?", example=true),
     *             @OA\Property(property="has_website", type="boolean", nullable=true, description="Does the product have a website?", example=false),
     *             @OA\Property(property="has_app", type="boolean", nullable=true, description="Does the product have an app?", example=false),
     *             @OA\Property(property="start_date", type="string", format="date", description="Product start date", example="2025-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", description="Product end date", example="2025-06-30"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Product image URL or file", example="product.jpg"),
     *             @OA\Property(
     *                 property="video",
     *                 type="object",
     *                 nullable=true,
     *                 description="Video details",
     *                 @OA\Property(property="is_file", type="integer", enum={0, 1}, nullable=true, description="Is the video a file? (1 = yes, 0 = no)", example=1),
     *                 @OA\Property(property="video_type", type="string", description="Type of video", example="mp4"),
     *                 @OA\Property(property="file", type="string", description="Video file (required if is_file = 1)", example="video.mp4"),
     *                 @OA\Property(property="link", type="string", description="Video link (required if is_file = 0)", example="https://example.com/video")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createProduct(CreateProductOfferRequest $request)
    {
        return $this->productOfferUseCase->createProduct($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_product_offer",
     *     summary="Update an existing product offer",
     *     tags={"Dashboard Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_id", type="integer", description="The ID of the product to update", example=1),
     *             @OA\Property(property="translations", type="array", nullable=true, description="Product translations", @OA\Items(type="object")),
     *             @OA\Property(property="code", type="string", nullable=true, maxLength=255, description="Product code", example="MATH101"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent product ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Product type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Product status", example="active"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Product image URL or file", example="product.jpg"),
     *             @OA\Property(
     *                 property="video",
     *                 type="object",
     *                 nullable=true,
     *                 description="Video details",
     *                 @OA\Property(property="is_file", type="integer", enum={0, 1}, nullable=true, description="Is the video a file? (1 = yes, 0 = no)", example=1),
     *                 @OA\Property(property="video_type", type="string", description="Type of video", example="mp4"),
     *                 @OA\Property(property="file", type="string", description="Video file (required if is_file = 1)", example="video.mp4"),
     *                 @OA\Property(property="link", type="string", description="Video link (required if is_file = 0)", example="https://example.com/video")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateProduct(UpdateProductOfferRequest $request)
    {
        return $this->productOfferUseCase->updateProduct($request->toDTO())->response();
    }


    /**
     * @OA\Post(
     *     path="/dashboard/delete_product",
     *     summary="Delete a product",
     *     tags={"Dashboard Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_id", type="integer", description="The ID of the product to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid product ID"
     *     )
     * )
     */
    public function deleteProduct(ProductOfferIdRequest $request)
    {
        return $this->productOfferUseCase->deleteProduct($request->toDTO())->response();
    }
}
