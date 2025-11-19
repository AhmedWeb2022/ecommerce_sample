<?php

namespace App\Modules\Product\Http\Observers;

use App\Jobs\BackToStockJob;
use App\Jobs\ProductActivateJob;
use App\Jobs\ProductInActivateJob;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductObserver
{

    /**
     * Product Observer constructor
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the Vendor "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {
        $name = $product->translate('en')->title;

        $slug = makeSlugFromTitle($name);

        if (DB::table('products')->where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $product->update(['slug' => $slug]);

        $product->refresh();
        // $product->searchable();
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @param Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
