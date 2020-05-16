<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class FileExist implements Rule
{
    private string $disk;
    private string $directory;

    /**
     * Create a new rule instance.
     */
    public function __construct(string $disk, string $directory)
    {
        $this->disk = $disk;
        $this->directory = $directory;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     */
    public function passes($attribute, $value): bool
    {
        $path = rtrim($value ?? '', '/');
        $path = $this->directory.'/'.$path;

        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The file specified for :attribute does not exist';
    }
}
