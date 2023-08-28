<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ModelService
{

    /**
     * Save the entity to the database
     *
     * This will either insert or update the entity in the database
     *
     * @param Model $model
     *
     * @return bool
     */
    public function save($model)
    {
        return $model->save();
    }

    /**
     * @return string|Model
     */
    abstract public function getClassName();

    /**
     * Get a Query to execute with
     *
     * @return Builder
     */
    public function query()
    {
        return $this->getClassName()::query();
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null|object
     */
    public function find($id)
    {
        return $this->query()->find($id);
    }

    public function read($id)
    {
        return $this->getModel($id);
    }

    /**
     * Find the record or throws an exception
     *
     * @param int|Model $id
     * @param bool $fail If true, calls findOrFail and throws a not found exception if not found
     *
     * @return Model|Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|null|object
     * @throws \Exception|ModelNotFoundException
     */
    protected function getModel($id, $fail = true)
    {
        if ($id instanceof Model) {
            return $id;
        } else if (is_int($id) || is_string($id)) {
            $model = $this->query()->find($id);
            if ($fail && !$model) {
                throw new NotFoundHttpException(__('Not found'));
            } else {
                return $model;
            }
        } else {
            throw new \Exception('Invalid id value passed to findOrAbort');
        }
    }

    /**
     * Make a new Entity/Model with the given values
     *
     * @param array $values
     *
     * @return Model|mixed
     */
    static function make($values)
    {
        $class = app(static::class)->getClassName();

        return new $class($values);
    }

	/**
	 * Finds a single object by a set of criteria.
	 *
	 * @param mixed[] $criteria The criteria.
	 *
	 * @return object|null The object.
	 */
	public function findOneBy(array $criteria)
	{
		$query = $this->query();
		foreach ($criteria as $key => $value) {
			if (is_array($value)) {
				$query->whereIn($key, $value);
			} else {
				$query->where($key, $value);
			}
		}

		return $query->first();
	}
}
