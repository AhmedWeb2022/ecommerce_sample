<?php

namespace App\Modules\Category\Application\UseCases\Category;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Category\Application\DTOS\Category\UpdateCategoryBrandsDTO;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Category\Application\DTOS\Category\CategoryDTO;
use App\Modules\Category\Http\Resources\Api\Search\SearchResource;
use App\Modules\Category\Application\DTOS\Category\CategoryFilterDTO;
use App\Modules\Category\Application\DTOS\Category\CategoryProductDTO;
use App\Modules\Category\Http\Resources\Dashboard\Category\CategoryResource;
use App\Modules\Category\Http\Resources\Dashboard\Category\CategoryDetailResource;
use App\Modules\Category\Infrastructure\Persistence\Repositories\Brand\BrandRepository;
use App\Modules\Category\Infrastructure\Persistence\Repositories\Category\BrandCategoryRepository;
use App\Modules\Category\Infrastructure\Persistence\Repositories\Category\CategoryRepository;
use App\Modules\Category\Http\Resources\Api\Category\CategoryResource as MobileCategoryResource;
use App\Modules\Category\Http\Resources\Api\Category\CategoryDetailResource as MobileCategoryDetailResource;
use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use Illuminate\Support\Facades\Log;

class CategoryUseCase
{

    protected $categoryRepository;
    protected $brandRepository;
    protected $categoryBrandRepository;
    protected $employee;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchCategories(CategoryFilterDTO $categoryFilterDto, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($categoryFilterDto);
            $categories = $this->categoryRepository->filter(
                $categoryFilterDto,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $categoryFilterDto->paginate,
                limit: $categoryFilterDto->limit
            );
            // dd($categories);
            $resource = CategoryResource::collection($categories);
            return DataSuccess(
                status: true,
                message: 'Categorys fetched successfully',
                data: $categoryFilterDto->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchCategoryDetails(CategoryFilterDTO $categoryFilterDto, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $category = $this->categoryRepository->getById($categoryFilterDto->category_id);
            $resource = CategoryResource::make($category);
            return DataSuccess(
                status: true,
                message: 'Category fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createCategory(CategoryDTO $categoryDTO): DataStatus
    {
        try {
            $categoryDTO->created_by = $this->employee->id;
            // dd($categoryDTO->toArray());
            $category = $this->categoryRepository->create($categoryDTO);
            $category->refresh();
            return DataSuccess(
                status: true,
                message: 'category created successfully',
                data: new CategoryResource($category)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateCategory(CategoryDTO $categoryDTO): DataStatus
    {
        try {
            $categoryDTO->updated_by = $this->employee->id;
            $category = $this->categoryRepository->update($categoryDTO->category_id, $categoryDTO);
            return DataSuccess(
                status: true,
                message: 'Category updated successfully',
                data: new CategoryResource($category)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCategory(CategoryFilterDTO $categoryFilterDto): DataStatus
    {
        try {
            /** @var Category $category */
            $category = $this->categoryRepository->getById($categoryFilterDto->category_id);
            if ($category->children->count() > 0) {
                return DataFailed(
                    status: false,
                    statusCode: 400,
                    message: 'Category has children'
                );
            } elseif ($category->products->count() > 0) {
                return DataFailed(
                    status: false,
                    statusCode: 400,
                    message: 'Category has products'
                );
            }
            $this->categoryRepository->delete($categoryFilterDto->category_id);

            return DataSuccess(
                status: true,
                message: 'Category deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
