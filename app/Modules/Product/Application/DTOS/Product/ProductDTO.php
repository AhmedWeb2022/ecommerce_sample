<?php

namespace App\Modules\Product\Application\DTOS\Product;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ProductDTO extends BaseDTOAbstract
{
    public $product_id;
    public $title;
    public $subtitle;
    public $description;
    public $price;
    public $stock;
    public $category_id;
    public $image;
    public $updated_by;
    public $created_by;

    protected string $imageFolder = 'products'; // fallback folder
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
