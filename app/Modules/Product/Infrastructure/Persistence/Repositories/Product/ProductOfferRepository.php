<?php

namespace App\Modules\Product\Infrastructure\Persistence\Repositories\Product;


use App\Modules\Order\Application\Enums\Order\OrderDurationTypeEnum;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Product\Http\Imports\AddProductOfferImport;
use App\Modules\Product\Http\Imports\ProductOfferImport;
use App\Modules\Product\Http\Imports\UpdateProductImport;
use App\Modules\Product\Infrastructure\Persistence\Models\ProductDependancy\ProductOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;

class ProductOfferRepository extends BaseRepositoryAbstract
{
    private $excel;
    public function __construct(Excel $excel)
    {
        $this->setModel(new ProductOffer());
        $this->excel = $excel;
    }

    public function addImport(BaseDTOInterface $dto): bool
    {
        // dd($dto->file);
        try {
            DB::beginTransaction();
            if (!$dto->file) {
                throw new Exception('File is required for import.');
            }
            $import = new ProductOfferImport($dto);
            $productsData = $this->excel->import($import, $dto->file);

            DB::commit();
            return true;
        } catch (Exception $e) {
            // DB::rollBack();
            $dto::rollbackUploads();
            throw $e;
        }
    }

    public function updateImport(BaseDTOInterface $dto): bool
    {
        // dd($dto->file);
        try {
            DB::beginTransaction();
            if (!$dto->file) {
                throw new Exception('File is required for import.');
            }
            $import = new UpdateProductImport($dto);
            $productsData = $this->excel->import($import, $dto->file);

            DB::commit();
            return true;
        } catch (Exception $e) {
            // DB::rollBack();
            $dto::rollbackUploads();
            throw $e;
        }
    }


    public function filter(
        BaseDTOInterface $dto,
        string $operator = 'like',
        array $translatableFields = [],
        $paginate = false,
        $limit = 10,
        array $whereHasRelations = [],
        array $whereHasMultipleRelations = []
    ): Collection|LengthAwarePaginator {
        try {
            $query = $this->getModel()->query();

            /* $query->when(isset($dto->is_parent) && $dto->is_parent, function ($q) use ($dto, $operator) {
                $q->whereNull('parent_id');
            }); */

            $query->when(isset($dto->word), function ($q) use ($dto, $translatableFields, $operator) {
                $q->whereHas('product', function ($sub_q) use ($dto, $translatableFields, $operator) {
                    $sub_q->where(function ($inner_q) use ($dto, $translatableFields, $operator) {
                        foreach ($translatableFields as $field) {
                            $inner_q->orWhereTranslationLike($field, "%{$dto->word}%");
                        }
                    });
                });
            });

            // dd($query->get());

            $query->when(isset($dto->duration_id), function ($q) use ($dto) {
                $date = match ($dto->duration_id) {
                    OrderDurationTypeEnum::LAST_THREE_MONTH->value => Carbon::now()->subMonths(3),
                    OrderDurationTypeEnum::LAST_SIX_MONTH->value => Carbon::now()->subMonths(6),
                    OrderDurationTypeEnum::LAST_YEAR->value => Carbon::now()->subYear(),
                    default => null,
                };

                if ($date) {
                    $q->where('created_at', '>=', $date);
                }
            });

            foreach ($dto->toArray() as $key => $value) {
                if (!in_array($key, ['lat', 'lng', 'distance']) && filled($value)) {
                    $query
                        ->when(in_array($key, $translatableFields), fn($q) => $q->whereTranslationLike($key, "%{$value}%"))
                        ->when(is_array($value), fn($q) => $q->whereIn($key, $value))
                        ->when(is_bool($value), fn($q) => $q->where($key, $value))
                        ->when(is_numeric($value), fn($q) => $q->where($key, $operator, $value))
                        ->when(is_string($value) && !in_array($key, $translatableFields), fn($q) => $q->where($key, $operator, ($operator === 'like' ? "%{$value}%" : $value)));
                }
            }

            // Apply whereHas relations with whereIn
            foreach ($whereHasRelations as $relation => $conditions) {
                // dd($relation, $conditions);
                $query->whereHas($relation, function ($q) use ($conditions, $relation) {
                    // dd($conditions);
                    /* foreach ($conditions as $key => $values) {
                        // dd($key, $values);
                        if (is_array($values)) {
                            // dd($key,$values);
                            $q->whereIn($key, $values); // Use whereIn for arrays
                            // dd($q->get());
                        } else {
                            $q->where($key, $values); // Use where for single values
                        }
                    } */
                    if (is_callable($conditions)) {
                        // If it's a closure, execute it
                        $conditions($q);
                    } elseif (is_array($conditions)) {
                        foreach ($conditions as $key => $values) {
                            // dd($key, $values);
                            if (is_array($values)) {
                                // dd($key,$values);
                                $q->whereIn($key, $values); // Use whereIn for arrays
                                // dd($q->get());
                            } else {
                                $q->where($key, $values); // Use where for single values
                            }
                        }
                    }
                });
            }
            // dd($query->toSql());

            // Apply whereHasMultiple relations with whereIn
            foreach ($whereHasMultipleRelations as $relationsGroup) {
                foreach ($relationsGroup as $relation => $conditions) {
                    $query->whereHas($relation, function ($q) use ($conditions) {
                        foreach ($conditions as $key => $values) {
                            if (is_array($values)) {
                                $q->whereIn($key, $values); // Use whereIn for arrays
                            } else {
                                $q->where($key, $values); // Use where for single values
                            }
                        }
                    });
                }
            }
            // Order by nearest location if lat & lng exist
            $query->when(isset($dto->lat) && isset($dto->lng), function ($query) use ($dto) {
                $query->select('*', DB::raw("(
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(lat))
                    )
                ) AS distance", [$dto->lat, $dto->lng, $dto->lat]))
                    ->orderBy('distance', 'asc');
            })->when(isset($dto->lat) && isset($dto->lng) && isset($dto->distance), function ($query) use ($dto) {
                $query->select('*', DB::raw("(
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(lat))
                    )
                ) AS distance", [$dto->lat, $dto->lng, $dto->lat]))
                    ->having('distance', '<=', $dto->distance)
                    ->orderBy('distance', 'asc');
            });

            return $paginate ? $query->orderBy($dto->orderBy, $dto->direction)->paginate($limit) : $query->orderBy($dto->orderBy, $dto->direction)->get();
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }
}
