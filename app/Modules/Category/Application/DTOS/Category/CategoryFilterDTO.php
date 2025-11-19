<?php

namespace App\Modules\Category\Application\DTOS\Category;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CategoryFilterDTO extends BaseDTOAbstract
{
    public $category_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return []; // Default empty array
    }
}
