<?php

namespace App\Modules\Product\Http\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;

class ProductExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $products;

    public function __construct(public ?BaseDTOInterface $dto = null)
    {
        $query = DB::table('products as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('product_translations as en', function ($join) {
                $join->on('p.id', '=', 'en.product_id')->where('en.locale', 'en');
            })
            ->leftJoin('product_translations as ar', function ($join) {
                $join->on('p.id', '=', 'ar.product_id')->where('ar.locale', 'ar');
            })
            ->select(
                'p.international_code',
                'p.public_price',
                'p.purchase_price',
                'p.expire_date',
                'p.stock',
                'p.carton_quantity',
                'c.slug as category_slug',
                'p.brand_id',
                'en.title as title_en',
                'en.description as description_en',
                'ar.title as title_ar',
                'ar.description as description_ar'
            );

        if ($dto) {
            if (filled($dto->category_id)) {
                $query->where('p.category_id', $dto->category_id);
            }

            if (filled($dto->brand_id)) {
                $query->where('p.brand_id', $dto->brand_id);
            }

            if (filled($dto->subcategory_id)) {
                $query->where('p.category_id', $dto->subcategory_id); // أو ممكن تعدل حسب التصميم
            }
        }

        // if ($dto) {
        //     if (filled($dto->subcategory_id)) {
        //         $query->where('p.category_id', $dto->subcategory_id);
        //     } elseif (filled($dto->category_id)) {
        //         // فلترة على الفئة الرئيسية وأي منتجات تابعة لفروعها
        //         $query->where(function ($q) use ($dto) {
        //             $q->where('c.parent_id', $dto->category_id)
        //                 ->orWhere('p.category_id', $dto->category_id);
        //         });
        //     }

        //     if (filled($dto->brand_id)) {
        //         $query->where('p.brand_id', $dto->brand_id);
        //     }
        // }


        $this->products = $query->get();
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'International Code',
            'Public Price',
            'Purchase Price',
            'Expire Date',
            'Stock',
            'Carton Quantity',
            'Category Slug',
            'Brand ID',
            'Title EN',
            'Description EN',
            'Title AR',
            'Description AR',
        ];
    }

    public function map($row): array
    {
        return [
            $row->international_code,
            $row->public_price,
            $row->purchase_price,
            $row->expire_date,
            $row->stock,
            $row->carton_quantity,
            $row->category_slug,
            $row->brand_id,
            $row->title_en,
            $row->description_en,
            $row->title_ar,
            $row->description_ar,
        ];
    }
}
