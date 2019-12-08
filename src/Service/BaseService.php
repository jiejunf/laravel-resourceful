<?php


namespace Jiejunf\Resourceful\Service;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jiejunf\Resourceful\Model\ModelAdapter;

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

    public function index()
    {
        return $this->modelClass->newQuery()
            ->when(...$this->ifGroup())
            ->when(...$this->ifSearch())
            ->when(...$this->ifSort())
            ->when(...$this->ifPaginate());
    }

    public function model($id)
    {
        return $this->modelClass->newQuery()->findOrFail($id);
    }

    public function show($id)
    {
        return $this->model($id);
    }

    public function store($inputs)
    {
        return $this->modelClass->newQuery()->create($inputs);
    }

    public function update($id, $inputs)
    {
        $model = $this->modelClass->newQuery()->findOrFail($id);
        $model = $this->updateAttributes($model, $inputs);
        return $model->push() ? 1 : 0;
    }

    public function destroy($id)
    {
        return $this->modelClass->newQuery()->where('id', $id)->delete();
    }

    protected function ifPaginate(): array
    {
        return [
            # if:
            !is_null(request()->query('page')),
            # :
            function (Builder $builder) {
                return $builder->paginate(intval(request()->query('per_page')));
            },
            # else:
            function (Builder $builder) {
                return $builder->get();
            }
        ];
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

    protected function ifSearch(): array
    {
        // todo : BaseService::ifSearch()
        return [
            # if:
            false,
            # :
            null
            # else:
        ];
    }

    protected function ifSort(): array
    {
        return [
            # if:
            $sorts = request()->query('sort', false),
            # :
            function (Builder $query) use ($sorts) {
                foreach (explode('|', $sorts) as $sort) {
                    $query->orderBy(...explode(',', $sort));
                }
            }
            # else:
        ];
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
}
