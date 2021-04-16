<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class ModelTestCase extends TestCase
{
    /**
     * @param  array  $fillable
     * @param  array  $hidden
     * @param  array  $guarded
     * @param  array  $visible
     * @param  array  $casts
     * @param  array  $dates
     * @param  string  $collectionClass
     * @param  string  $primaryKey
     * @param  null  $connection
     *
     * - `$fillable` -> `getFillable()`
     * - `$guarded` -> `getGuarded()`
     * - `$table` -> `getTable()`
     * - `$primaryKey` -> `getKeyName()`
     * - `$connection` -> `getConnectionName()`: in case multiple connections exist.
     * - `$hidden` -> `getHidden()`
     * - `$visible` -> `getVisible()`
     * - `$casts` -> `getCasts()`: note that method appends incrementing key.
     * - `$dates` -> `getDates()`: note that method appends `[static::CREATED_AT, static::UPDATED_AT]`.
     * - `newCollection()`: assert collection is exact type. Use `assertEquals` on `get_class()` result, but not `assertInstanceOf`.
     */
    protected function runConfigurationAssertions(
        Model $model,
        $fillable = [],
        $hidden = [],
        $guarded = ['*'],
        $visible = [],
        $casts = ['id' => 'int'],
        $dates = ['created_at', 'updated_at'],
        $collectionClass = Collection::class,
        $table = null,
        $primaryKey = 'id',
        $connection = null,
        $slug = null
    ): void {
        $this->assertSame($fillable, $model->getFillable());
        $this->assertSame($guarded, $model->getGuarded());
        $this->assertSame($hidden, $model->getHidden());
        $this->assertSame($visible, $model->getVisible());
        $this->assertSame($casts, $model->getCasts());
        $this->assertSame($dates, $model->getDates());
        $this->assertSame($slug ?? $primaryKey, $model->getKeyName());

        $c = $model->newCollection();
        $this->assertSame($collectionClass, \get_class($c));
        $this->assertInstanceOf(Collection::class, $c);

        if (null !== $connection) {
            $this->assertSame($connection, $model->getConnectionName());
        }

        if (null !== $table) {
            $this->assertSame($table, $model->getTable());
        }
    }

    /**
     * @param  HasMany  $relation
     * @param  string  $key
     * @param  string  $parent
     * @param  \Closure  $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getQualifiedParentKeyName()`: in case of `HasOneOrMany` relation, there is no `getLocalKey()` method, so this one should be asserted.
     */
    protected function assertHasManyRelation(
        $relation,
        Model $model,
        Model $related,
        $key = null,
        $parent = null,
        \Closure $queryCheck = null
    ): void {
        $this->assertInstanceOf(HasMany::class, $relation);

        if (null !== $queryCheck) {
            $queryCheck->bindTo($this);
            $queryCheck($relation->getQuery(), $model, $relation);
        }

        if (null === $key) {
            $key = $model->getForeignKey();
        }

        $this->assertSame($key, $relation->getForeignKeyName());

        if (null === $parent) {
            $parent = $model->getKeyName();
        }

        $this->assertSame($model->getTable().'.'.$parent, $relation->getQualifiedParentKeyName());
    }

    /**
     * @param  BelongsTo  $relation
     * @param  string  $key
     * @param  string  $owner
     * @param  \Closure  $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getOwnerKey()`: `BelongsTo` relation and its extendings.
     */
    protected function assertBelongsToRelation(
        $relation,
        Model $model,
        Model $related,
        $key,
        $owner = null,
        \Closure $queryCheck = null
    ): void {
        $this->assertInstanceOf(BelongsTo::class, $relation);

        if (null !== $queryCheck) {
            $queryCheck->bindTo($this);
            $queryCheck($relation->getQuery(), $model, $relation);
        }

        $this->assertSame($key, $relation->getForeignKeyName());

        if (null === $owner) {
            $owner = $related->getKeyName();
        }

        $this->assertSame($owner, $relation->getOwnerKeyName());
    }

    /**
     * @param  BelongsToMany  $relation
     * @param  string  $key
     * @param  string  $parent
     * @param  \Closure  $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getQualifiedParentKeyName()`: in case of `HasOneOrMany` relation, there is no `getLocalKey()` method, so this one should be asserted.
     */
    protected function assertBelongsToManyRelation(
        $relation,
        Model $model,
        Model $related,
        $key,
        $parent = null,
        \Closure $queryCheck = null
    ): void {
        $this->assertInstanceOf(BelongsToMany::class, $relation);

        if (null !== $queryCheck) {
            $queryCheck->bindTo($this);
            $queryCheck($relation->getQuery(), $model, $relation);
        }

        $this->assertSame($key, $relation->getForeignPivotKeyName());

        if (null === $parent) {
            $parent = $model->getKeyName();
        }

        $this->assertSame($model->getTable().'.'.$parent, $relation->getQualifiedParentKeyName());
    }
}
