<?php

namespace App\Http\OpenGraphs;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Contracts\OpenGraphable;
use App\Models\Contracts\ImagesOpenGraphable;

class CategoryOpenGraph implements OpenGraphable, ImagesOpenGraphable
{
    /**
     * @var Category
     */
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function tags(): Collection
    {
        return $this->category->categories()->pluck('name');
    }

    public function updatedAt(): string
    {
        return $this->category->updated_at->toIso8601String();
    }

    public function images(): Collection
    {
        if ($cover = $this->category->cover) {
            return collect([$cover]);
        }

        return collect();
    }

    public function title(): string
    {
        return $this->category->name;
    }

    public function description(): string
    {
        return Str::limit($this->category->description ?? '', 150);
    }

    public function type(): string
    {
        return 'website';
    }
}
