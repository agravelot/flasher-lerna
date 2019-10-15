<?php

namespace App\Http\OpenGraphs\Contracts;

interface ProfileOpenGraphable
{
    public function username(): string;

    public function firstName(): string;

    public function lastName(): string;

    public function gender(): string;
}
