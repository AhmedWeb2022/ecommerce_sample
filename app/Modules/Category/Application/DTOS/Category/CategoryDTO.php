<?php

namespace App\Modules\Category\Application\DTOS\Category;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CategoryDTO extends BaseDTOAbstract
{
    public $category_id;
    public $title;
    public $subtitle;
    public $parent_id;
    public $image;
    public $created_by;
    public $updated_by;
    public $order;
    public $is_active = true;
    public $products_ids;
    // public $slug;
    protected string $imageFolder = 'categories'; // fallback folder
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


}
