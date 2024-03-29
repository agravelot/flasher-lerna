<?php

declare(strict_types=1);

namespace App\Abilities;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

trait HasParentModel
{
    public function getTable(): string
    {
        if (! isset($this->table)) {
            return str_replace('\\', '', Str::snake(Str::plural(class_basename($this->getParentClass()))));
        }

        return $this->table;
    }

    /**
     * @throws ReflectionException
     */
    protected function getParentClass(): string
    {
        return (new ReflectionClass($this))->getParentClass()->getName();
    }

    /**
     * @throws ReflectionException
     */
    public function getForeignKey(): string
    {
        return Str::snake(class_basename($this->getParentClass())).'_'.$this->primaryKey;
    }

    /**
     * @throws ReflectionException
     */
    public function joiningTable($related, $instance = null): string
    {
        $models = [
            Str::snake(class_basename($related)),
            Str::snake(class_basename($this->getParentClass())),
        ];
        sort($models);

        return mb_strtolower(implode('_', $models));
    }
}
