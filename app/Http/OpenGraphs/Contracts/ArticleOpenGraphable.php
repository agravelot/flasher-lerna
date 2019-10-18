<?php

namespace App\Http\OpenGraphs\Contracts;

use Illuminate\Support\Collection;

interface ArticleOpenGraphable
{
    public function title(): string;

    public function author(): string;

    public function tags(): Collection;

    public function publishedAt(): string;

    public function updatedAt(): string;
}
