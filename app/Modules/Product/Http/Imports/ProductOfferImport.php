<?php

namespace App\Modules\Product\Http\Imports;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Product\Domain\Enums\OfferTypeEnum;
use App\Modules\Product\Domain\Enums\TaxTypeEnum;
use App\Modules\Product\Domain\Traits\ImportProductConstTrait;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function PHPUnit\Framework\isInt;



class ProductOfferImport implements ToCollection, WithHeadingRow
{
    use ImportProductConstTrait;
    public function __construct(public BaseDTOInterface $dto)
    {
    }
    /**
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        // Step 1: Prepare product data
        $productsToInsert = [];
        $translationsToUpsert = [];
        $productOffersToInsert = [];

        foreach ($rows as $row) {
            $rowData = $row->toArray();

            // Format expire date
            $expireDate = null;
            if (!empty($rowData[self::EXPIRE_DATE])) {
                // $rawDate = $rowData[self::EXPIRE_DATE];

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

            // Base product data
            $internationalCode = $rowData[self::INTERNATIONAL_CODE] ?? null;
            $stock = $rowData[self::STOCK] ?? 0;

            // Build product data without stock (will be added conditionally)
            $is_active = !empty($rowData[self::IS_ACTIVE]) ? (bool) $rowData[self::IS_ACTIVE] : true;
            $categoryId = $this->dto->subcategory_id ?? $this->dto->category_id ?? null;
            if (!empty($categoryId)) {
                $categorySlug = $rowData[self::CATEGORY_SLUG] ?? null;
                $categoryId = isset($categorySlug) ? DB::table('categories')->where('slug', $categoryId)->first()?->id : null;
            }
            $brandId = $this->dto->brand_id ?? null;
            if (!empty($brandId)) {
                $brandSlug = $rowData[self::BRAND_SLUG] ?? null;
                $brandId = isset($brandSlug) ? DB::table('brands')->where('slug', $brandSlug)->first()?->id : null;
            }
            $baseProductData = [
                'public_price' => $rowData[self::PUBLIC_PRICE] ?? null,
                'purchase_price' => $rowData[self::PURCHASE_PRICE] ?? 0,
                'expire_date' => $expireDate,
                'has_taxes' => $rowData[self::HAS_TAXES] ?? false, // Assuming 'has_taxes' is a column in the import file
                'is_active' => $is_active,
                'is_for_sale' => !empty($rowData[self::IS_FOR_SALE]) ? (bool) $rowData[self::IS_FOR_SALE] : true,
                'carton_quantity' => $rowData[self::CARTON_QUANTITY] ?? 0,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'updated_by' => $this->dto->updated_by ?? null,
                'updated_at' => now(),
            ];





            if (!empty($internationalCode)) {
                // Check if product exists
                $existingProduct = DB::table('products')
                    ->where('international_code', $internationalCode)
                    ->select('id', 'international_code', 'stock')
                    ->first();

                if (!$existingProduct) {
                    $productData = array_merge($baseProductData, [
                        'international_code' => $internationalCode,
                        'stock' => $stock,
                        'created_by' => $this->dto->created_by ?? null,
                        'created_at' => now(),
                    ]);
                    $productsToInsert[] = $productData;
                } else {
                    // Update existing product stock
                    $newStock = $existingProduct->stock + $stock;
                    DB::table('products')
                        ->where('international_code', $internationalCode)
                        ->update(array_merge($baseProductData, ['stock' => $newStock]));
                }

                // Prepare base product offer data
                $from_date = null;
                $to_date = null;
                if (!empty($rowData[self::FROM_DATE])) {
                    $from_date = Carbon::createFromFormat('d/m/Y', $rowData[self::FROM_DATE])->format('Y-m-d');
                }
                if (!empty($rowData[self::TO_DATE])) {
                    $to_date = Carbon::createFromFormat('d/m/Y', $rowData[self::TO_DATE])->format('Y-m-d');
                }
                $productOffersToInsert[] = [
                    'offer_type' => !empty($rowData[self::OFFER_TYPE]) ? OfferTypeEnum::tryFromLabel(strtolower($rowData[self::OFFER_TYPE]))->value ?? OfferTypeEnum::FIXED->value : OfferTypeEnum::FIXED->value,
                    'offer_price' => $rowData[self::OFFER_PRICE] ?? null,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'is_active' => $is_active,
                    'product_id' => $existingProduct->id ?? null, // Will be updated later
                ];
                // Prepare translation data
                $translationData = [];
                foreach (['en', 'ar'] as $locale) {
                    $translationData[] = [
                        'locale' => $locale,
                        'title' => $row[self::TITLE . '_' . $locale] ?? null,
                        'description' => $row[self::DESCRIPTION . '_' . $locale] ?? null,
                        'international_code' => $internationalCode,
                    ];
                }
                // Save translation data
                $translationsToUpsert[] = $translationData;
            }
        }

        // Step 2: Insert new products
        if (!empty($productsToInsert)) {
            DB::table('products')->insertOrIgnore($productsToInsert);
        }

        // Get all product IDs (new and existing)
        $internationalCodes = array_merge(
            array_column($productsToInsert, 'international_code'),
            array_map(fn($offer) => $offer['product_id'] ?? null, $productOffersToInsert)
        );

        $insertedProducts = DB::table('products')
            ->whereIn('international_code', array_filter($internationalCodes))
            ->select('id', 'international_code')
            ->get()
            ->keyBy('international_code')
            ->toArray();

        // Map product IDs to offers
        $offerRows = [];
        foreach ($productOffersToInsert as $index => $offer) {
            $internationalCode = $rows[$index][self::INTERNATIONAL_CODE] ?? null;
            $productId = $insertedProducts[$internationalCode]->id ?? null;

            if ($productId) {
                $offer['product_id'] = $productId;
                $offerRows[] = $offer;
            }
        }

        // Step 5: Map translations to product_id
        $translationRows = [];
        foreach ($translationsToUpsert as $translationGroup) {
            foreach ($translationGroup as $trans) {
                $internationalCode = $trans['international_code'] ?? null;
                $productId = $insertedProducts[$internationalCode]->id ?? null;

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

        // Step 6: Upsert translations
        if (!empty($translationRows)) {
            DB::table('product_translations')->upsert(
                $translationRows,
                ['product_id', 'locale'], // Unique key
                ['title', 'description']  // Fields to update
            );
        }


        // Step 7: Upsert product offers
        if (!empty($productOffersToInsert)) {
            DB::table('product_offers')->insert($productOffersToInsert);
        }
    }
}
