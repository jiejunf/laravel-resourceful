<?php


namespace Jiejunf\Resourceful\Service;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Jiejunf\Resourceful\Model\ModelAdapter;
use Jiejunf\Resourceful\Resourceful;

class BaseService implements ResourceServiceInterface
{

    /**
     * @var Model
     */
    protected $modelClass;

    public function __construct(ModelAdapter $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function index()
    {
        $resources = $this->modelClass->newQuery()
            ->when(...$this->ifGroup())
            ->when(...$this->ifSearch())
            ->when(...$this->ifSort())
            ->when(...$this->ifPaginate());
        $resources->when(...$this->ifMakeHidden());
        return $resources;
    }

    protected function ifGroup(): array
    {
        // todo : BaseService::ifGroup
        return [
            # if:
            false,
            # :
            null
            # else:
        ];
    }

    /**
     * @return array
     */
    protected function ifSearch(): array
    {
        $searchString = '';
        return [
            # if:
            (
                ($searchable = Resourceful::serviceConfig('searchable', false))
                && ($searchString = request('search'))
            ),
            # :
            function (Builder $query) use ($searchable, $searchString) {
                $search = $this->makeSearch($searchable, $searchString);
                $query->where(function (Builder $query) use ($search) {
                    foreach ($search as $_search) {
                        $query->orWhere(...$_search);
                    }
                });
            }
            # else:
        ];
    }

    private function makeSearch(array $searchable, string $searchString)
    {
        $searches = [];
        if (strpos($searchString, ':') === false) {
            foreach ($searchable as $field) {
                $searches[] = [$field, 'like', $searchString];
            }
            return $searches;
        }
        foreach (explode('|', $searchString) as $searchStr) {
            if (strpos($searchStr, ':') === false) {
                $searches = array_merge($searches, $this->makeSearch($searchable, $searchStr));
                continue;
            }
            [$field, $value] = explode(':', $searchStr);
            if (!in_array($field, $searchable, true)) {
                continue;
            }
            $searches[] = [$field, 'like', $value];
        }
        return $searches;
    }

    protected function ifSort(): array
    {
        $sorts = $this->makeSorts();
        return [
            # if:
            isset($sorts),
            # :
            function (Builder $query) use ($sorts) {
                foreach ($sorts as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            }
            # else:
        ];
    }

    /**
     * @return array
     */
    protected function makeSorts(): array
    {
        $default = Resourceful::serviceConfig('sort_default', []);
        $queryStr = request()->query('sort', '');
        $query = $this->queryStr2Sorts($queryStr);
        return $query + $default;
    }

    /**
     * @param string $queryStr
     * @return array
     */
    protected function queryStr2Sorts($queryStr): array
    {
        $sorts = [];
        $sortables = Resourceful::serviceConfig('sortables', []);
        foreach (explode('|', $queryStr) as $queryStrSub) {
            $queryArr = explode(',', $queryStrSub);
            if (!in_array($queryArr[0], $sortables, true)) {
                continue;
            }
            $sorts[$queryArr[0]] = $queryArr[1] ?? 'asc';
        }
        return $sorts;
    }

    protected function ifPaginate(): array
    {
        return [
            # if:
            (
                Resourceful::serviceConfig('force_paginate')
                || !is_null(request()->query('page'))
            ),
            # :
            function (Builder $builder) {
                return $builder->paginate(intval(request()->query('per_page', Resourceful::serviceConfig('per_page_default'))));
            },
            # else:
            function (Builder $builder) {
                return $builder->get();
            }
        ];
    }

    private function ifMakeHidden()
    {
        return [
            # if
            $hidden = Resourceful::serviceConfig('hidden'),
            # :
            function ($collection) use ($hidden) {
                /** @var Collection $collection */
                return $collection->makeHidden($hidden);
            }
            # else
        ];
    }

    /**
     * @param $id
     * @return Model
     */
    public function show($id)
    {
        return $this->model($id);
    }

    /**
     * @param $id
     * @return Model
     */
    public function model($id)
    {
        return $this->modelClass->newQuery()->findOrFail($id);
    }

    /**
     * @param $inputs
     * @return Model
     */
    public function store($inputs)
    {
        return $this->modelClass->newQuery()->create($inputs);
    }

    /**
     * @param $id
     * @param $inputs
     * @return bool
     */
    public function update($id, $inputs)
    {
        $model = $this->model($id);
        $model = $this->updateAttributes($model, $inputs);
        return $model->push();
    }

    /**
     * @param Model $model
     * @param array $inputs
     * @return Model
     */
    private function updateAttributes($model, $inputs)
    {
        foreach ($inputs as $field => $input) {
            if (!is_array($input) || array_key_exists($field, $model->getCasts())) {
                $model->$field = $input;
                continue;
            }
            $this->updateAttributes($model->$field, $input);
        }
        return $model;
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function destroy($id)
    {
        return $this->model($id)->delete();
    }
}
