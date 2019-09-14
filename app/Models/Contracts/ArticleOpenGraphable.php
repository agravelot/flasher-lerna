<?php

namespace App\Models\Contracts;

use Illuminate\Support\Collection;

interface ArticleOpenGraphable
{
    public function title(): string;

    public function author(): string;

    public function tags(): Collection;

    public function publishedAt(): string;

    public function updatedAt(): string;
}
