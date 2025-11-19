<?php

namespace App\Modules\Product\Application\DTOS\Product;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ProductFilterDTO extends BaseDTOAbstract
{
    public $product_id;
    public $category_id;
    public function __construct(array $data = [])
    {
        $this->handleSpecialCases();
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
        ];
    }

    public function handleSpecialCases()
    {

    }
}
