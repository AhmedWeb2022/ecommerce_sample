<?php

namespace App\Modules\Category\Http\Observers;

use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryObserver
{

    /**
     * Category Observer constructor
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the Vendor "created" event.
     *
     * @param Category $Category
     * @return void
     */
    public function created(Category $Category)
    {

        $name = $Category->translate('en')->title;

        if (!isset($name) || empty($name)) {
            $name = 'category';
        }
        if(isset($Category->parent_id) && !empty($Category->parent_id)) {
            $main_title = $Category->parent->translate('en')->title;
            $name = $main_title.' '.$name;
        }
        
        $slug = makeSlugFromTitle($name);

        if (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        
        $Category->update(['slug' => $slug]);

        $Category->refresh();
        // $Category->searchable();
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param Category $Category
     * @return void
     */
    public function updated(Category $Category)
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param Category $Category
     * @return void
     */
    public function deleted(Category $Category)
    {
        //
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @param Category $Category
     * @return void
     */
    public function restored(Category $Category)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @param Category $Category
     * @return void
     */
    public function forceDeleted(Category $Category)
    {
        //
    }
}
