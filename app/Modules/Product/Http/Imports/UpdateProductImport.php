<?php

namespace App\Modules\Product\Http\Imports;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Product\Domain\Traits\ImportProductConstTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateProductImport implements ToCollection, WithHeadingRow
{
    use ImportProductConstTrait;

    public function __construct(public BaseDTOInterface $dto)
    {
    }

    public function collection(Collection $rows)
    {
        // Check required columns
        $requiredColumns = collect([
            self::INTERNATIONAL_CODE,
            self::PUBLIC_PRICE,
            self::PURCHASE_PRICE,
            self::STOCK,
            self::CARTON_QUANTITY,
            self::HAS_TAXES,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $requiredColumns->push(self::TITLE . '_' . $locale);
            $requiredColumns->push(self::DESCRIPTION . '_' . $locale);
        }

        $missingColumns = array_filter(
            $requiredColumns->toArray(),
            fn($column) => !array_key_exists($column, $rows->first()?->toArray() ?? [])
        );

        if (!empty($missingColumns)) {
            throw new \Exception('Missing required columns: ' . implode(', ', $missingColumns));
        }

        $productsToInsert = [];
        $productsToUpdate = [];
        $translationsToInsert = [];

        foreach ($rows as $index => $row) {
            $rowData = $row->toArray();

            $this->validateRow($rowData, $index);

            $expireDate = null;
            if (!empty($rowData[self::EXPIRE_DATE])) {
                try {
                    $expireDate = Carbon::createFromFormat('d/m/Y', $rowData[self::EXPIRE_DATE])->format('Y-m-d');
                } catch (\Exception $e1) {
                    try {
                        $expireDate = Carbon::createFromFormat('Y-m-d', $rowData[self::EXPIRE_DATE])->format('Y-m-d');
                    } catch (\Exception $e2) {
                        try {
                            $expireDate = Carbon::parse($rowData[self::EXPIRE_DATE])->format('Y-m-d');
                        } catch (\Exception $e3) {
                            FacadesLog::warning("Invalid expire date format", [
                                'value' => $rowData[self::EXPIRE_DATE],
                                'row' => $rowData,
                            ]);
                            $expireDate = null;
                        }
                    }
                }
            }

            $internationalCode = $rowData[self::INTERNATIONAL_CODE] ?? null;
            $stock = $rowData[self::STOCK] ?? 0;

            $baseProductData = [
                'public_price' => $rowData[self::PUBLIC_PRICE] ?? null,
                'purchase_price' => $rowData[self::PURCHASE_PRICE] ?? null,
                'expire_date' => $expireDate,
                'is_active' => !empty($rowData[self::IS_ACTIVE]) ? (bool) $rowData[self::IS_ACTIVE] : true,
                'is_for_sale' => !empty($rowData[self::IS_FOR_SALE]) ? (bool) $rowData[self::IS_FOR_SALE] : true,
                'carton_quantity' => $rowData[self::CARTON_QUANTITY] ?? null,
                'category_id' => $this->dto->subcategory_id ?? $this->dto->category_id ?? null,
                'brand_id' => $this->dto->brand_id ?? null,
                'has_taxes' => $rowData[self::HAS_TAXES] ?? false,
                'updated_by' => $this->dto->updated_by ?? null,
                'updated_at' => now(),
            ];

            if (!empty($internationalCode)) {
                $existingProduct = DB::table('products')
                    ->where('international_code', $internationalCode)
                    ->select('id', 'stock')
                    ->first();
                if ($existingProduct) {
                    $newStock = $existingProduct->stock + $stock;
                    $productsToUpdate[] = [
                        'international_code' => $internationalCode,
                        'data' => array_merge($baseProductData, ['stock' => $newStock]),
                    ];
                } else {
                    $productsToInsert[] = array_merge($baseProductData, [
                        'international_code' => $internationalCode,
                        'stock' => $stock,
                        'created_by' => $this->dto->created_by ?? null,
                        'created_at' => now(),
                    ]);
                }

                $translationGroup = [];
                foreach (['en', 'ar'] as $locale) {
                    $translationGroup[] = [
                        'locale' => $locale,
                        'title' => $row[self::TITLE . '_' . $locale] ?? null,
                        'description' => $row[self::DESCRIPTION . '_' . $locale] ?? null,
                        'international_code' => $internationalCode,
                    ];
                }
                $translationsToInsert[] = $translationGroup;
            }
        }

        // Insert new products
        if (!empty($productsToInsert)) {
            collect($productsToInsert)->chunk(500)->each(
                fn($chunk) => DB::table('products')->insertOrIgnore($chunk->toArray())
            );
        }

        // Update existing products
        foreach ($productsToUpdate as $product) {
            DB::table('products')
                ->where('international_code', $product['international_code'])
                ->update($product['data']);
        }

        // Get product IDs
        $internationalCodes = array_column(
            array_merge(
                $productsToInsert,
                array_map(fn($p) => ['international_code' => $p['international_code']], $productsToUpdate)
            ),
            'international_code'
        );

        $insertedProducts = DB::table('products')
            ->whereIn('international_code', $internationalCodes)
            ->select('id', 'international_code')
            ->get()
            ->keyBy('international_code')
            ->toArray();

        // Prepare translations
        $translationRows = [];
        foreach ($translationsToInsert as $group) {
            foreach ($group as $trans) {
                $productId = $insertedProducts[$trans['international_code']]->id ?? null;
                if ($productId) {
                    $translationRows[] = [
                        'product_id' => $productId,
                        'locale' => $trans['locale'],
                        'title' => $trans['title'],
                        'description' => $trans['description'],
                    ];
                }
            }
        }

        // Insert/Update translations
        if (!empty($translationRows)) {
            DB::table('product_translations')->upsert(
                $translationRows,
                ['product_id', 'locale'],
                ['title', 'description']
            );
        }

        FacadesLog::info('Product update/import completed', [
            'inserted' => count($productsToInsert),
            'updated' => count($productsToUpdate),
            'translations' => count($translationRows)
        ]);
    }

    private function validateRow(array $row, int|string $index): void
    {
        $rowNumber = (int) $index + 2;

        $publicPrice = $row[self::PUBLIC_PRICE] ?? null;
        $purchasePrice = $row[self::PURCHASE_PRICE] ?? null;
        $stock = $row[self::STOCK] ?? null;

        if (!is_numeric($publicPrice) || $publicPrice <= 0) {
            throw new \Exception("Invalid public_price: $publicPrice in row number: $rowNumber");
        }

        if (!is_numeric($purchasePrice) || $purchasePrice <= 0) {
            throw new \Exception("Invalid purchase_price: $purchasePrice in row number: $rowNumber");
        }
        if (!is_numeric($stock) || $stock < 0) {
            throw new \Exception("Invalid stock: $stock in row number: $rowNumber");
        }
    }
}
