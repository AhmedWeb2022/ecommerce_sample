<?php

namespace App\Modules\Category\Http\Observers;

use App\Modules\Category\Infrastructure\Persistence\Models\Brand\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandObserver
{

    /**
     * Brand Observer constructor
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the Vendor "created" event.
     *
     * @param Brand $Brand
     * @return void
     */
    public function created(Brand $Brand)
    {

        $name = $Brand->translate('en')->title;

        if(isset($Brand->parent_id) && !empty($Brand->parent_id)) {
            $main_title = $Brand->parent->translate('en')->title;
            $name = $main_title.' '.$name;
        }
        if (!isset($name) || empty($name)) {
            $name = 'brand';
        }
        if(isset($Brand->parent_id) && !empty($Brand->parent_id)) {
            $main_title = $Brand->parent->translate('en')->title;
            $name = $main_title.' '.$name;
        }
        
        $slug = makeSlugFromTitle($name);

        if (DB::table('brands')->where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        
        $Brand->update(['slug' => $slug]);

        $Brand->refresh();
        // $Brand->searchable();
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param Brand $Brand
     * @return void
     */
    public function updated(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param Brand $Brand
     * @return void
     */
    public function deleted(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @param Brand $Brand
     * @return void
     */
    public function restored(Brand $Brand)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @param Brand $Brand
     * @return void
     */
    public function forceDeleted(Brand $Brand)
    {
        //
    }
}
