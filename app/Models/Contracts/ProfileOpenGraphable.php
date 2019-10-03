<?php

namespace App\Models\Contracts;

interface ProfileOpenGraphable
{
    public function username(): string;

    public function firstName(): string;

    public function lastName(): string;

    public function gender(): string;
}
