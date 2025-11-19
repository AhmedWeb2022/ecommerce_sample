<?php

namespace App\Modules\Base\Domain\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Application\DTOS\Base\BaseDTO;
use App\Modules\Base\Application\DTOS\Base\BaseFilterDTO;
use App\Modules\Base\Infrastructure\Persistence\Models\Base\Base;

interface BaseRepositoryInterface
{
    public function getAll(bool $paginate = true, int $limit = 10): Collection|LengthAwarePaginator;
    public function getById(int $id): Model;
    public function create(BaseDTOInterface $dto): Model;
    public function update(int $id, BaseDTOInterface $dto): Model;
    public function delete(int $id): bool;
    public function filter(BaseDTOInterface $dto): Collection|LengthAwarePaginator;
}
