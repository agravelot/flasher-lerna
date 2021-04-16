<?php

declare(strict_types=1);

namespace App\Http\OpenGraphs\Contracts;

interface ProfileOpenGraphable
{
    public function username(): string;

    public function firstName(): string;

    public function lastName(): string;

    public function gender(): string;
}
