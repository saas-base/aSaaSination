<?php

namespace Repository\Eloquent;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Repository\Contracts\Presentable;
use Illuminate\Database\Eloquent\Model;
use Core\Events\RepositoryEntityCreated;
use Core\Events\RepositoryEntityDeleted;
use Core\Events\RepositoryEntityUpdated;
use Illuminate\Database\Eloquent\Builder;
use Repository\Contracts\CriteriaInterface;
use Repository\Contracts\PresenterInterface;
use Repository\Traits\ComparesVersionsTrait;
use Repository\Contracts\RepositoryInterface;
use Repository\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Repository\Contracts\RepositoryCriteriaInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface, RepositoryCriteriaInterface
{
    use ComparesVersionsTrait;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model|Builder
     */
    protected $model;

    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = null;

    /**
     * Collection of Criteria.
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var bool
     */
    protected $skipPresenter = false;

    /**
     * @var \Closure
     */
    protected $scopeQuery = null;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app      = $app;
        $this->criteria = new Collection();
        $this->makeModel();
        $this->makePresenter();
        $this->makeValidator();
        $this->boot();
    }

    public function boot()
    {
        //
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    abstract public function model();

    /**
     * Specify Presenter class name.
     *
     * @return string
     */
    public function presenter()
    {
    }

    /**
     * Specify Validator class name of Prettus\Validator\Contracts\ValidatorInterface.
     *
     * @throws Exception
     * @return null
     */
    public function validator()
    {
        if (! isset($this->rules) || ! is_array($this->rules) || empty($this->rules)) {
            return;
        }

        if (! class_exists('Prettus\Validator\LaravelValidator')) {
            throw new Exception(trans('repository::packages.prettus_laravel_validation_required'));
        }

        $validator = app('Prettus\Validator\LaravelValidator');

        if ($validator instanceof ValidatorInterface) {
            $validator->setRules($this->rules);

            return $validator;
        }
    }

    /**
     * Set Presenter.
     *
     * @param $presenter
     *
     * @return $this
     */
    public function setPresenter($presenter)
    {
        $this->makePresenter($presenter);

        return $this;
    }

    /**
     * @throws RepositoryException
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * @param null $presenter
     *
     * @throws RepositoryException
     * @return PresenterInterface
     */
    public function makePresenter($presenter = null)
    {
        $presenter = ! is_null($presenter) ? $presenter : $this->presenter();

        if (! is_null($presenter)) {
            $this->presenter = is_string($presenter) ? $this->app->make($presenter) : $presenter;

            if (! $this->presenter instanceof PresenterInterface) {
                throw new RepositoryException("Class {$presenter} must be an instance of Repository\\Contracts\\PresenterInterface");
            }

            return $this->presenter;
        }
    }

    /**
     * @param null $validator
     *
     * @throws RepositoryException
     * @return null|ValidatorInterface
     */
    public function makeValidator($validator = null)
    {
        $validator = ! is_null($validator) ? $validator : $this->validator();

        if (! is_null($validator)) {
            $this->validator = is_string($validator) ? $this->app->make($validator) : $validator;

            if (! $this->validator instanceof ValidatorInterface) {
                throw new RepositoryException("Class {$validator} must be an instance of Prettus\\Validator\\Contracts\\ValidatorInterface");
            }

            return $this->validator;
        }
    }

    /**
     * Get Searchable Fields.
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Query Scope.
     *
     * @param \Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(\Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Retrieve data array for populate field select.
     *
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function lists($column, $key = null)
    {
        $this->applyCriteria();

        return $this->model->lists($column, $key);
    }

    /**
     * Retrieve data array for populate field select
     * Compatible with Laravel 5.3.
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($column, $key = null)
    {
        $this->applyCriteria();

        return $this->model->pluck($column, $key);
    }

    /**
     * Sync relations.
     *
     * @param int|string|Model $resolvable
     * @param $relation
     * @param $attributes
     * @param bool $detaching
     * @return mixed
     */
    public function sync($resolvable, $relation, $attributes, $detaching = true)
    {
        return $this->find($this->resolveId($resolvable))->{$relation}()->sync($attributes, $detaching);
    }

    /**
     * SyncWithoutDetaching.
     *
     * @param int|string|Model $resolvable
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function syncWithoutDetaching($resolvable, $relation, $attributes)
    {
        return $this->sync($this->resolveId($resolvable), $relation, $attributes, false);
    }

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    /**
     * Alias of All method.
     *
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }

    /**
     * Retrieve first data of repository.
     *
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        $results = $this->model->first($columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Retrieve first data of repository, or return new Entity.
     *
     * @param array $attributes
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function firstOrNew(array $attributes = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->model->firstOrNew($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Retrieve first data of repository, or create new Entity.
     *
     * @param array $attributes
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function firstOrCreate(array $attributes = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->model->firstOrCreate($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit   = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Retrieve all data of repository, simple paginated.
     *
     * @param null $limit
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->paginate($limit, $columns, 'simplePaginate');
    }

    /**
     * Find data by id or the model itself.
     *
     * @param int|string $resolvable [Primary Id or the model]
     * @param array $columns
     *
     * @return mixed
     */
    protected function resolve($resolvable, $columns = ['*'])
    {
        if ($resolvable instanceof $this->model) {
            return $resolvable;
        }

        return $this->model->findOrFail($resolvable, $columns);
    }

    protected function resolveId($resolvable)
    {
        return $resolvable instanceof $this->model ? $this->model->{$this->model->getKey()} : $resolvable;
    }

    /**
     * Find data by id.
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrFail($id, $columns);

        return $this->parserResult($model);
    }

    /**
     * Find data by id.
     *
     * @param int|string $resolvable [Primary Id or the model]
     * @param array $columns
     *
     * @return mixed
     */
    public function findOrResolve($resolvable, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->resolve($resolvable, $columns);

        return $this->parserResult($model);
    }

    /**
     * Find data by field and value.
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple fields.
     *
     * @param array $where
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->applyConditions($where);

        $model = $this->model->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple values in one field.
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereIn($field, $values)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by excluding multiple values in one field.
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereNotIn($field, $values)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by between values in one field.
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function findWhereBetween($field, array $values, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereBetween($field, $values)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Save a new entity in repository.
     *
     * @param array $attributes
     *
     * @throws ValidatorException
     * @throws RepositoryException
     * @return mixed
     */
    public function create(array $attributes)
    {
        if (! is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            if ($this->versionCompare($this->app->version(), '5.2.*', '>')) {
                $attributes = $this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
            } else {
                $model = $this->model->newInstance()->forceFill($attributes);
                $model->addVisible($this->model->getHidden());
                $attributes = $model->toArray();
            }

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param int|string $resolvable [Primary Id or the model]
     *
     *
     * @throws ValidatorException
     * @throws RepositoryException
     * @throws ValidatorException
     * @return mixed
     *
     */
    public function update($resolvable, array $attributes)
    {
        $this->applyScope();

        if (! is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $model = $this->model->newInstance()->forceFill($attributes);
            $model->addVisible($this->model->getHidden());
            $attributes = $model->toArray();
            $id         = $this->resolveId($resolvable);
            $this->validator->with($attributes)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->resolve($resolvable);
        $model->fill($attributes);
        $model->save();

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update or Create an entity in repository.
     *
     * @param array $attributes
     * @param array $values
     *
     * @throws RepositoryException
     * @throws ValidatorException
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();

        if (! is_null($this->validator)) {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->updateOrCreate($attributes, $values);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Delete a entity in repository by id.
     *
     * @param int|string|Model $resolvable
     *
     * @throws RepositoryException
     * @return int
     */
    public function delete($resolvable)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model         = $this->resolve($resolvable);
        $originalModel = clone $model;

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        $deleted = $model->delete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }

    /**
     * Delete multiple models by given criteria.
     *
     * @param array $where
     *
     * @throws RepositoryException
     * @return int
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $deleted = $this->model->delete();

        event(new RepositoryEntityDeleted($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }

    /**
     * Check if entity has relation.
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has($relation)
    {
        $this->model = $this->model->has($relation);

        return $this;
    }

    /**
     * Load relations.
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Add subselect queries to count the relations.
     *
     * @param mixed $relations
     * @return $this
     */
    public function withCount($relations)
    {
        $this->model = $this->model->withCount($relations);

        return $this;
    }

    /**
     * Load relation with closure.
     *
     * @param string $relation
     * @param closure $closure
     *
     * @return $this
     */
    public function whereHas($relation, $closure)
    {
        $this->model = $this->model->whereHas($relation, $closure);

        return $this;
    }

    /**
     * Set hidden fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function hidden(array $fields)
    {
        $this->model->setHidden($fields);

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    /**
     * Set visible fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function visible(array $fields)
    {
        $this->model->setVisible($fields);

        return $this;
    }

    /**
     * Push Criteria for filter the query.
     *
     * @param $criteria
     *
     * @throws RepositoryException
     * @return $this
     */
    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = new $criteria;
        }

        if (! $criteria instanceof CriteriaInterface) {
            throw new RepositoryException('Class '.get_class($criteria).' must be an instance of Repository\\Contracts\\CriteriaInterface');
        }
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Pop Criteria.
     *
     * @param $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria)
    {
        $this->criteria = $this->criteria->reject(function ($item) use ($criteria) {
            if (is_object($item) && is_string($criteria)) {
                return get_class($item) === $criteria;
            }

            if (is_string($item) && is_object($criteria)) {
                return $item === get_class($criteria);
            }

            return get_class($item) === get_class($criteria);
        });

        return $this;
    }

    /**
     * Get Collection of Criteria.
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Find data by Criteria.
     *
     * @param CriteriaInterface $criteria
     *
     * @throws RepositoryException
     * @return mixed
     */
    public function getByCriteria(CriteriaInterface $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        $results     = $this->model->get();
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Skip Criteria.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Reset all Criterias.
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = new Collection();

        return $this;
    }

    /**
     * Reset Query Scope.
     *
     * @return $this
     */
    public function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query.
     *
     * @return $this
     */
    protected function applyScope()
    {
        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback    = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }

    /**
     * Apply criteria in current Query.
     *
     * @return $this
     */
    protected function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria) {
            foreach ($criteria as $c) {
                if ($c instanceof CriteriaInterface) {
                    $this->model = $c->apply($this->model, $this);
                }
            }
        }

        return $this;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model                   = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    /**
     * Skip Presenter Wrapper.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipPresenter($status = true)
    {
        $this->skipPresenter = $status;

        return $this;
    }

    /**
     * Wrapper result data.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function parserResult($result)
    {
        if ($this->presenter instanceof PresenterInterface) {
            if ($result instanceof Collection || $result instanceof LengthAwarePaginator) {
                $result->each(function ($model) {
                    if ($model instanceof Presentable) {
                        $model->setPresenter($this->presenter);
                    }

                    return $model;
                });
            } elseif ($result instanceof Presentable) {
                $result = $result->setPresenter($this->presenter);
            }

            if (! $this->skipPresenter) {
                return $this->presenter->present($result);
            }
        }

        return $result;
    }
}
