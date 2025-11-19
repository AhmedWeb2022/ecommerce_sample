<?php

namespace App\Modules\Product\Http\Imports;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Product\Domain\Enums\TaxTypeEnum;
use App\Modules\Product\Domain\Traits\ImportProductConstTrait;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AddProductImport implements ToCollection, WithHeadingRow
{
    use ImportProductConstTrait;

    public function __construct(public BaseDTOInterface $dto) {}

    public function collection(Collection $rows)
    {
        $requiredColumns = collect([
            self::INTERNATIONAL_CODE,
            self::PUBLIC_PRICE,
            self::PURCHASE_PRICE,
            self::STOCK,
            self::CARTON_QUANTITY,
            // self::CATEGORY_ID,
            // self::SUBCATEGORY_ID,
            // self::BRAND_ID,
            self::HAS_TAXES,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $requiredColumns->push(self::TITLE . '_' . $locale);
            $requiredColumns->push(self::DESCRIPTION . '_' . $locale);
        }

        // Check for required columns
        $missingColumns = array_filter($requiredColumns->toArray(), fn($column) => !array_key_exists($column, $rows->first()?->toArray() ?? []));

        if (!empty($missingColumns)) {
            throw new \Exception('Missing required columns:   : ' . implode(', ', $missingColumns));
        }

        $productsToInsert = [];
        $translationsToInsert = [];

        foreach ($rows as $index => $row) {
            $rowData = $row->toArray();
            // dd($rowData ,$index);

            $this->validateRow($rowData, $index); // ← validate input row


            // $categoryId = $rowData[self::CATEGORY_ID] ?? null;
            // $brandId = $this->dto->brand_id ?? null;
            // if (!empty($brandId)) {
            //     $brandSlug = $rowData[self::BRAND_SLUG] ?? null;
            //     $brandId = isset($brandSlug) ? DB::table('brands')->where('slug', $brandSlug)->first()?->id : null;
            // }
            $expireDate = null;
            if (!empty($row[self::EXPIRE_DATE])) {
                try {
                    $expireDate = Carbon::createFromFormat('d/m/Y', $row[self::EXPIRE_DATE])->format('Y-m-d');
                } catch (\Exception $e1) {
                    try {
                        $expireDate = Carbon::createFromFormat('Y-m-d', $row[self::EXPIRE_DATE])->format('Y-m-d');
                    } catch (\Exception $e2) {
                        try {
                            $expireDate = Carbon::parse($row[self::EXPIRE_DATE])->format('Y-m-d');
                        } catch (\Exception $e3) {
                            FacadesLog::warning("Invalid expire date format", [
                                'value' => $row[self::EXPIRE_DATE],
                                'row' => $row,
                            ]);
                            $expireDate = null;
                        }
                    }
                }
            }

            $productData = [
                'international_code' => $rowData[self::INTERNATIONAL_CODE] ?? null,
                'public_price' => $rowData[self::PUBLIC_PRICE] ?? null,
                'purchase_price' => $rowData[self::PURCHASE_PRICE] ?? null,
                'expire_date' => $expireDate,
                'stock' => $rowData[self::STOCK] ?? null,
                'carton_quantity' => $rowData[self::CARTON_QUANTITY] ?? null,
                'category_id' => $this->dto->subcategory_id ?? $this->dto->category_id ?? null,
                'brand_id' => $this->dto->brand_id ?? null,
                'has_taxes' => $rowData[self::HAS_TAXES] ?? false,
                'created_by' => !empty($this->dto->created_by) ? $this->dto->created_by : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $translationData = [];
            foreach (['en', 'ar'] as $locale) {
                $translationData[] = [
                    'locale' => $locale,
                    'title' => $row[self::TITLE . '_' . $locale] ?? null,
                    'description' => $row[self::DESCRIPTION . '_' . $locale] ?? null,
                    'international_code' => $productData['international_code'] ?? null,
                ];
            }

            if (!empty($productData['international_code'])) {
                $productsToInsert[] = $productData;
                $translationsToInsert[] = $translationData;
            }
        }

        FacadesLog::info('Starting product import', [
            'productCount' => count($productsToInsert),
            'translationsCount' => count($translationsToInsert)
        ]);

        if (!empty($productsToInsert)) {
            collect($productsToInsert)->chunk(500)->each(
                fn($chunk) =>
                DB::table('products')->insertOrIgnore($chunk->toArray())
            );
        }

        $internationalCodes = array_column($productsToInsert, 'international_code');
        $insertedProducts = DB::table('products')
            ->whereIn('international_code', $internationalCodes)
            ->select('id', 'international_code')
            ->get()
            ->keyBy('international_code')
            ->toArray();

        FacadesLog::info('Products inserted or ignored', [
            'internationalCodes' => $internationalCodes,
            'insertedProductsCount' => count($insertedProducts),
            'insertedProducts' => $insertedProducts
        ]);

        $translationRows = [];
        foreach ($translationsToInsert as $translationGroup) {
            foreach ($translationGroup as $trans) {
                $internationalCode = $trans['international_code'] ?? null;
                $productId = $insertedProducts[$internationalCode]->id ?? null;

                if ($productId) {
                    $trans['product_id'] = $productId;
                    unset($trans['international_code']);
                    $translationRows[] = $trans;
                }
            }
        }

        FacadesLog::info('Inserting product translations', [
            'translationRowsCount' => count($translationRows),
            'translationRows' => $translationRows
        ]);

        if (!empty($translationRows)) {
            DB::table('product_translations')->insertOrIgnore($translationRows);
        }
    }

    private function validateRow(array $row, int $index): void
    {
        $rowNumber = $index;

        // $categoryId = $row[self::CATEGORY_ID] ?? null;
        // if (!$categoryId || !DB::table('categories')->where('id', $categoryId)->exists()) {
        //     throw new \Exception("Category not found: $categoryId in row number: $rowNumber");
        // }

        // $subcategoryId = $row[self::SUBCATEGORY_ID] ?? null;
        // if (!$subcategoryId || !DB::table('subcategories')->where('id', $subcategoryId)->exists()) {
        //     throw new \Exception("Subcategory not found: $subcategoryId in row number: $rowNumber");
        // }
        // $brandId = $row[self::BRAND_ID] ?? null;
        // // dd($brandId);
        // if (!$brandId && !DB::table('brands')->where('id', $brandId)->exists()) {
        //     throw new \Exception("Brand not found: $brandId in row number:  $rowNumber");
        // }

        $publicPrice = $row[self::PUBLIC_PRICE] ?? null;
        $purchasePrice = $row[self::PURCHASE_PRICE] ?? null;

        if (!is_numeric($publicPrice) || $publicPrice <= 0) {
            throw new \Exception("Invalid public_price: $publicPrice in row number: $rowNumber");
        }

        if (!is_numeric($purchasePrice) || $purchasePrice <= 0) {
            throw new \Exception("Invalid purchase_price: $purchasePrice in row number: $rowNumber");
        }

        // Format expire date
        /* $expireDate = null;
        if (!empty($row[self::EXPIRE_DATE])) {
            try {
                $expireDate = Carbon::createFromFormat('d/m/Y', $row[self::EXPIRE_DATE])->format('Y-m-d');
            } catch (\Exception $e1) {
                try {
                    $expireDate = Carbon::createFromFormat('Y-m-d', $row[self::EXPIRE_DATE])->format('Y-m-d');
                } catch (\Exception $e2) {
                    try {
                        $expireDate = Carbon::parse($row[self::EXPIRE_DATE])->format('Y-m-d');
                    } catch (\Exception $e3) {
                        FacadesLog::warning("Invalid expire date format", [
                            'value' => $row[self::EXPIRE_DATE],
                            'row' => $row,
                        ]);
                        $expireDate = null;
                    }
                }
            }
        } */
    }
}
