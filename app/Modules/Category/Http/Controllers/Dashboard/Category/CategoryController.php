<?php

namespace App\Modules\Category\Http\Controllers\Dashboard\Category;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Category\Application\UseCases\Category\CategoryUseCase;
use App\Modules\Category\Http\Requests\Dashboard\Category\CategoryIdRequest;
// use App\Modules\Category\Http\Requests\Dashboard\Category\CategoryIdRequest;

use App\Modules\Category\Http\Requests\Dashboard\Category\CreateCategoryRequest;
use App\Modules\Category\Http\Requests\Dashboard\Category\FetchCategoryRequest;

use App\Modules\Category\Http\Requests\Dashboard\Category\UpdateCategoryRequest;


class CategoryController extends Controller
{
    protected $categoryUseCase;

    public function __construct(CategoryUseCase $categoryUseCase)
    {
        $this->categoryUseCase = $categoryUseCase;
    }
    public function fetchCategories(FetchCategoryRequest $request)
    {
        return $this->categoryUseCase->fetchCategories($request->toDTO())->response();
    }
    public function fetchCategoryDetails(CategoryIdRequest $request)
    {
        return $this->categoryUseCase->fetchCategoryDetails($request->toDTO())->response();
    }
    public function createCategory(CreateCategoryRequest $request)
    {
        return $this->categoryUseCase->createCategory($request->toDTO())->response();
    }
    public function updateCategory(UpdateCategoryRequest $request)
    {
        return $this->categoryUseCase->updateCategory($request->toDTO())->response();
    }
    public function deleteCategory(CategoryIdRequest $request)
    {
        return $this->categoryUseCase->deleteCategory($request->toDTO())->response();
    }
}
