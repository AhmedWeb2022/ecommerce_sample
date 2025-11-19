<?php

namespace App\Modules\Category\Infrastructure\Persistence\Repositories\Category;


use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use App\Modules\Order\Application\Enums\Order\OrderDurationTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Category());
    }
}
