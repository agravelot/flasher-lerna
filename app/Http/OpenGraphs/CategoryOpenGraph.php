<?php

namespace App\Http\OpenGraphs;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Contracts\OpenGraphable;
use App\Models\Contracts\ImagesOpenGraphable;
use App\Models\Contracts\ArticleOpenGraphable;

class CategoryOpenGraph implements OpenGraphable, ArticleOpenGraphable, ImagesOpenGraphable
{
    /**
     * @var Category
     */
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function author(): string
    {
        return $this->category->user->name;
    }

    public function tags(): Collection
    {
        return $this->category->categories()->pluck('name');
    }

    public function publishedAt(): string
    {
        return $this->category->published_at->toIso8601String();
    }

    public function updatedAt(): string
    {
        return $this->category->updated_at->toIso8601String();
    }

    public function images(): Collection
    {
        return $this->category->cover;
    }

    public function title(): string
    {
        return $this->category->name;
    }

    public function description(): string
    {
        return Str::limit($this->category->description, 150);
    }

    public function type(): string
    {
        //TODO ???
        return 'article';
    }
}
