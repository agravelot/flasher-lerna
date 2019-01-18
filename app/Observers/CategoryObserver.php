<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param Category $category
     */
    public function created(Category $category)
    {
        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'created'])
            ->log('');
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'updated'])
            ->log('');
    }

    /**
     * Handle the category "deleted" event.
     *
     * @return void
     */
    public function deleted()
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'deleted'])
            ->log('');
    }
}
