<?php

namespace App\Modules\Category\Http\Controllers\Api\Category;


use App\Http\Controllers\Controller;
use App\Modules\Category\Application\UseCases\Category\CategoryUseCase;
use App\Modules\Category\Http\Requests\Api\Category\CategoryIdRequest;
use App\Modules\Category\Http\Requests\Api\Category\FetchCategoryRequest;

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
}
