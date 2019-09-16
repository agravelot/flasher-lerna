<?php

namespace App\Models\Contracts;

interface OpenGraphable
{
    public function title(): string;

    public function description(): string;

    public function type(): string;
}
