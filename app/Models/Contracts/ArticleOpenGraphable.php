<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

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
